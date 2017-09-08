<?php

class socAuth extends bundleBaseObject
{
    const tableName               = 'soc_authentication';
    const AUTH_TYPE_FACEBOOK      = 'socAuthProviderFacebook';
    const AUTH_TYPE_EMAIL         = 'socAuthProviderEmail';
    const AUTH_TYPE_GOOGLE        = 'socAuthProviderGoogle';
    const AUTH_TYPE_TWITTER       = 'socAuthProviderTwitter';
    const AUTH_TYPE_DRAUGIEM      = 'socAuthProviderDraugiem';
    const AUTH_TYPE_VKONTAKTE     = 'socAuthProviderVkontakte';
    const AUTH_TYPE_ODNOKLASSNIKI = 'socAuthProviderOdnoklassniki';
    const AUTH_TYPE_LINKEDIN      = 'socAuthProviderLinkedin';

    /**
     * Current login provider to handle authentication.
     *
     * @var null|socAuthProvider
     */
    protected $authProvider = null;

    protected $modes = [
        'updateTokens'      => [
            'storedAccessToken'
        ],
        'registerWithEmail' => [
            'networkUserId', 'type', 'password', "personId"
        ]
    ];

    protected
        $personId,
        $networkUserId,
        $storedAccessToken,
        $password,
        $type,
        $add_date,
        $author_ip;


    protected
        $fieldsDefinition = [
        'personId'          => [ 'not_empty' => true ],
        'networkUserId'     => [ 'not_empty' => true ],
        'storedAccessToken' => [ 'optional' => true ],
        'password'          => [ 'optional' => true ],
        'type'              => [ 'not_empty' => true ]
    ];

    protected static $_tableDefsStr = [
        self::tableName => [
            'fields'      =>
                '
				id			            int auto_increment
				personId		        int
				networkUserId           varchar(255)
				storedAccessToken       varchar(255)
				password                varchar(255)
				type                    enum(<types>)
				add_date		        datetime
				author_ip	            varchar(255)
			',
            'indexes'     =>
                '
				primary     id		
			',
            'engine'      => 'InnoDB'
            ,
            'foreignKeys' =>
                '
				personId    soc_person.id CASCADE CASCADE
			',
        ]
    ];

    /**
     * @var socPerson
     */
    protected $person;

    protected $objectRelations = [
        'person' => [
            'key'    => 'personId',
            'object' => 'socPerson'
        ]
    ];


    public static function _autoload( $className )
    {
        parent::_autoload( $className );
        self::updateTypeEnum();
    }

    public static function updateTypeEnum()
    {
        $typeString                                         = '"' . implode( '","', socAuth::listAuthProviderTypes() ) . '"';
        self::$_tableDefsStr[ self::tableName ][ 'fields' ] = str_replace( '<types>', $typeString, self::$_tableDefsStr[ self::tableName ][ 'fields' ] );
        dbRegisterRawTableDefs( self::$_tableDefsStr );
    }


    /**
     * Returns available login provider type list.
     *
     * @return array
     */
    public static function listAuthProviderTypes()
    {
        $configuredProviders = [];
        $authConfig          = leaf_get_property( [ 'authentication' ], false );
        if( is_array( $authConfig ) )
        {
            $configuredProviders = array_keys( $authConfig );
        }
        return $configuredProviders;
    }

    /**
     * Factories login provider by type.
     *
     * @param string $providerType One of socLoginBase::LOGIN_TYPE_* constants
     *
     * @throws UnexpectedValueException
     * @return socAuthProviderFacebook
     */
    public static function factoryAuthProvider( $providerType )
    {
        if( !in_array( $providerType, self::listAuthProviderTypes() ) )
        {
            throw new UnexpectedValueException( 'Non existent login provider type: [' . $providerType . '].' );
        }

        return new $providerType();
    }

    /**
     * @return SiteServices_Authentication|xml_template
     */
    public static function getHandlerTemplate()
    {
        return objectTree::getFirstChild( 0, 'siteServices/authentication' );
    }


    /**
     * Mutator for the auth provider member.
     *
     * @param socAuthProvider $socAuthProvider
     */
    public function setAuthProvider( socAuthProvider $socAuthProvider )
    {
        $this->authProvider = $socAuthProvider;
    }

    /**
     * Accessor for the auth provider member.
     *
     * @throws BadMethodCallException
     * @return socAuthProvider
     */
    public function getAuthProvider()
    {
        if( is_null( $this->authProvider ) )
        {
            throw new BadMethodCallException( 'Not bound with auth provider.' );
        }
        return $this->authProvider;
    }


    /**
     * Create a new socPerson entry from soc network user credentials
     *
     * @return socPerson
     */
    public function createNewSocPerson()
    {
        $socPerson = getObject( 'socPerson' );
        $variables = [
            'name'        => $this->authProvider->getName(),
            'email'       => $this->authProvider->getEmail(),
            'profileLink' => $this->authProvider->getProfileLink(),
            'imageUrl'    => $this->authProvider->getProfileImage(),
            "status"      => socPerson::STATUS_VERIFIED
        ];
        $socPerson->variablesSave( $variables );
        if( $socPerson )
        {
            $variables = [
                'storedAccessToken' => $this->authProvider->getStorableAccessToken(),
                'personId'          => $socPerson->id,
                'networkUserId'     => $this->authProvider->getAuthenticationTokenString(),
                'type'              => $this->authProvider->getProviderType()
            ];
            $this->variablesSave( $variables );
        }

        return $socPerson;
    }

    public function updateTokens()
    {
        $variables = [
            'storedAccessToken' => $this->authProvider->getStorableAccessToken()
        ];

        $this->variablesSave( $variables, null, 'updateTokens' );
    }

    /**
     * Set a socPerson as a verified user
     * (typically if @see socAuthProviderEmail is used, but can be used for other adapters)
     *
     * @return socPerson
     */
    public function completeVerification()
    {
        $variables = [
            "status" => socPerson::STATUS_VERIFIED
        ];

        $this->person->variablesSave( $variables, null, "updateStatus" );
        return $this->person;
    }

    /**
     * @param string $returnUrl
     *
     * @return array
     */
    public static function getAvailableAuthUrls( $returnUrl )
    {
        $socAuth = self::getHandlerTemplate();

        $extraParams = [
            'emailLoginFormUrl' => site::getTemplateUrl( "auth/email_login" )
        ];

        return $socAuth->getAvailableHandlerURLs( $returnUrl, $extraParams );
    }
}
