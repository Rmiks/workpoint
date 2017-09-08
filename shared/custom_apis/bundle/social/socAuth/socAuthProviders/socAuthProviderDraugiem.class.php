<?php

final class socAuthProviderDraugiem extends socAuthProvider implements socAuthProviderInterface
{
    const sessionName = 'socDraugiemInstance';

    protected $providerType = socAuth::AUTH_TYPE_DRAUGIEM;

    /**
     * @var DraugiemApi
     */
    protected $api;

    public function __construct( $initData = null )
    {
        if( ( $authConfig = $this->getAuthConfig() ) )
        {
            $this->api = new DraugiemApi(
                $this->config[ 'app_id' ],
                $this->config[ 'app_key' ]
            );
        }
    }

    public function getSignInUrl()
    {
        return $this->api->getLoginURL( $this->getAuthHandlerUrl() );
    }

    public function handleAuthentication( array $request )
    {
        $session = $this->api->getSession();
        if( $session )
        {
            $this->accessToken = $this->api->getUserKey();
            $this->userData    = $this->getUserData();
        }
        return $session;
    }

    public function getUserData()
    {
        return $this->api->getUserData();
    }


    public function setUserData( $networkUserId )
    {
        $response = $this->api->apiCall( 'userdata', [ 'apikey' => $this->accessToken ] );

        if( key_exists( 'users', $response ) && key_exists( $networkUserId, $response[ 'users' ] ) )
        {
            $this->userData = $response[ 'users' ][ $networkUserId ];
        }
    }

    public function getProfileImage()
    {
        return $this->userData[ 'imgm' ];
    }

    public function getProfileLink()
    {
        return 'http://www.draugiem.lv' . $this->userData[ 'url' ];
    }

    public function getAuthenticationTokenString()
    {
        return $this->userData[ 'uid' ];
    }

    public function getAccessToken()
    {
        return $this->api->getUserKey();
    }

    public function getName()
    {
        return $this->userData[ 'name' ] . ' ' . $this->userData[ 'surname' ];
    }

    public function getEmail()
    {
        return null;
    }

    public function validateAccessToken( $networkUserId, $accessToken )
    {
        $result = $this->api->apiCall( 'userdata', [ 'apikey' => $accessToken ] );

        if( is_array( $result ) && key_exists( 'users', $result ) )
        {
            if( !empty( $result[ 'users' ] ) && key_exists( $networkUserId, $result[ 'users' ] ) )
            {
                return true;
            }
        }
        return false;
    }
}
