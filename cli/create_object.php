<?php

require_once( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'prepend.cli.php' );

/**
 * baseObject
 */
$objectClassName = $argv[ 1 ];
$tableName       = $argv[ 1 ] . "_data";
$objectStub      = file_get_contents( SHARED_PATH . "custom_apis/stubs/baseObject.stub" );

$replace = [
    "%objectClass%" => $objectClassName,
    "%tableName%"   => $tableName
];

$objectResult = str_replace( array_keys( $replace ), $replace, $objectStub );

$destinationPath     = SHARED_PATH . "custom_apis/";
$destinationFileName = $objectClassName . ".class.php";

file_put_contents( $destinationPath . $destinationFileName, $objectResult );


/**
 * baseModule
 */
$moduleClassName = $objectClassName . "s";
$moduleStub      = file_get_contents( SHARED_PATH . "custom_apis/stubs/baseModule.stub" );
$replace         = [
    "%objectClass%" => $objectClassName,
    "%moduleClass%" => $moduleClassName
];

$moduleResult        = str_replace( array_keys( $replace ), $replace, $moduleStub );
$destinationPath     = ADMIN_PATH . "modules/$moduleClassName/";
$destinationFileName = "module.php";

if( !file_exists( $destinationPath ) )
{
    mkdir( $destinationPath, 0755 );
    recurse_copy( SHARED_PATH . "custom_apis/stubs/templates/", ADMIN_PATH . "modules/$moduleClassName/templates" );
}

file_put_contents( $destinationPath . $destinationFileName, $moduleResult );
shell_exec( "composer dump-autoload" );
shell_exec( "php cli/migrate.php" );

function recurse_copy( $src, $dst )
{
    $dir = opendir( $src );
    @mkdir( $dst );
    while( false !== ( $file = readdir( $dir ) ) )
    {
        if( ( $file != '.' ) && ( $file != '..' ) )
        {
            if( is_dir( $src . '/' . $file ) )
            {
                recurse_copy( $src . '/' . $file, $dst . '/' . $file );
            }
            else
            {
                copy( $src . '/' . $file, $dst . '/' . $file );
            }
        }
    }
    closedir( $dir );
}

echo "Don't forget to enable module in " . ADMIN_PATH . "config/config.php";