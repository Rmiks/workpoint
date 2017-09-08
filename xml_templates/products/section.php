<?php

class Products_Section extends xml_template
{
    public function dynamic_output( & $module = null )
    {
        $firstChild = objectTree::getFirstChild( $this );
        leafHttp::redirect( orp( $firstChild ) );
    }
}
