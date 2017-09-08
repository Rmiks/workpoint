<?php

final class socAuthProviderVkontakte extends socAuthProvider implements socAuthProviderInterface
{
    protected static $sessionName = 'socVkontakteInstance';

    protected $providerType = socAuth::AUTH_TYPE_VKONTAKTE;

    /**
     * @var \BW\Vkontakte
     */
    protected $api = null;

    public function __construct()
    {
        if( ( $authConfig = $this->getAuthConfig() ) )
        {
            $this->api = new \BW\Vkontakte( [
                'client_id'     => $this->config[ 'app_id' ],
                'client_secret' => $this->config[ 'app_key' ],
                'scope'         => $this->config[ 'scope' ]
            ] );
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
            $token = $this->api->getAccessToken();
            if( ( $accessToken = get( $token, 'access_token' ) ) )
            {
                $this->accessToken         = $accessToken;
                $this->userData            = $this->getUserData( $token[ 'user_id' ] );
                $this->userData[ 'email' ] = get( $token, 'email' );
                return true;
            }
        }
        return false;
    }

    public function getUserData( $userId = null )
    {
        $user = $this->api->api( 'users.get', [
            'user_id' => $userId,
            'fields'  => [
                'name',
                'screen_name',
                'photo_big'
            ]
        ] );
        return $user[ 0 ];
    }

    public function getAuthenticationTokenString()
    {
        return $this->userData[ 'id' ];
    }

    public function getAccessToken()
    {
        return $this->accessToken;
    }

    public function getName()
    {
        return implode( ' ', [ $this->userData[ 'first_name' ], $this->userData[ 'last_name' ] ] );
    }

    public function getProfileLink()
    {
        return 'http://vk.com/' . $this->userData[ 'screen_name' ];
    }

    public function getEmail()
    {
        return $this->api->getUserEmail();
    }

    public function getProfileImage()
    {
        return $this->userData[ 'photo_big' ];
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