<?php

final class socAuthProviderLinkedin extends socAuthProvider implements socAuthProviderInterface
{
    protected static $sessionName = 'socLinkedinInstance';

    protected $providerType = socAuth::AUTH_TYPE_LINKEDIN;

    /**
     * @var \LinkedIn\LinkedIn
     */
    protected $api = null;

    private function getApi()
    {
        if( !$this->api && ( $authConfig = $this->getAuthConfig() ) )
        {
            $this->api = new \LinkedIn\LinkedIn( [
                'api_key'      => $this->config[ 'api_key' ],
                'api_secret'   => $this->config[ 'api_secret' ],
                'callback_url' => $this->getAuthHandlerUrl()
            ] );
        }
        return $this->api;
    }

    public function getSignInUrl()
    {
        return $this->getApi()->getLoginUrl( $this->config[ 'scope' ] );
    }

    public function getAccessToken()
    {
        return $this->getApi()->getAccessToken( $_GET[ 'code' ] );
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
                $this->userData = $user;
                return true;
            }
        }
        return false;
    }


    public function getUserData()
    {
        return $this->getApi()->get( '/people/~:(id,picture-url,email-address,first-name,last-name,public-profile-url)' );
    }


    public function getProfileLink()
    {
        return $this->userData[ 'publicProfileUrl' ];
    }


    public function getAuthenticationTokenString()
    {
        return $this->userData[ 'id' ];
    }

    public function getFirstName()
    {
        return $this->userData[ 'firstName' ];
    }

    public function getLastName()
    {
        return $this->userData[ 'lastName' ];
    }

    public function getName()
    {
        return implode( ' ', [ $this->userData[ 'firstName' ], $this->userData[ 'lastName' ] ] );
    }


    public function getEmail()
    {
        return $this->userData[ 'emailAddress' ];
    }

    public function getProfileImage()
    {
        return $this->userData[ 'pictureUrl' ];
    }


}