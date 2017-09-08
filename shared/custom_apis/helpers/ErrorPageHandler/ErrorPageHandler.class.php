<?

use Whoops\Handler\Handler;
use Whoops\Exception\Frame;


class ErrorPageHandler extends Handler
{
    public static $fatalErrorCodes = array(
        E_ERROR,
        E_CORE_ERROR,
        E_COMPILE_ERROR,
        E_USER_ERROR,
    );
    
    public function handle()
    {
        $exception = $this->getException();
        
        $languageCode = 'en';
        
        if(
            isset( $_SERVER['REQUEST_URI'] )
            &&
            preg_match( '/^\/(?P<language>[a-z]{2})\//ui', $_SERVER['REQUEST_URI'], $matches )
        )
        {
            if( !empty( $matches['language'] ) && $this->hasErrorPage( $matches['language'] ) )
            {
                $languageCode = $matches['language'];
            }
        }
        
        echo file_get_contents( $this->getErrorPagePath( $languageCode ) );
        return Handler::QUIT;
    }
    
    public function getErrorPagePath( $language )
    {
        return dirname( SHARED_PATH ) . '/errors/500.' . $language . '.html';
    }
    
    public function hasErrorPage( $language )
    {
        return file_exists( $this->getErrorPagePath( $language ) );
    }
    
    public function isFatalError( $code )
    {
        $fatalErrorCodes = array(
            E_ERROR,
            E_CORE_ERROR,
            E_COMPILE_ERROR,
            E_USER_ERROR,
        );
        
        return in_array( $code, self::$fatalErrorCodes );
    }
    

}