<?php

$deployment = true;

require_once( dirname( __FILE__ ) . '/prepend.cli.php' );

$files = [
    "styleHash" => "styles/style.css",
    "fontsHash" => "styles/fonts.css",
    "jsHash"    => "js/index.js"
];

array_walk( $files, "saveHash" );

function saveHash( $item, $key )
{
    $releasePath = dirname( __DIR__ );
    $filePath    = implode( "/", [ $releasePath, $item ] );
    $hash        = md5_file( $filePath );
    setValue( $key, $hash );
    $destinationPath = implode( "/", [
        $releasePath,
        substr_replace( $item, "." . $hash, strpos( $item, "." ), 0 )
    ] );
    copy( $filePath, $destinationPath );
}
