<?php

final class socEmail
{

    /**
     * @var socEmail
     */
    private static $instance = null;
    private
        $handlerUrl  = null,
        $credentials = null
    ;

    /**
     * @return socEmail
     */
    public static function getInstance()
    {
        if (self::$instance === null)
        {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function getSalt()
    {
        return '*(yh234Hiq4iut4buasdiofvq3h-9';
    }

    public function setHandlerUrl( $handlerUrl = null )
    {
        $this->handlerUrl = $handlerUrl;
    }

    public function getHandlerUrl()
    {
        return $this->handlerUrl;
    }

    public function getSignInUrl()
    {
        return $this->getHandlerUrl();
    }

    public function handleAuthentication( array $request )
    {
        $this->credentials = $request;
        return true;
    }

    public function getCredentials()
    {
        return $this->credentials;
    }

    public function getImageUrl()
    {
        return null;
    }

    public function getProfileLink()
    {
        return null;
    }

    public function getAuthenticationTokenString( array $request )
    {
        $token = md5( get( $request, 'email' ) . $this->getSalt() );

        return $token;
    }

    public function getHashedPassword( array $request )
    {
        $input = get( $request, 'password' );
        if (!mb_strlen( $input ))
        {
            return '';
        }
        else
        {
            $password = md5( $this->getSalt() . $input );
            return $password;
        }
    }

    public function getName()
    {
        $credentials = $this->getCredentials();
        return implode( ' ', array(
            get( $credentials, 'firstName' ),
            get( $credentials, 'lastName' )
        ));
    }

    public function getEmail()
    {
        $credentials = $this->getCredentials();
        return get( $credentials, 'email', null );
    }

}
