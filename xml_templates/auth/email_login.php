<?php

class email_login_template extends xml_template
{

    /**
     * @param site $module
     */
    function dynamic_output( & $module = null )
    {
        $data                           = &$this->object_data[ 'data' ];
        $registerTemplate               = objectTree::getFirstObject( 'auth/email_register', null, false );
        $data[ 'emailRegisterFormUrl' ] = $registerTemplate ? orp( $registerTemplate ) : null;


        $socAuth             = socAuth::getHandlerTemplate();
        $data[ 'actionUrl' ] = $socAuth->getHandlerUrl( socAuth::AUTH_TYPE_EMAIL, orp( $this ) );
    }
}


?>