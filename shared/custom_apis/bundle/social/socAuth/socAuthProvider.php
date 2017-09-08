<?php

abstract class socAuthProvider
{
    protected $providerType;

    protected static $sessionName;

    /**
     * @var string url of authentication template with a specific type's pathPart
     * @see SiteServices_Authentication
     */
    protected $authHandlerUrl = null;

    /**
     * @var array app config
     */
    protected $config;

    /**
     * @var string access token for querying soc network's API
     */
    protected $accessToken;

    protected $userData;

    public function getProviderType()
    {
        return $this->providerType;
    }

    public function getAuthConfig()
    {
        $authConfigList = leaf_get_property( [ 'authentication' ], false );
        if( is_array( $authConfigList ) && array_key_exists( $this->providerType, $authConfigList ) )
        {
            $this->config = $authConfigList[ $this->providerType ];
            return $this->config;
        }
        return null;
    }

    public function setAuthHandlerUrl( $authHandlerUrl )
    {
        $this->authHandlerUrl = $authHandlerUrl;
    }

    public function getAuthHandlerUrl()
    {
        return $this->authHandlerUrl;
    }

    public function setReturnUrl( $returnUrl )
    {
        $_SESSION[ static::$sessionName ][ 'returnUrl' ] = $returnUrl;
    }

    public function getReturnUrl()
    {
        return isset( $_SESSION[ static::$sessionName ] ) ? get( $_SESSION[ static::$sessionName ], 'returnUrl' ) : WWW;
    }

    /**
     * Returns a socAuthProvider* object by network's type & network user's id
     *
     * @return socAuth
     */
    public function getAuthByProviderData()
    {
        $params = [
            'where' => [
                't.networkUserId = "' . $this->getAuthenticationTokenString() . '"',
                't.type = "' . $this->getProviderType() . '"'
            ]
        ];

        /** @var socAuth $socAuth */
        $socAuth = socAuth::getObject( $params );
        if( $socAuth )
        {
            $socAuth->setAuthProvider( $this );
            return $socAuth;
        }
        return null;
    }

    public function getStorableAccessToken()
    {
        return $this->accessToken;
    }
}
