<?php


final class socAuthProviderEmail extends socAuthProvider
{
    const sessionName                 = 'socEmailInstance';
    const ACTION_REGISTRATION_KEYWORD = 'register';

    protected $providerType = socAuth::AUTH_TYPE_EMAIL;

    protected $errors = [];

    private $registrationVerificationToken;

    /** @var array */
    protected $userData;

    protected $registrationFieldsDefinition = [
        'email'          => [
            'not_empty'        => true,
            'type'             => 'email',
            'validateFunction' => [
                __CLASS__,
                'validateUniqueEmail'
            ]
        ],
        'password'       => [
            'not_empty'        => true,
            'validateFunction' => [
                __CLASS__,
                'validatePasswordLength'
            ]
        ],
        'passwordRepeat' => [
            'not_empty' => true
        ]
    ];

    protected $passwordChangeFieldsDefinition = [
        'password'       => [
            'not_empty'        => true,
            'validateFunction' => [
                __CLASS__,
                'validatePasswordLength'
            ]
        ],
        'passwordRepeat' => [
            'not_empty' => true
        ]
    ];

    private function isRegistrationRequest( $request )
    {
        return isset( $request[ 'action' ] ) && $request[ 'action' ] === self::ACTION_REGISTRATION_KEYWORD;
    }


    public function getSignInUrl()
    {
        // TODO: Implement getSignInUrl() method.
    }

    public function getErrors()
    {
        return $this->errors;
    }

    protected function doPasswordsMatch( $password, $passwordRepeat )
    {
        return $password === $passwordRepeat;
    }

    public function handleRegistration( array $request )
    {
        self::clearErrors();

        if( !$this->doPasswordsMatch( $request[ 'password' ], $request[ 'passwordRepeat' ] ) )
        {
            $this->errors[] = [
                'field'   => 'password',
                'message' => alias_cache::getAlias( 'passwords_no_match', 'validation' )
            ];
            $this->errors[] = [
                'field'   => 'passwordRepeat',
                'message' => alias_cache::getAlias( 'passwords_no_match', 'validation' )
            ];
            return false;
        }
        $p      = new processing( true );
        $result = $p->validateAndOutput( $this->registrationFieldsDefinition, false, $request, 'variables' );

        if( $p->hasErrors() )
        {
            $this->errors = array_merge( $this->errors, $result[ 'errors' ] );
            $this->storeErrors();
            return false;
        }

        $this->userData = $request;
        if( $this->createUnverifiedUser( $request ) )
        {
            $this->sendRegistrationEmail();
            return true;
        }
        return false;

    }

    /** TODO: rework to use bcrypt */
    protected function getSalt()
    {
        return '*(yh234Hiq4iut4buasdiofvq3h-9';
    }

    protected function getVerificationSalt()
    {
        return 'sdiofvq3h-9*(yh234Hiq4iut4bua';
    }

    protected function getResetSalt()
    {
        return 'ytous*4avbHu(4fqi-di3h94ih2q3';
    }

    protected function createVerificationToken( $input )
    {
        return md5( $this->getVerificationSalt() . time() . $input );
    }

    protected function createResetToken( $input )
    {
        return md5( $this->getResetSalt() . time() . $input );
    }

    public static function getByVerificationToken( $verificationToken )
    {
        if( !$verificationToken )
        {
            return null;
        }
        $params = [
            'where' => [
                't.emailVerificationToken IS NOT NULL',
                't.emailVerificationToken = "' . dbSE( $verificationToken ) . '"'
            ]
        ];
        return self::getObject( $params );
    }


    public static function getByPasswordResetToken( $passwordResetToken )
    {
        if( !$passwordResetToken )
        {
            return null;
        }

        $params = [
            'where' => [
                't.passwordResetToken IS NOT NULL',
                't.passwordResetToken = "' . dbSE( $passwordResetToken ) . '"'
            ]
        ];
        return self::getObject( $params );
    }

    protected function getHashedPassword( $input )
    {
        if( !mb_strlen( $input ) )
        {
            return '';
        }
        else
        {
            return md5( $this->getSalt() . $input );
        }
    }

    protected function createUnverifiedUser( $params )
    {
        $socPerson = getObject( 'socPerson' );

        $variables = [
            'name'        => $this->getName(),
            'email'       => $this->getEmail(),
            'profileLink' => $this->getProfileLink(),
            'imageUrl'    => $this->getProfileImage(),
            "status"      => socPerson::STATUS_UNVERIFIED
        ];


        $socPerson->variablesSave( $variables );

        if( $socPerson )
        {
            $variables = [
                'password'      => $this->getHashedPassword( $params[ 'password' ] ),
                'type'          => $this->providerType,
                'networkUserId' => $params[ 'email' ],
                "personId"      => $socPerson->id
            ];

            $auth = getObject( 'socAuth' );

            if( $auth->variablesSave( $variables, null, 'registerWithEmail' ) )
            {
                $this->registrationVerificationToken = socAuthEmailHelper::createVerificationToken( $auth->id, $params[ 'email' ] );
                return true;
            }
        }
        return false;
    }

    protected function getRegistrationVerificationUrl()
    {
        $template = objectTree::getFirstObject( 'auth/email_verification', null, false );
        return orp( $template ) . $this->registrationVerificationToken;
    }


    protected function getPasswordResetUrl()
    {
        $template = objectTree::getFirstObject( 'auth/password_change', null, false );
        return orp( $template ) . $this->passwordResetToken;
    }

    protected function sendRegistrationEmail()
    {
        $email = new userRegistrationEmail();

        $params = [
            'verificationUrl' => $this->getRegistrationVerificationUrl()
        ];

        $email->send( $this->getEmail(), $params );
    }

