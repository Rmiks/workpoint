<?php

require_once( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'prepend.cli.php' );

$optimizer = new ImageOptimizer();

if( !empty( $argv[ 1 ] ) && $argv[ 1 ] === 'optimizers' )
{
    $optimizer->printUsableOptimizers();
}


$optimizer->optimizeLeafFiles();
$optimizer->optimizeFiles();
