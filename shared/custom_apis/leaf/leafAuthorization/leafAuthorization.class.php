<?php

class leafAuthorization extends leafBaseObject
{

    protected static $config;

    public static function _autoload( $className )
    {
        parent::_autoload( $className );

        self::$config = leaf_get( 'properties', __CLASS__ );
        if( empty( self::$config ) || ( !is_array( self::$config ) ) )
        {
            self::$config = [
                'userClass'  => 'leafUser',
                'groupClass' => 'leafUserGroup'
            ];
        }

        // verify user and group classes
        $class = get( self::$config, 'userClass' );
        if( !class_exists( $class ) || !in_array( 'leafUserInterface', class_implements( $class ) ) )
        {
            trigger_error( 'Invalid ' . __CLASS__ . ' userClass: ' . $class, E_USER_ERROR );
        }

        $class = get( self::$config, 'groupClass' );
        if( !class_exists( $class ) || !in_array( 'leafUserGroupInterface', class_implements( $class ) ) )
        {
            trigger_error( 'Invalid ' . __CLASS__ . ' groupClass: ' . $class, E_USER_ERROR );
        }

    }

    public static function getUserClass()
    {
        return get( self::$config, 'userClass' );
    }

    public static function getGroupClass()
    {
        return get( self::$config, 'groupClass' );
    }

    public static function listUsers()
    {
        return call_user_func( [ self::getUserClass(), 'getCollection' ] );
    }

    public static function listGroups()
    {
        return call_user_func( [ self::getGroupClass(), 'getCollection' ] );
    }

    public static function getUser( $id )
    {
        return call_user_func( [ self::getUserClass(), 'getById' ], $id );
    }

    public static function getGroup( $id )
    {
        return call_user_func( [ self::getGroupClass(), 'getById' ], $id );
    }


}