    public function handleAuthentication( array $request )
    {
        if( $this->isRegistrationRequest( $request ) )
        {
            return $this->handleRegistration( $request );
        }

        $password = get( $request, 'password' );
        $email    = get( $request, 'email' );
        if( !$password )
        {
            $this->errors[] = [
                'field'   => 'password',
                'message' => alias_cache::getAlias( 'empty_value', 'validation' )
            ];
        }

        if( !$email )
        {
            $this->errors[] = [
                'field'   => 'email',
                'message' => alias_cache::getAlias( 'empty_value', 'validation' )
            ];
        }

        if( $this->errors )
        {
            return false;
        }

        $params = [
            'where'     => [
                't.password = "' . $this->getHashedPassword( $password ) . '"',
                't.type = "' . $this->providerType . '"',
                't.networkUserId = "' . $email . '"',
                "s.status = " . socPerson::STATUS_VERIFIED
            ],
            'leftJoins' => [
                'soc_person s ON s.id = t.personId'
            ]
        ];

        /** @var socAuth $user */
        $user = socAuth::getObject( $params );

        if( $user )
        {
            $this->setUserData( $user->person );
            return true;
        }
        $this->errors[] = [
            'field'   => 'email',
            'message' => alias_cache::getAlias( 'account_does_not_exist_or_incorrect_password', 'validation' )
        ];
        return false;
    }

    public function getCredentials()
    {
        return null;
    }

    public function getProfileImage()
    {
        return null;
    }

    public function getProfileLink()
    {
        return null;
    }

    public function getAuthenticationTokenString()
    {
        return $this->userData[ "email" ];
    }

    public function getAccessToken()
    {
        return null;
    }

    public function getName()
    {
        return $this->userData[ "name" ];
    }

    public function getEmail()
    {
        return $this->userData[ "email" ];
    }


    public function createNewSocPerson( $joinAccounts = false )
    {
        /** @var socPerson $socPerson */
        dump( $this );
        $socPerson = getObject( 'socPerson' );
        $variables = [
            'name'        => $this->getName(),
            'email'       => $this->getEmail(),
            'profileLink' => $this->getProfileLink(),
            'imageUrl'    => $this->getProfileImage()
        ];
        $socPerson->variablesSave( $variables, null );

        if( $socPerson )
        {
            $variables = [
                'personId'      => $socPerson->id,
                'type'          => $this->providerType,
                'networkUserId' => $this->getAuthenticationTokenString()
            ];

            $this->variablesSave( $variables );

            $this->emailVerificationToken = null;
            $this->save();
            return $socPerson;
        }
        else
        {
            return false;
        }
    }

    public function sendResetEmail()
    {
        $emailObject = new userForgotPasswordEmail();

        $this->passwordResetToken = $this->createResetToken( $this->getEmail() );
        $this->save();

        $params = [
            'resetUrl' => $this->getPasswordResetUrl()
        ];
        $emailObject->send( $this->networkUserId, $params );
    }

    public function isVerified()
    {
        return !isset( $this->emailVerificationToken );
    }

    public function resetPassword( $request )
    {
        $password       = get( $request, 'password' );
        $passwordRepeat = get( $request, 'passwordRepeat' );

        if( !$this->doPasswordsMatch( $password, $passwordRepeat ) )
        {
            $this->errors[] = [
                'field'   => 'password',
                'message' => alias_cache::getAlias( 'passwords_no_match', 'validation' )
            ];
            $this->errors[] = [
                'field'   => 'passwordRepeat',
                'message' => alias_cache::getAlias( 'passwords_no_match', 'validation' )
            ];
            return false;
        }

        $p      = new processing( true );
        $result = $p->validateAndOutput( $this->passwordChangeFieldsDefinition, false, $request, 'variables' );
        if( $p->hasErrors() )
        {
            $this->errors = array_merge( $this->errors, $result[ 'errors' ] );
            return false;
        }
        else
        {
            $this->password           = $this->getHashedPassword( $password );
            $this->passwordResetToken = null;
            $this->save();
            return true;
        }
    }

    public static function validateUniqueEmail( $value )
    {
        $params = [
            'where'     => [
                't.type = "' . socAuth::AUTH_TYPE_EMAIL . '"',
                't.networkUserId ="' . dbSE( $value ) . '"',
                'h.emailVerificationToken is null'
            ],
            'leftJoins' => [
                'soc_authentication_helper h ON h.socAuthId = t.id'
            ]
        ];
        $auth   = socAuth::getObject( $params );
        if( $auth )
        {
            return [
                'errorCode' => 'email_already_registered'
            ];
        }
        return true;

    }

    public static function validatePasswordLength( $value )
    {
        if( mb_strlen( $value ) < 8 )
        {
            return [
                'errorCode' => 'password_too_short'
            ];
        }
        return true;
    }

    public function getReturnUrl()
    {
        return get( $_GET, 'returnUrl' );
    }

    public function setUserData( socPerson $person )
    {
        $this->userData = [
            "email" => $person->email,
            "name"  => $person->name
        ];
    }

    protected function storeErrors()
    {
        $_SESSION[ self::sessionName ][ "errors" ] = $this->errors;
    }

    public static function clearErrors()
    {
        if( isset( $_SESSION[ self::sessionName ] ) )
        {
            unset( $_SESSION[ self::sessionName ][ "errors" ] );
        }
    }

    public static function getStoredErrors()
    {
        return isset( $_SESSION[ self::sessionName ] ) ? get( $_SESSION[ self::sessionName ], "errors", [] ) : [];
    }

}
