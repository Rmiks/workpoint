<?

class site extends leaf_module
{
    // Don't show these templates in menu
    protected $skipTemplates = [];

    // Auto filled from shared/config
    protected $authorizedTemplates = [];

    // Template which is responsible for authorization (ex. users/authorization)
    protected $authorizationTemplate = null;


    protected $root = null;

    protected $openedObject = null;

    protected $objectId = null;

    /** @var Menu */
    protected $menu = null;

    protected $pathPart = null;

    protected $user = null;

    public function __construct()
    {
        parent::__construct();

        $this->root         = $this->getLanguageRoot();
        $this->objectId     = leaf_get( 'object_id' );
        $this->openedObject = _core_load_object( $this->objectId );
        $this->pathPart     = leaf_get( 'path_part' );
    }

    public function output()
    {
        // Send 404 if opened object not available
        if( ( !$this->openedObject ) || ( !$this->objectId ) )
        {
            $rewrite = _core_load_module( 'leaf_rewrite' );
            $rewrite->error_404( $force404 = false, $outputHtml = true );
            return;
        }


        // Start flash message handler
        $useSession = leaf_get_property( 'useSession', false );
        if(
            ( !empty( $useSession ) )
            &&
            ( $useSession === true )
        )
        {
            leafFlash::gc();
        }


        // Create menu for opened object
        $this->menu = new Menu( $this->openedObject );
        $this->menu->setProperties( [ "expandAll" => true ] );
        $this->prepareMenuTemplates();


        // Handle opened object authorization
        $this->handleAuthorization();


        // run viewObject only if path parts are empty or object has been marked as supporting them
        if( empty( $this->pathPart ) || !empty( $this->openedObject->hasPathParts ) )
        {
            $this->openedObject->content = $this->openedObject->viewObject( [], $this );
            $this->openedObject->content = translate_objects_id( $this->openedObject->content );
            $this->openedObject->content = embedObject::replacePlaceholders( $this->openedObject->content );
        }


        // If path_part isn't handled, force error 404
        if( leaf_get( 'path_part' ) )
        {
            $rewrite = _core_load_module( 'leaf_rewrite' );
            $rewrite->error_404( $force404 = false, $outputHtml = true );
            return;
        }


        $assign[ 'openedObject' ]    = $this->openedObject;
        $assign[ 'aboveTheFoldCSS' ] = $this->getAboveTheFoldCSS();
        $assign[ 'site' ]            = $this;

        // Render output depending of output mode
        switch( $this->openedObject->getOutputMode() )
        {
            case "page":

                if( isset( $this->openedObject->customLayout ) )
                {
                    $content = alias_cache::fillInAliases( $this->openedObject->content );
                }
                else
                {
                    $assign[ 'menu' ]            = $this->menu;
                    $assign[ 'properties' ]      = leaf_get( 'properties' );
                    $assign[ 'user' ]            = $this->user;
                    $assign[ 'metaDescription' ] = $this->getDescription( $this->openedObject );
                    $assign[ 'metaImage' ]       = $this->getMetaImage( $this->openedObject );


                    $assign[ 'styleHash' ]     = getValue( 'styleHash' );
                    $assign[ 'fontsHash' ]     = getValue( 'fontsHash' );
                    $assign[ 'jsHash' ]        = getValue( 'jsHash' );
                    $assign[ "assetCacherJs" ] = $this->getAssetCacherJs();


                    if( $this->root )
                    {
                        $assign[ 'root' ]     = $this->root;
                        $assign[ 'rootVars' ] = $this->root->object_data[ 'data' ];
                    }

                    //check if GA script is cached
                    $ga_cached             = 'js/analytics.js';
                    $assign[ 'ga_script' ] = file_exists( PATH . $ga_cached ) ? $ga_cached : '//www.google-analytics.com/analytics.js';

                    $content = $this->renderView( 'content.tpl', $assign );
                }

                break;

            case "text":

                header( 'Content-Type: text/plain' );
                $content = alias_cache::fillInAliases( $this->openedObject->content );

                break;

            case "xml":

                header( 'Content-Type: text/xml' );
                $content = $this->renderView( 'xml.tpl', $assign );

                break;

            default:
                throw new Exception( 'Unsupported output mode' );
        }

        return $content;
    }


    public function renderView( $template, $assign = [] )
    {
        $smarty = new leaf_smarty( $this->module_path . 'templates/' );
        $smarty->register_outputfilter( [ 'alias_cache', 'fillInAliases' ] );
        $smarty->assign( $assign );

        return $smarty->fetch( $template );
    }


    public static function getLanguageRoot()
    {
        return _core_load_object( leaf_get( 'root' ) );
    }


    public function getMenuSkipTemplates()
    {
        return $this->menu->getSkipTemplates();
    }


    public function getDescription( $object )
    {
        if( method_exists( $object, 'getMetaDescription' ) && ( ( $description = $object->getMetaDescription() ) ) )
        {
            return $description;
        }

        $commonFieldValue = objectTree::getFirstCommonFieldValue( $object, 'metaDescription' );

        if( empty( $commonFieldValue[ 'value' ] ) )
        {
            return null;
        }

        return $commonFieldValue[ 'value' ];
    }

    public function getMetaImage( $object )
    {
        if ( method_exists( $object, 'getMetaImage' ) && ( ( $image = $object->getMetaImage() ) ) )
        {
            return $image;
        }

        $commonFieldValue = objectTree::getFirstCommonFieldValue( $object, 'metaImage' );

        if ( empty( $commonFieldValue[ 'value' ] ) )
        {
            return null;
        }

        return orp( $commonFieldValue[ 'value' ] );
    }

    protected function prepareMenuTemplates()
    {
        if( !$this->user )
        {
            $this->skipTemplates = array_unique( array_merge( $this->skipTemplates, $this->authorizedTemplates ) );
        }

        // Set templates to menu
        $this->menu->setSkipTemplates( $this->skipTemplates );
    }


    protected function openedObjectRequiresAuthorization()
    {
        if( !$this->openedObject || $this->user )
        {
            return false;
        }

        if( method_exists( $this->openedObject, 'requiresAuthorization' ) )
        {
            return $this->openedObject->requiresAuthorization();
        }

        foreach( $this->menu->getObjectsTree() as $item )
        {
            if( empty( $item[ 'template' ] ) )
            {
                continue;
            }

            if( in_array( $item[ 'template' ], $this->authorizedTemplates ) )
            {
                return true;
            }
        }
    }


    protected function handleAuthorization()
    {
        if( !$this->openedObjectRequiresAuthorization() )
        {
            return false;
        }

        $authorizationObject = objectTree::getFirstObject( $this->authorizationTemplate );

        // Authorization is required, but template is not available. Send 403 - Access Denied
        if( !$authorizationObject )
        {
            leafHttp::send403();
        }

        // Override opened object and clear path_part
        $this->openedObject = $authorizationObject;
        leaf_set( 'path_part', null );
    }

    public function getAboveTheFoldCSS()
    {
        $filePath = PATH . 'styles/abovethefold.css';
        return file_exists( $filePath ) ? file_get_contents( PATH . 'styles/abovethefold.css' ) : null;
    }

    protected function getAssetCacherJs()
    {
        return @file_get_contents( PATH . 'js/assetCacher.js' );
    }
}
