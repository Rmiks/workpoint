<?php

class socPerson extends bundleBaseObject
{

    const tableName = 'soc_person';

    const STATUS_VERIFIED   = 1;
    const STATUS_UNVERIFIED = 2;
    const STATUS_BLOCKED    = 3; // etc

    protected static $sessionName = 'social_user';
    
    protected $modes = [
        "updateStatus" => [
            "status"
        ]
    ];

    protected
        $name,
        $email,
        $phone,
        $imageUrl,
        $profileLink,
        $emailVerificationToken,
        $status,
        $add_date,
        $author_ip;

    protected $fieldsDefinition = [
        'name'                   => [
            'optional' => true
        ],
        'email'                  => [
            'optional' => true,
            'type'     => 'email'
        ],
        'phone'                  => [
            'optional'         => true,
            'validateFunction' => [
                __CLASS__,
                'validatePhoneLength'
            ]
        ],
        'imageUrl'               => [
            'optional' => true
        ],
        'profileLink'            => [
            'optional' => true
        ],
        'emailVerificationToken' => [
            'optional' => true
        ],
        "status"                 => [
            "optional" => true
        ]
    ];

    protected static $_tableDefsStr = [
        self::tableName => [
            'fields'  =>
                '
                id                      int auto_increment
                name			        varchar(255)
                email			        varchar(255)
                phone			        varchar(255)
				imageUrl		        varchar(500)
				profileLink		        varchar(255)
				emailVerificationToken  varchar(255)
				status                  tinyint
                add_date		        datetime
                author_ip		        varchar(63)
            '
            ,
            'engine'  => 'InnoDB'
            ,
            'indexes' => '
                primary id
            '
        ]
    ];

    public function getDefinitions()
    {
        return parent::getDefinitions();
    }

    public static function validatePhoneLength( $value )
    {
        if( mb_strlen( $value ) > 16 )
        {
            return [
                'errorCode' => 'phone_too_long'
            ];
        }
        if( mb_strlen( $value ) < 8 )
        {
            return [
                'errorCode' => 'phone_too_short'
            ];
        }
        return true;
    }

    /**
     * @return socPerson
     */
    public static function getLoggedInUser()
    {
        if( empty( $_SESSION[ self::$sessionName ] ) )
        {
            return null;
        }

        $user = $_SESSION[ self::$sessionName ];

        $userObject = getObject( get_called_class(), $user->id );
        return $userObject;
    }

    public function logInstanceIn()
    {
        $_SESSION[ self::$sessionName ] = $this;
    }

    public static function logout()
    {
        unset( $_SESSION[ self::$sessionName ] );
    }

    public function getAdminUrl()
    {
        require_once( ADMIN_PATH . 'modules/leafBaseModule/module.php' );
        require_once( ADMIN_PATH . 'modules/bundleBaseModule/module.php' );
        require_once( ADMIN_PATH . 'modules/socPersons/module.php' );

        $params = [
            'id' => $this->id,
            'do' => 'view'
        ];

        /** @var socPersons $personModule */
        $personModule = getObject( 'socPersons' );
        return $personModule->getModuleUrl() . '&' . http_build_query( $params );
    }

}