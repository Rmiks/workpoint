<?php

final class socAuthProviderTwitter extends socAuthProvider implements socAuthProviderInterface
{

    protected static $sessionName = 'socTwitterInstance';

    protected $providerType = socAuth::AUTH_TYPE_TWITTER;

    /** @var array */
    protected $userData;

    /**
     * @var \Abraham\TwitterOAuth\TwitterOAuth
     */
    protected $api;

    public function __construct()
    {
        if( ( $authConfig = $this->getAuthConfig() ) )
        {
            $this->api = new Abraham\TwitterOAuth\TwitterOAuth(
                $this->config[ 'consumer_key' ],
                $this->config[ 'consumer_secret' ]
            );
        }
    }

    private function setRequestToken( $requestToken )
    {
        $_SESSION[ self::$sessionName ][ 'requestToken' ] = $requestToken;
    }

    private function getRequestToken()
    {
        return isset( $_SESSION[ self::$sessionName ] ) ? $_SESSION[ self::$sessionName ][ 'requestToken' ] : null;
    }

    public function getSignInUrl()
    {
        $requestToken = $this->api->oauth( 'oauth/request_token', [ 'oauth_callback' => $this->getAuthHandlerUrl() ] );
        $this->setRequestToken( $requestToken );
        $url = $this->api->url( 'oauth/authenticate', [ 'oauth_token' => $requestToken[ 'oauth_token' ] ] );
        return $url;
    }


    public function getAccessToken()
    {
        $oauthVerifier = get( $_GET, 'oauth_verifier', null );

        $requestToken = $this->getRequestToken();
        $this->api->setOauthToken( $requestToken[ 'oauth_token' ], $requestToken[ 'oauth_token_secret' ] );
        return $this->api->oauth( 'oauth/access_token', [ 'oauth_verifier' => $oauthVerifier ] );
    }

    public function handleAuthentication( array $request )
    {
        $accessToken = $this->getAccessToken();
        if( !empty( $accessToken[ 'oauth_token' ] ) && !empty( $accessToken[ 'oauth_token_secret' ] ) )
        {
            $this->accessToken = $accessToken;
            $this->userData    = $this->getUserData();
            return true;
        }
        return false;
    }


    public function getUserData()
    {
        $this->api->setOauthToken( $this->accessToken[ 'oauth_token' ], $this->accessToken[ 'oauth_token_secret' ] );
        return $this->api->get( 'account/verify_credentials' );
    }

    public function getProfileImage()
    {
        return $this->userData->profile_image_url;
    }

    public function getProfileLink()
    {
        return 'http://www.twitter.com/' . $this->userData->screen_name;
    }

    public function getAuthenticationTokenString()
    {
        return $this->userData->id_str;
    }

    public function getName()
    {
        return $this->userData->name;
    }

    public function getEmail()
    {
        return null;
    }

    public function getStorableAccessToken()
    {
        return implode( ':', [ $this->accessToken[ 'oauth_token' ], $this->accessToken[ 'oauth_token_secret' ] ] );
    }


    public function setAccessToken( $accessToken )
    {
        $parts = explode( ':', $accessToken );
        return $this->accessToken = [
            'oauth_token'        => $parts[ 0 ],
            'oauth_token_secret' => $parts[ 1 ]
        ];
    }

    public function validateAccessToken( $networkUserId, $accessToken )
    {
        $accessTokenParts = explode( ':', $accessToken );
        $this->api->setOauthToken( $accessTokenParts[ 0 ], $accessTokenParts[ 1 ] );

        $result = $this->api->get( 'account/verify_credentials' );
        return !property_exists( $result, 'errors' ) && $result->id_str === $networkUserId;
    }
}
