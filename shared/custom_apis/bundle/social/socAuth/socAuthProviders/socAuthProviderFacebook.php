<?php

final class socAuthProviderFacebook extends socAuthProvider implements socAuthProviderInterface
{

    protected static $sessionName = 'socFacebookInstance';

    protected $providerType = socAuth::AUTH_TYPE_FACEBOOK;

    /**
     * @var Facebook\GraphNodes\GraphUser
     */
    protected $userData;

    /**
     * @var \Facebook\Facebook
     */
    protected $api;


    public function __construct()
    {
        if( ( $authConfig = $this->getAuthConfig() ) )
        {
            $this->api = new \Facebook\Facebook( [
                'app_id'                => $authConfig[ 'app_id' ],
                'app_secret'            => $authConfig[ 'app_secret' ],
                'default_graph_version' => get( $authConfig, 'default_graph_version', "v2.8" ),
                'default_access_token'  => $authConfig[ 'app_id' ] . '|' . $authConfig[ 'app_secret' ]
            ] );
        }

    }

    public function getSignInUrl()
    {
        $helper   = $this->api->getRedirectLoginHelper();
        $loginUrl = $helper->getLoginUrl( $this->getAuthHandlerUrl(), get( $this->config, 'scope', [] ) );
        return $loginUrl;
    }

    public function getAccessToken()
    {
        return $this->api->getRedirectLoginHelper()->getAccessToken();
    }


    public function handleAuthentication( array $request )
    {
        $accessToken = $this->getAccessToken();
        if( $accessToken )
        {
            $this->accessToken = $accessToken;
            $user              = $this->getUserData();
            if( $user )
            {
                $this->userData = $user->getGraphUser();
                return true;
            }
        }
        return false;
    }


    public function getUserData( $networkUserId = 'me' )
    {
        return $this->api->get( '/' . $networkUserId . '?fields=name,email,link,picture', $this->accessToken );
    }


    public function getProfileLink()
    {
        return $this->userData->getLink();
    }


    public function getAuthenticationTokenString()
    {
        return $this->userData->getId();
    }

    public function getName()
    {
        return $this->userData->getName();
    }


    public function getEmail()
    {
        return $this->userData->getEmail();
    }

    public function getProfileImage()
    {
        return $this->userData->getPicture()->getUrl();
    }

    public function validateAccessToken( $networkUserId, $accessToken )
    {
        $response = $this->api->get( 'debug_token?input_token=' . $accessToken )->getDecodedBody();
        if( ( $data = get( $response, 'data' ) ) )
        {
            if( get( $data, 'user_id' ) === $networkUserId
                &&
                get( $data, 'app_id' ) === $this->config[ 'APP_ID' ]
            )
            {
                return true;
            }
        }
        return false;
    }


}
