<?php

final class socAuthProviderGoogle extends socAuthProvider implements socAuthProviderInterface
{

    protected static $sessionName = 'socGoogleInstance';

    protected $providerType = socAuth::AUTH_TYPE_GOOGLE;

    /**
     * @var Google_Service_Oauth2_Userinfoplus
     */
    protected $userData;

    /**
     * @var Google_Client
     */
    protected $api;


    public function __construct()
    {
        if( ( $authConfig = $this->getAuthConfig() ) )
        {
            $this->api = new Google_Client( [
                'application_name' => $this->config[ 'app_name' ],
                'client_id'        => $this->config[ 'client_id' ],
                'client_secret'    => $this->config[ 'client_secret' ]
            ] );
        }
    }

    public function getSignInUrl()
    {
        $this->api->setRedirectUri( $this->getAuthHandlerUrl() );
        return $this->api->createAuthUrl( $this->config[ 'scope' ] );
    }

    public function handleAuthentication( array $request )
    {
        if( ( $code = get( $request, 'code' ) ) )
        {
            $this->api->setRedirectUri( $this->getAuthHandlerUrl() );
            $token = $this->api->fetchAccessTokenWithAuthCode( $code );
            if( isset( $token[ 'access_token' ] ) )
            {
                $this->api->setAccessToken( $token );
                $this->accessToken = $this->getAccessToken();
                $this->userData    = $this->getUserData();
                return true;
            }
        }
        return false;
    }

    public function getAccessToken()
    {
        return get( $this->api->getAccessToken(), 'access_token' );
    }


    public function setUserData()
    {
        $url = 'https://www.googleapis.com/oauth2/v2/userinfo?access_token=' . $this->accessToken;


        $response = json_decode( file_get_contents( $url ), true );

        $credentials = new Google_Service_Oauth2_Userinfoplus();
        $credentials->setFamilyName( get( $response, 'family_name' ) );
        $credentials->setEmail( get( $response, 'email' ) );
        $credentials->setId( get( $response, 'id' ) );
        $credentials->setGender( get( $response, 'gender' ) );
        $credentials->setGivenName( get( $response, 'given_name' ) );
        $credentials->setHd( get( $response, 'hd' ) );
        $credentials->setLink( get( $response, 'link' ) );
        $credentials->setName( get( $response, 'name' ) );
        $credentials->setPicture( get( $response, 'picture' ) );
        $credentials->setVerifiedEmail( get( $response, 'verified_email' ) );
        $this->userData = $credentials;
    }

    public function getUserData()
    {
        $oauth = new Google_Service_Oauth2( $this->api );;
        return $oauth->userinfo->get();
    }

    public function getProfileImage()
    {
        return $this->userData->getPicture();
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
        return $this->userData->verifiedEmail ? $this->userData->getEmail() : null;
    }

    public function validateAccessToken( $networkUserId, $accessToken )
    {
        $oauth = new Google_Service_Oauth2( $this->api );
        /** @var Google_Service_Oauth2_Tokeninfo $result */

        try
        {
            /** @var Google_Service_Oauth2_Tokeninfo $result */
            $result = $oauth->tokeninfo( [ 'access_token' => $accessToken ] );
            return
                $result->getAudience() === $this->config[ 'client_id' ] &&
                $result->getUserId() === $networkUserId;
        } catch( Google_Service_Exception $ex )
        {
            return false;
        }
    }
}
