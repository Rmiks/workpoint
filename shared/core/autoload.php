<?php
$composerPath = dirname( __DIR__ ) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'composer' . DIRECTORY_SEPARATOR;
require_once $composerPath . 'ClassLoader.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'ClassLoader.php';
$loader     = new \Leaf\Autoload\ClassLoader();
$namespaces = require_once $composerPath . 'autoload_namespaces.php';
$psr4       = require_once $composerPath . 'autoload_psr4.php';
$classMap   = require_once $composerPath . 'autoload_classmap.php';
$files      = require_once $composerPath . 'autoload_files.php';
foreach( $namespaces as $namespace => $path )
{
    $loader->set( $namespace, $path );
}
foreach( $psr4 as $namespace => $path )
{
    $loader->setPsr4( $namespace, $path );
}
foreach( $files as $fileIdentifier => $path )
{
    if( empty( $GLOBALS[ '__composer_autoload_files' ][ $fileIdentifier ] ) )
    {
        require $path;
        $GLOBALS[ '__composer_autoload_files' ][ $fileIdentifier ] = true;
    }
}
if( $classMap )
{
    $loader->addClassMap( $classMap );
}
$loader->register( true );
return $loader;