<?php

class SiteServices_Authentication extends xml_template
{

    public $hasPathParts = true;

    /**
     * @param site $module
     */
    public function dynamic_output( & $module = null )
    {
        if( isset( $_GET[ 'fail' ] ) )
        {
            leafHttp::redirect( WWW );
        }
        $authenticationType = trim( leaf_get( 'path_part' ), '/' );
        $providerTypeList   = socAuth::listAuthProviderTypes();
        if( !empty( $authenticationType ) && in_array( $authenticationType, $providerTypeList ) )
        {
            leaf_set( 'path_part', null );
            $extraParams = [];

            if( isset( $_GET[ 'authRedirect' ] ) )
            {
                $url = get( $_GET, 'returnUrl', WWW );
                $this->redirectToAuthSite( $authenticationType, $url, $extraParams );
            }
            $handler = socAuth::factoryAuthProvider( $authenticationType );
            return $this->handleAuthentication( $authenticationType, $handler );
        }
    }

    public function redirectToAuthSite( $type, $returnUrl, $extraParams = [] )
    {
        $providerInstance = socAuth::factoryAuthProvider( $type );
        $handlerUrl       = $this->getHandlerUrl( $type, null, false, $extraParams );
        $providerInstance->setReturnUrl( $returnUrl );
        $providerInstance->setAuthHandlerUrl( $handlerUrl );
        leafHttp::redirect( $providerInstance->getSignInUrl() );
    }


    public function getAvailableHandlerURLs( $returnUrl = null, $extraParams = [] )
    {
        $return = [];

        $handlers = socAuth::listAuthProviderTypes();
        foreach( $handlers as $item )
        {
            $return[ $item ] = $this->getHandlerUrl( $item, $returnUrl, true, $extraParams );
        }
        return $return;
    }

    public function getHandlerUrl( $handlerType, $returnUrl = null, $authRedirect = false, $extraParams = [] )
    {
        if( $handlerType === socAuth::AUTH_TYPE_EMAIL )
        {
            $baseUrl = !empty( $extraParams ) && isset( $extraParams[ 'emailLoginFormUrl' ] ) ? $extraParams[ 'emailLoginFormUrl' ] : orp( $this ) . $handlerType;
        }
        else
        {
            $baseUrl = orp( $this ) . $handlerType;
        }


        $params = [
            'returnUrl'    => $returnUrl,
            'authRedirect' => $authRedirect ? true : null
        ];

        $query = http_build_query( array_merge( $params, $extraParams ) );
        return $query ? $baseUrl . '?' . $query : $baseUrl;
    }

    /**
     * @param                                 $authenticationType
     * @param socAuthProvider                 $providerInstance
     */
    public function handleAuthentication( $authenticationType, $providerInstance )
    {

        $returnUrl = $providerInstance->getReturnUrl();
        $providerInstance->setAuthHandlerUrl( $this->getHandlerUrl( $authenticationType, null ) );
        $request = $_SERVER[ 'REQUEST_METHOD' ] === 'GET' ? $_GET : $_POST;

        if( $providerInstance->handleAuthentication( $request ) )
        {

            $socAuth = $providerInstance->getAuthByProviderData();

            if( $socAuth )
            {
                $socAuth->updateTokens();
                $socAuth->person->logInstanceIn();
            }
            // No person found
            else
            {
                $socAuth = new socAuth();
                $socAuth->setAuthProvider( $providerInstance );

                $socPerson = $socAuth->createNewSocPerson();
                $socPerson->logInstanceIn();
            }
        }


        leafHttp::redirect( $returnUrl );
    }

}