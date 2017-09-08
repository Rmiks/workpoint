<?
require_once '../shared/config.php';
## enable error display for admin side
ini_set( 'display_errors', 1 );
## site define variables
$def_config[ 'PATH' ]         = $def_config[ 'ADMIN_PATH' ];
$def_config[ 'WWW' ]          = $def_config[ 'ADMIN_WWW' ];
$def_config[ 'SESSION_NAME' ] = md5( $_SERVER[ 'HTTP_HOST' ] );
## db info
$config[ 'modules' ] = [
    'leaf' => [
        'log'    => true,
        'main'   => true,
        'load'   => true,
        'return' => true,
    ],
];

$config[ 'flash' ] = [
    'defaultFlashVersion'     => 9,
    'defaultSwfobjectVersion' => 2
];

$config[ 'leafAuthorization' ] = [
    'userClass'  => 'leafUser',
    'groupClass' => 'leafUserGroup'
];

$config[ 'objectModules' ] = [ 'objectAccess' ];

//hidden admin panel permission modules
//group - hidden module array
//hiding base modules will prevent user from blocking himself out
$config[ 'hideGroupModules' ]              = [];
$config[ 'hideGroupModules' ][ 'clients' ] = [
    'leafBaseModule',
    'automators',
    'bundleBaseModule',
    'profile'
];

$config[ 'leafAdminAbilityClass' ] = 'leafAdminAbility';

// main menu config
$config[ 'leafMenuConfig' ] = [
    'content',
    'leafBaseModule',
    'errors',
    'aliases',
    'adminLog'
];

// example config
$config[ 'leafBaseModuleConfig' ] = [
    'menu' => [
        // main menu
        'settings' => [
            // submenu sections
            'users' => [ 'users', 'userGroups' ]
        ],
        'modules'  => [
            'bundleNews'      => [ 'bundleNews' ],
            'bundleGalleries' => [ 'bundleGalleries' ],
            'bundleProducts'  => [ 'bundleProducts', 'bundleProductCategories', 'bundleCountries' ],
            "tests"           => [ tests::class ]
        ],
        'emails'   => [
            'emails' => [
                'emailHeader',
                'userRegistrationEmail' => [
                    'url'        => '?module=emails&email=userRegistrationEmail',
                    'moduleName' => 'emails'
                ]
            ]
        ]

        /*
		// main menu
		'projectSpecific' => array
		(
			// submenu sections
			'emails' => array
			(
				'emailHeader',
				'exampleEmail' => array
				(
					'url' 		 => '?module=emails&email=leafExampleEmail',
					'moduleName' => 'emails',
				),
                'errorReports' 	  => array
                (
                    'url' 		 => '?module=errorReports',
                    'moduleName' => 'errorReports',
                    'badgeCallback'      => function()
                    {
                        $unresolvedErrors = errorReport::getUnresolvedForCurrentUser();
                        if($unresolvedErrors)
                        {
                            return '<span class="unresolvedErrorsBadge">' . $unresolvedErrors . '</span>';
                        }
                    }
                ),
			),
        ),
     */
    ],
];
