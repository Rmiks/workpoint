<?php

class socAuthEmailHelper extends bundleBaseObject
{
    /**
     * TODO: add status (available|consumed) and expires_at properties
     */

    const tableName = 'soc_authentication_helper';

    protected
        $socAuthId,
        $emailVerificationToken,
        $add_date,
        $author_ip;


    protected
        $fieldsDefinition = [
        'socAuthId'              => [ 'not_empty' => true ],
        'emailVerificationToken' => [ 'optional' => true ],
    ];

    protected static $_tableDefsStr = [
        self::tableName => [
            'fields'      =>
                '
				id			            int auto_increment
				socAuthId		        int
				emailVerificationToken  varchar(255)
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
				socAuthId    soc_authentication.id CASCADE CASCADE
			',
        ]
    ];

    /**
     * @var $socAuth
     */
    protected $socAuth;

    protected $objectRelations = [
        'socAuth' => [
            'key'    => 'socAuthId',
            'object' => 'socAuth'
        ]
    ];

    private static $verificationTokenSalt = 'sdiofvq3h-9*(yh234Hiq4iut4bua';

    public static function createVerificationToken( $authId, $email )
    {
        $params = [
            'socAuthId'              => $authId,
            'emailVerificationToken' => md5( self::$verificationTokenSalt . time() . $email )
        ];

        $object = getObject( __CLASS__ );
        $object->variablesSave( $params );
        return $params[ 'emailVerificationToken' ];
    }

    /**
     * @param string $verificationToken
     *
     * @return socAuth
     */
    public static function getAuthEntryByToken( $verificationToken )
    {
        return socAuth::getObject( [
            'leftJoins' => [
                self::tableName . ' h on h.socAuthId = t.id'
            ],
            'where'     => [
                'h.emailVerificationToken = "' . $verificationToken . '"'
            ]
        ] );
    }
}
