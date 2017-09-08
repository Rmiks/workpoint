<?php

class login_template extends xml_template
{

    /**
     * @param site $module
     */
    function dynamic_output( & $module = null )
    {
        $data = &$this->object_data[ 'data' ];


        $socAuth     = socAuth::getHandlerTemplate();
        $returnUrl   = orp( $this );
        $extraParams = [
            'emailLoginFormUrl' => orp( objectTree::getFirstObject( 'auth/email_login', null, false ) )
        ];

        $data[ 'authData' ] = $socAuth->getAvailableHandlerURLs( $returnUrl, $extraParams );
    }
}


?>