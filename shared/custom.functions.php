<?php

function pp( &$a = null, &$b = null, &$d = null, &$e = null, &$f = null, &$g = null, &$h = null, &$j = null )
{
    $vars = array( &$a, &$b, &$d, &$e, &$f, &$g, &$h, &$j );
    $parts = explode( '/', trim( leaf_get( 'path_part' ), '/' ) );
    if ( $parts[0] == '' )
        $parts[0] = null;
    for ($i = 0, $c = sizeof( $parts ); $i < $c; $vars[$i] = $parts[$i], $i++)
        ;
}