<?php

class email_verification_template extends xml_template
{

    public $hasPathParts = true;

    /**
     * @param site $module
     */
    function dynamic_output( & $module = null )
    {
        $data = &$this->object_data[ 'data' ];

        pp( $verificationToken );
        if( $verificationToken &&
            ( $auth = socAuthEmailHelper::getAuthEntryByToken( $verificationToken ) )
        )
        {
            /** @var socAuthProviderEmail $provider */
            $provider = socAuth::factoryAuthProvider( socAuthProviderEmail::class );
            $auth->setAuthProvider( $provider );
            $provider->setUserData( $auth->person );
            $auth->completeVerification();

            leaf_set( 'path_part', null );
        }
    }
}


?>