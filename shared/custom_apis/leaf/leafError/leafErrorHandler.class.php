<?

use Whoops\Handler\Handler;
use Whoops\Exception\Frame;



/**
 * Catches an exception and converts it to a JSON
 * response. Additionally can also return exception
 * frames for consumption by an API.
 */
class leafErrorHandler extends Handler
{
    public static $readableErrorCodes = array(
        E_ERROR 	        => 'FATAL ERROR',
        E_WARNING 	        => 'WARNING',
        E_PARSE 	        => 'PARSE ERROR',
        E_NOTICE 	        => 'NOTICE',
        E_CORE_ERROR 	    => 'CORE ERROR',
        E_CORE_WARNING 	    => 'CORE WARNING',
        E_COMPILE_ERROR 	=> 'COMPILE ERROR',
        E_COMPILE_WARNING 	=> 'COMPILE WARNING',
        E_USER_ERROR 	    => 'FATAL ERROR',
        E_USER_WARNING 	    => 'WARNING',
        E_USER_NOTICE 	    => 'NOTICE',
        E_STRICT 	        => 'STRICT ERRROR',
        E_RECOVERABLE_ERROR => 'RECOVERABLE ERROR',
        E_DEPRECATED 	    => 'DEPRECATED ERROR',
        E_USER_DEPRECATED 	=> 'DEPRECATED ERROR',
        E_ALL               => 'STRICT',
    );

    public static $fatalErrorCodes = array(
        E_ERROR,
        E_CORE_ERROR,
        E_COMPILE_ERROR,
        E_USER_ERROR,
    );

    public function handle()
    {
        $exception          = $this->getException();
        $fatalError         = DEV_MODE ? true : $this->isFatalError( $exception->getCode() );
        $error_reporting    = error_reporting();
        $display_errors     = ini_get('display_errors') == 1;

        // error can only be logged if db is functional
        // by this time, there is access to custom apis
        if( $error_reporting && function_exists( 'dbIsConnected' ) && dbIsConnected() )
        {
            $params = array(
                'message'       => $exception->getMessage(),
                'file'          => $exception->getFile(),
                'line'          => $exception->getLine(),
                'level'         => $exception->getCode(),
                //'context'       => $errcontext,
                'stackTrace'    => $exception->getTrace(),
            );
            leafError::create( $params );
        }


        if( !$display_errors && !$fatalError )
        {
            return Handler::LAST_HANDLER;
        }
        else if( $fatalError )
        {
            return Handler::DONE;
        }

        $errorOutput = '<strong style="color: red">Leaf '  . self::getErrorNameByCode( $exception->getCode() ) . ':</strong> ' . $exception->getMessage() . ' in <strong>' . $exception->getFile() . '</strong> on line <strong>' . $exception->getLine() . '</strong><br />'. "\n" ;

        if( php_sapi_name() == 'cli' )
        {
            $errorOutput = strip_tags( $errorOutput );
            file_put_contents( 'php://stderr', $errorOutput );
        }
        else
        {
            echo $errorOutput;
        }

        return Handler::LAST_HANDLER;
    }

    public function isFatalError( $code )
    {
        return in_array( $code, self::$fatalErrorCodes );
    }


    public static function getErrorNameByCode( $code )
    {
        $errorName = null;
        switch ( $code )
        {
            case E_USER_ERROR:
                $errorName =  'ERROR';
                break;

            case E_NOTICE:
            case E_USER_NOTICE:
                $errorName =  'NOTICE';
                break;

            case E_WARNING:
            case E_USER_WARNING:
                $errorName =  'WARNING';
                break;

            case E_STRICT:
                $errorName =  'STRICT ERROR';
                break;

            case E_RECOVERABLE_ERROR:
                $errorName =  'RECOVERABLE ERROR';
                break;

            case E_DEPRECATED:
            case E_USER_DEPRECATED:
                $errorName =  'DEPRECATED ERROR';
                break;

            default:
                $errorName =  'UNKNOWN ERROR';
        }

        return $errorName;
    }



}