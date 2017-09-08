<?php

final class socAuthProviderOdnoklassniki extends socAuthProvider implements socAuthProviderInterface
{
    protected static $sessionName = 'socOdnoklassnikiInstance';

    protected $providerType = socAuth::AUTH_TYPE_ODNOKLASSNIKI;

    /**
     * @var Holystix\Odnoklassniki\Api
     */
    protected $api = null;

    public function __construct()
    {
        if( ( $authConfig = $this->getAuthConfig() ) )
        {
            $this->api = new Holystix\Odnoklassniki\Api(
                $this->config[ 'client_id' ],
                $this->config[ 'application_key' ],
                $this->config[ 'client_secret' ],
                $this->config[ 'scope' ]
            );
        }
    }

    public function setAuthHandlerUrl( $handlerUrl )
    {
        $this->api->setRedirectUri( $handlerUrl );
        parent::setAuthHandlerUrl( $handlerUrl );
    }

    public function getSignInUrl()
    {
        return $this->api->getLoginUrl();
    }

    public function handleAuthentication( array $request )
    {
        if( ( $code = get( $request, 'code' ) ) )
        {
            $this->api->authenticate( $code );
            if( ( $accessToken = $this->api->getAccessToken() ) )
            {
                $this->accessToken = $accessToken;
                $this->userData    = $this->getUserData();
                return true;
            }
        }
        return false;
    }

    public function getUserData()
    {
        return $this->api->getUser();
    }

    public function getProfileImage()
    {
        return $this->userData[ 'pic_2' ];
    }

    public function getProfileLink()
    {
        return 'http://www.odnoklassniki.ru/profile/' . $this->userData[ 'uid' ];
    }

    public function getAuthenticationTokenString()
    {
        return $this->userData[ 'uid' ];
    }

    public function getAccessToken()
    {
        return $this->api->getAccessToken();
    }

    public function getName()
    {
        return $this->userData[ 'name' ];
    }

    public function getEmail()
    {
        return null;
    }

    public function getFirstName()
    {
        return $this->userData[ 'first_name' ];
    }

    public function getLastName()
    {
        return $this->userData[ 'last_name' ];
    }
}