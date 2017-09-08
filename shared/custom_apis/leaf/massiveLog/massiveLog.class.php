<?

class massiveLog extends bundleBaseObject
{
    const tableName = 'admin_log';
    protected static $searchFields = [ 'object_title', 'object_url', 'user_name' ];

    protected
        $request_time,
        $user_ip,
        $user_forwarded_ip,
        $http_host,
        $request_uri,
        $query_string,
        $request_method,
        $http_referer,
        $user_agent,
        $http_content_type,
        $http_cookie,
        $data_get,
        $data_post,
        $data_files,
        $data_session,
        $data_cookie,
        $argv,
        $user_name,
        $action_name,
        $object_title,
        $object_url,
        $module_name;

    protected $fieldsDefinition = [
        'request_time'      => [
            'null' => true
        ],
        'user_ip'           => [
            'null'     => true,
            'optional' => true
        ],
        'user_forwarded_ip' => [
            'null'     => true,
            'optional' => true
        ],
        'http_host'         => [
            'null'     => true,
            'optional' => true
        ],
        'request_uri'       => [
            'null'     => true,
            'optional' => true
        ],
        'query_string'      => [
            'null'     => true,
            'optional' => true // php built-in server might not have $_SERVER['QUERY_STRING'] var
        ],
        'request_method'    => [
            'null' => true
        ],
        'http_referer'      => [
            'null'     => true,
            'optional' => true
        ],
        'user_agent'        => [
            'null'     => true,
            'optional' => true
        ],
        'http_content_type' => [
            'null'     => true,
            'optional' => true
        ],
        'http_cookie'       => [
            'optional' => true
        ],
        'data_get'          => [
            'optional' => true
        ],
        'data_post'         => [
            'optional' => true
        ],
        'data_cookie'       => [
            'optional' => true
        ],
        'data_files'        => [
            'optional' => true
        ],
        'data_session'      => [
            'optional' => true
        ],
        'argv'              => [
            'optional' => true
        ],
        'user_name'         => [
            'null'     => true,
            'optional' => true
        ],
        'action_name'       => [
            'null'     => true,
            'optional' => true
        ],
        'object_title'      => [
            'null'     => true,
            'optional' => true
        ],
        'object_url'        => [
            'null'     => true,
            'optional' => true
        ],
        'module_name'       => [
            'null'     => true,
            'optional' => true
        ]
    ];

    protected static $_tableDefsStr = [
        self::tableName => [
            'fields'  =>
                '
            id                  int auto_increment
            request_time        datetime
            user_ip             varchar(255)
            user_forwarded_ip   text
            http_host           varchar(255)
            request_uri         text
            query_string        text
            request_method      varchar(255)
            http_referer        text
            user_agent          varchar(255)
            http_content_type   varchar(255)
            http_cookie         mediumtext
            data_get            longtext
            data_post           mediumtext
            data_cookie         mediumtext
            data_files          mediumtext
            data_session        mediumtext
            argv                mediumtext
            user_name           varchar(255)
            action_name         varchar(255)
            object_title        text
            object_url          text
            module_name         varchar(255)
            '
            ,
            'indexes' => 'primary id',
            'engine'  => 'InnoDB'
        ]
    ];

    function getDefinitionArr()
    {
        return $this->fieldsDefinition;
    }

    public static function log()
    {
        $data   = self::getDataFromRequest();
        $object = getObject( __CLASS__ );
        $object->variablesSave( $data );
    }

    protected static function getDataFromRequest()
    {
        $data = [];

        $data[ 'request_time' ] = date( 'Y-m-d H:i:s' );

        $data[ 'user_ip' ]           = @$_SERVER[ 'REMOTE_ADDR' ];
        $data[ 'user_forwarded_ip' ] = @$_SERVER[ 'HTTP_X_FORWARDED_FOR' ];

        $data[ 'http_host' ]      = @$_SERVER[ 'HTTP_HOST' ];
        $data[ 'request_uri' ]    = @$_SERVER[ 'REQUEST_URI' ];
        $data[ 'query_string' ]   = @$_SERVER[ 'QUERY_STRING' ];
        $data[ 'request_method' ] = @$_SERVER[ 'REQUEST_METHOD' ];

        $data[ 'http_referer' ] = @$_SERVER[ 'HTTP_REFERER' ];

        $data[ 'user_agent' ]        = @$_SERVER[ 'HTTP_USER_AGENT' ];
        $data[ 'http_content_type' ] = @$_SERVER[ 'CONTENT_TYPE' ];

        $data[ 'http_cookie' ] = @$_SERVER[ 'HTTP_COOKIE' ];

        $data[ 'data_get' ]     = isset( $_GET ) ? serialize( $_GET ) : null;
        $data[ 'data_post' ]    = isset( $_POST ) ? serialize( $_POST ) : null;
        $data[ 'data_cookie' ]  = isset( $_COOKIE ) ? serialize( $_COOKIE ) : null;
        $data[ 'data_files' ]   = isset( $_FILES ) ? serialize( $_FILES ) : null;
        $data[ 'data_session' ] = isset( $_SESSION ) ? serialize( $_SESSION ) : null;

        $data[ 'argv' ]         = ( isset( $_SERVER[ 'argv' ] ) ) ? serialize( $_SERVER[ 'argv' ] ) : null;
        $data[ 'user_name' ]    = self::getUserName();
        $data[ 'action_name' ]  = self::getActionName();
        $data[ 'module_name' ]  = self::getModuleName();
        $data[ 'object_title' ] = self::getObjectTitle();
        $data[ 'object_url' ]   = self::getObjectUrl();

        return $data;
    }

