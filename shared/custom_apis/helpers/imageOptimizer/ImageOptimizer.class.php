<?

class ImageOptimizer
{
    /** @var array */
    protected $configuredOptimizers;

    /** @var array */
    protected $usableOptimizers;

    public function __construct()
    {
        $this->configuredOptimizers = leaf_get_property( "imageOptimizer" );

        $this->setUsableOptimizers();
    }

    protected function lookForOptimizers()
    {
        $supportedOptimizers = $this->configuredOptimizers;

        $existingOptimizers = [];

        foreach( $supportedOptimizers as $format => $optimizers )
        {
            foreach( $optimizers as $optimizer )
            {
                if( ( $path = $this->getExecutablePath( $optimizer[ "tool" ] ) ) )
                {
                    $optimizer[ "path" ]             = $path;
                    $existingOptimizers[ $format ][] = $optimizer;
                }
            }
        }
        return $existingOptimizers;
    }

    public function printUsableOptimizers()
    {
        echo 'Optimizers on system:', PHP_EOL;
        dump( $this->usableOptimizers );
    }

    protected function setUsableOptimizers()
    {
        $available = $this->lookForOptimizers();
        $usable    = [];
        //use preferred first, then available as fallback
        foreach( $available as $mime => $optimizers )
        {
            if( isset( $preferred[ $mime ] ) && in_array( $preferred[ $mime ], $optimizers ) )
            {
                $usable[ $mime ] = $preferred[ $mime ];
            }
            else
            {
                $usable[ $mime ] = reset( $optimizers );
            }
        }
        $this->usableOptimizers = $usable;
    }

    protected function getExecutablePath( $optimizer )
    {
        $finder = new Symfony\Component\Process\ExecutableFinder();
        return $finder->find( $optimizer );
    }

    public function optimizeFiles()
    {
        $files = $this->getUnoptimizedFiles();

        foreach( $files as $file )
        {
            $filePath = LEAF_FILE_ROOT . $file[ 'file_name' ];

            if( file_exists( $filePath ) )
            {
                $mime = getimagesize( $filePath )[ 'mime' ];

                $optimizer = $this->usableOptimizers[ $mime ];

                $command = $this->getShellCommand( $optimizer, $filePath );
                shell_exec( $command );

                dbUpdate( 'files', [ 'optimized' => date( 'Y-m-d H:i:s' ) ], [ 'object_id' => $file[ 'object_id' ] ] );
            }
        }
    }

    public function optimizeLeafFiles()
    {
        $images = leafFile::getUnoptimizedImages();
        foreach( $images as $image )
        {
            $filePath = LEAF_FILE_ROOT . $image->path;

            if( file_exists( $filePath ) )
            {
                $mime = getimagesize( $filePath )[ 'mime' ];

                $optimizer = $this->usableOptimizers[ $mime ];

                $command = $this->getShellCommand( $optimizer, $filePath );
                shell_exec( $command );

                $image->optimized = date( 'Y-m-d H:i:s' );
                $image->save();

                $this->deleteCachedImageVersions( $image );
            }
        }
    }

    /**
     * @param array  $optimizer
     * @param string $filePath
     *
     * @return string
     */
    protected function getShellCommand( $optimizer, $filePath )
    {
        $executablePath = $optimizer[ "path" ];
        $options        = $optimizer[ "options" ];

        $command = "$executablePath $options $filePath";

        if( $optimizer[ "tool" ] === "gifsicle" )
        {
            $command .= " -o $filePath";
        }
        return $command;
    }

    protected function deleteCachedImageVersions( leafFile $image )
    {
        $cachedImages = $image->getCachedImages();
        foreach( $cachedImages as $imageSize => $imageProperties )
        {
            if( file_exists( $imageProperties[ 'path' ] ) )
            {
                //echo 'Deleting :',  $imageProperties['path'], PHP_EOL;
                unlink( $imageProperties[ 'path' ] );
            }
        }
        // calling this function will re-create cached images we just deleted (only this time from optimized source image!)
        $image->getCachedImages();
    }

    protected function getUnoptimizedFiles()
    {
        $q     = '
        SELECT *
        FROM files
        WHERE optimized IS NULL';
        $files = dbGetAll( $q );
        return $files;
    }
}