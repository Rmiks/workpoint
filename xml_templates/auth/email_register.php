<?php

class email_register_template extends xml_template
{

    /**
     * @param site $module
     */
    function dynamic_output( & $module = null )
    {
        $data = &$this->object_data[ 'data' ];

        $data[ "errors" ]    = $this->getErrors();
        $socAuth             = socAuth::getHandlerTemplate();
        $data[ 'actionUrl' ] = $socAuth->getHandlerUrl( socAuth::AUTH_TYPE_EMAIL, orp( $this ) );
        socAuthProviderEmail::clearErrors();
    }

    protected function getErrors()
    {
        $storedErrors = socAuthProviderEmail::getStoredErrors();
        array_walk( $storedErrors, function( $item ) use ( & $errors )
        {
            $errors[ $item[ "field" ] ] = $item;
        } );

        return $errors;
    }
}


?>