    private static function getActionName()
    {
        return get( $_GET, 'do', null );
    }

    private static function getModuleName()
    {
        return get( $_GET, 'module', null );
    }

    //returns object/s that is being used
    //obj || array
    private static function getUsedObject()
    {

        static $objects = null; //poor mans cache
        $objectIds  = [];
        $moduleName = self::getModuleName();
        $actionName = self::getActionName();

        if( is_null( $objects ) )
        {
            //handle content related actions
            if( $moduleName == 'content' )
            {
                if( $actionName == 'delete_objects' )
                {
                    $objectIds = get( $_POST, 'objects', false );

                }
                else
                {
                    $objectId = get( $_GET, 'object_id', false );
                    if( $objectId )
                    {
                        $objectIds = [ $objectId ];
                    }
                }

                if( !empty( $objectIds ) )
                {
                    foreach( $objectIds as $key => $id )
                    {
                        $objects[] = _core_load_object( $id );
                    }
                }
            }
            else
            { //handle other (module, etc) related actions
                $modProps = [];
                $objectId = get( $_GET, 'id', false );
                if( $objectId )
                {
                    $objectIds[] = $objectId;
                }
                try
                {
                    $modRefClass = new ReflectionClass( $moduleName );
                    $modProps    = $modRefClass->getDefaultProperties();
                } catch( Exception $e )
                {
                    //ou well
                }
                //obj class
                $objClass = null;
                if( isset( $modProps[ 'mainObjectClass' ] ) && !empty( $modProps[ 'mainObjectClass' ] ) )
                {

                    $objClass = $modProps[ 'mainObjectClass' ];
                    $objId    = !empty( $objectIds ) ? reset( $objectIds ) : null;

                    if( $objId && $objClass )
                    {
                        $objects[] = getObject( $objClass, $objId );
                    }
                }
                else
                {
                    //no class,this means module has own custom model handling
                }
            }
        }
        return $objects;
    }


    private static function getObjectUrl()
    {
        $objectUrl  = null;
        $obj        = self::getUsedObject();
        $objectUrls = [];

        if( $obj )
        {
            if( !is_array( $obj ) )
            {
                $obj = [ $obj ];
            }

            foreach( $obj as $objItem )
            {
                if( is_a( $objItem, 'xml_template' ) || is_a( $objItem, 'file' ) )
                {
                    $objectUrls[] .= orp( $objItem );
                }
                else if( is_a( $objItem, 'bundleBaseObject' ) )
                { //custom api
                    //TODO: Add basic bundle urls?
                }
            }
        }

        if( !empty( $objectUrls ) )
        {
            $objectUrl = join( ';', $objectUrls );
        }

        return $objectUrl;
    }

    private static function getObjectTitle()
    {
        $objectTitle  = null;
        $obj          = self::getUsedObject();
        $objectTitles = [];

        //try to get object and get its name
        if( $obj )
        {
            if( !is_array( $obj ) )
            {
                $obj = [ $obj ];
            }

            foreach( $obj as $objItem )
            {
                if( ( is_a( $objItem, 'xml_template' ) || is_a( $objItem, 'file' ) ) && isset( $objItem->object_data[ 'name' ] ) )
                {
                    $objectTitles[] = $objItem->object_data[ 'name' ];
                }
                else if( is_a( $objItem, 'bundleBaseObject' ) )
                { //custom api
                    //get objects dispay string
                    $title = $objItem->getDisplayString();

                    //try to guess its name
                    if( empty( $title ) )
                    {
                        if( property_exists( $objItem, 'name' ) )
                        {
                            $title = $objItem->name;
                        }
                    }

                    $objectTitles[] = $title;
                }
                else
                {
                    $objectTitles[] = get_class( $objItem );
                }
            }
        }

        if( !empty( $objectTitles ) )
        {
            $objectTitle = join( ';', $objectTitles );
        }

        //if everything fails try to read data from post
        if( empty( $objectTitle ) )
        {
            $objectTitle = get( $_POST, 'name', '' );
        }

        return $objectTitle;
    }

    private static function getUserName()
    {
        $username = null;
        if( isset( $_SESSION ) == true && defined( 'SESSION_NAME' ) )
        {
            //get  from session
            if( isset( $_SESSION[ SESSION_NAME ][ 'user' ][ 'name' ] ) )
            {
                $username = $_SESSION[ SESSION_NAME ][ 'user' ][ 'name' ];
            }
            else
            {
                //try to get from db by last loggen in user?
            }
        }

        return $username;
    }

    //checks if data is serialized
    //returns bollean
    public function is_serialized( $data )
    {
        // if it isn't a string, it isn't serialized
        if( !is_string( $data ) )
            return false;
        $data = trim( $data );
        if( 'N;' == $data )
            return true;
        if( !preg_match( '/^([adObis]):/', $data, $badions ) )
            return false;
        switch( $badions[ 1 ] )
        {
            case 'a' :
            case 'O' :
            case 's' :
                if( preg_match( "/^{$badions[1]}:[0-9]+:.*[;}]\$/s", $data ) )
                    return true;
                break;
            case 'b' :
            case 'i' :
            case 'd' :
                if( preg_match( "/^{$badions[1]}:[0-9.E-]+;\$/", $data ) )
                    return true;
                break;
        }
        return false;
    }
}