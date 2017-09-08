<?
//core table definitions
$tableDefs = [
    'xml_templates_list'    => [
        'name'   => 'xml_templates_list',
        'fields' => [
            [
                'name' => 'template_path',
                'type' => 'VARCHAR(255)'
            ],
            [
                'name' => 'name',
                'type' => 'VARCHAR(255)'
            ],
            [
                'name' => 'icon_path',
                'type' => 'VARCHAR(255)'
            ],
            [
                'name' => 'table',
                'type' => 'VARCHAR(255)'
            ],
            [
                'name' => 'fields_index',
                'type' => 'TEXT'
            ],
            [
                'name' => 'alias',
                'type' => 'VARCHAR(255)'
            ],
            [
                'name' => 'lastModifiedMethod',
                'type' => 'VARCHAR(255)',
            ],
        ],
        'keys'   => [
            'PRIMARY' => [
                'type'   => 'PRIMARY',
                'name'   => 'PRIMARY',
                'fields' => [
                    [
                        'name' => 'template_path',
                    ]
                ]
            ],
        ]
    ],
    'object_rules'          => [
        'name'   => 'object_rules',
        'fields' => [
            [
                'name' => 'object',
                'type' => 'VARCHAR(255)'
            ],
            [
                'name' => 'child',
                'type' => 'VARCHAR(255)'
            ],
            [
                'name' => 'max',
                'type' => 'TINYINT',
            ],
        ],
        'keys'   => [
            'object' => [
                'type'   => 'INDEX',
                'name'   => 'object',
                'fields' => [
                    [
                        'name'   => 'object',
                        'length' => 128
                    ],
                    [
                        'name'   => 'child',
                        'length' => 128
                    ]
                ]
            ],
        ]
    ],
    'system_values'         => [
        'name'   => 'system_values',
        'fields' => [
            [
                'name' => 'name',
                'type' => 'VARCHAR(255)'
            ],
            [
                'name' => 'value',
                'type' => 'TEXT'
            ],
            [
                'name' => 'modified',
                'type' => 'TIMESTAMP'
            ],
        ],
        'keys'   => [
            'PRIMARY' => [
                'type'   => 'PRIMARY',
                'name'   => 'PRIMARY',
                'fields' => [
                    [
                        'name' => 'name',
                    ]
                ]
            ],
        ]
    ],
    'image_text_info'       => [
        'name'   => 'image_text_info',
        'fields' => [
            [
                'name' => 'filename',
                'type' => 'VARCHAR(255)'
            ],
            [
                'name' => 'width',
                'type' => 'INT(11)'
            ],
            [
                'name' => 'height',
                'type' => 'INT(11)'
            ],
            [
                'name' => 'type',
                'type' => 'VARCHAR(255)'
            ],
            [
                'name' => 'created',
                'type' => 'DATETIME'
            ],
        ],
        'keys'   => [
            'PRIMARY' => [
                'type'   => 'PRIMARY',
                'name'   => 'PRIMARY',
                'fields' => [
                    [
                        'name' => 'filename',
                    ]
                ]
            ]
        ]
    ],
    'objects_rewrite_cache' => [
        'name'   => 'objects_rewrite_cache',
        'fields' => [
            [
                'name' => 'object_id',
                'type' => 'INT(11)'
            ],
            [
                'name' => 'url',
                'type' => 'TEXT'
            ],
            [
                'name' => 'file_name',
                'type' => 'VARCHAR(255)'
            ],
        ],
        'keys'   => [
            'PRIMARY' => [
                'type'   => 'PRIMARY',
                'name'   => 'PRIMARY',
                'fields' => [
                    [
                        'name' => 'object_id',
                    ]
                ]
            ],
        ]
    ],
    'objects_cache'         => [
        'name'   => 'objects_cache',
        'fields' => [
            [
                'name' => 'id',
                'type' => 'VARCHAR(32)'
            ],
            [
                'name' => 'data',
                'type' => 'LONGTEXT'
            ],
            [
                'name' => 'timestamp',
                'type' => 'DATETIME'
            ],
        ],
        'keys'   => [
            'id' => [
                'type'   => 'UNIQUE',
                'name'   => 'id',
                'fields' => [
                    [
                        'name' => 'id',
                    ]
                ]
            ],
        ]
    ],

    'objects_url_history' => [
        'name'   => 'objects_url_history',
        'fields' => [
            [
                'name' => 'path',
                'type' => 'VARCHAR(255)'
            ],
            [
                'name' => 'object_id',
                'type' => 'INT(11)'
            ],
            [
                'name' => 'params',
                'type' => 'longtext'
            ],
        ],
        'keys'   => [
            'PRIMARY' => [
                'type'   => 'PRIMARY',
                'name'   => 'PRIMARY',
                'fields' => [
                    [
                        'name' => 'path',
                    ]
                ]
            ],
        ]
    ],

    'bannerCache'  => [
        'name'   => 'bannerCache',
        'fields' => [
            [
                'name' => 'cacheKey',
                'type' => 'CHAR(40)'
            ],
            [
                'name' => 'cacheDate',
                'type' => 'DATETIME'
            ],
            [
                'name' => 'html',
                'type' => 'LONGTEXT'
            ],
        ],
        'keys'   => [
            'cacheKey'  => [
                'type'   => 'PRIMARY',
                'name'   => 'cacheKey',
                'fields' => [
                    [
                        'name' => 'cacheKey',
                    ],
                ]
            ],
            'cacheDate' => [
                'type'   => 'INDEX',
                'name'   => 'cacheDate',
                'fields' => [
                    [
                        'name' => 'cacheDate',
                    ]
                ]
            ],
        ]
    ],
    'embedObjects' => [
        'name'   => 'embedObjects',
        'fields' => [
            [
                'name'           => 'id',
                'type'           => 'INT(11)',
                'auto_increment' => true
            ],
            [
                'name' => 'add_date',
                'type' => 'DATETIME'
            ],
            [
                'name' => 'embedCode',
                'type' => 'TEXT',
            ],
            [
                'name' => 'objectId',
                'type' => 'INT(11)',
            ],
            [
                'name'    => 'source',
                'type'    => 'VARCHAR(255)',
                'default' => 'embedCode'
            ],

        ],
        'keys'   => [
            'id' => [
                'type'   => 'PRIMARY',
                'name'   => 'id',
                'fields' => [
                    [
                        'name' => 'id',
                    ],
                ]
            ],
        ]
    ],


    'objects' => [
        'name'   => 'objects',
        'fields' => [
            [
                'name'           => 'id',
                'type'           => 'INT(11)',
                'auto_increment' => true
            ],
            [
                'name' => 'name',
                'type' => 'VARCHAR(255)',
            ],
            [
                'name' => 'type',
                'type' => 'TINYINT',
            ],
            [
                'name' => 'parent_id',
                'type' => 'INT',
            ],
            [
                'name'    => 'create_date',
                'type'    => 'DATETIME',
                'default' => '0000-00-00 00:00:00'
            ],
            [
                'name'    => 'last_edit',
                'type'    => 'DATETIME',
                'default' => '0000-00-00 00:00:00'
            ],
            [
                'name'    => 'createdby',
                'type'    => 'INT',
                'default' => '0'
            ],
            [
                'name'    => 'visible',
                'type'    => 'TINYINT',
                'default' => '1'
            ],
            [
                'name'    => 'protected',
                'type'    => 'TINYINT',
                'default' => '0',
            ],
            [
                'name'    => 'order_nr',
                'type'    => 'INT',
                'default' => '0'
            ],
            [
                'name' => 'rewrite_name',
                'type' => 'VARCHAR(255)',
            ],
            [
                'name' => 'template',
                'type' => 'VARCHAR(255)',
            ],
            [
                'name' => 'data',
                'type' => 'LONGTEXT',
            ],
        ],
        'keys'   => [
            'PRIMARY' => [
                'type'   => 'PRIMARY',
                'name'   => 'PRIMARY',
                'fields' => [
                    [
                        'name' => 'id',
                    ],
                ]
            ],

            'create_date' => [
                'type'   => 'INDEX',
                'name'   => 'create_date',
                'fields' => [
                    [
                        'name' => 'create_date'
                    ]
                ]
            ],

            'order_nr' => [
                'type'   => 'INDEX',
                'name'   => 'order_nr',
                'fields' => [
                    [
                        'name' => 'order_nr'
                    ]
                ]
            ],

            'visible' => [
                'type'   => 'INDEX',
                'name'   => 'visible',
                'fields' => [
                    [
                        'name' => 'visible'
                    ]
                ]
            ],

            'last_edit' => [
                'type'   => 'INDEX',
                'name'   => 'last_edit',
                'fields' => [
                    [
                        'name' => 'last_edit'
                    ]
                ]
            ],


            'template' => [
                'type'   => 'INDEX',
                'name'   => 'template',
                'fields' => [
                    [
                        'name' => 'template'
                    ]
                ]
            ],


            'parent_type' => [
                'type'   => 'INDEX',
                'name'   => 'parent_type',
                'fields' => [
                    [
                        'name' => 'parent_id'
                    ],
                    [
                        'name' => 'type'
                    ]
                ]
            ],


        ]
    ],


    'translations' => [
        'name'   => 'translations',
        'fields' => [
            [
                'name'           => 'id',
                'type'           => 'INT(11)',
                'auto_increment' => true
            ],
            [
                'name' => 'group_id',
                'type' => 'INT(11)'
            ],
            [
                'name' => 'name',
                'type' => 'VARCHAR(100)'
            ],
            [
                'name' => 'type',
                'type' => 'TINYINT(1)',
            ],
        ],

        'keys' => [
            'id'       => [
                'type'   => 'PRIMARY',
                'name'   => 'id',
                'fields' => [
                    [
                        'name' => 'id',
                    ],
                ]
            ],
            'name'     => [
                'type'   => 'index',
                'name'   => 'name',
                'fields' => [
                    [
                        'name' => 'name',
                    ],
                ]
            ],
            'group_id' => [
                'type'   => 'INDEX',
                'name'   => 'group_id',
                'fields' => [
                    [
                        'name' => 'group_id'
                    ]
                ]
            ],
        ],
    ],


    'translations_groups' => [
        'name'   => 'translations_groups',
        'fields' => [
            [
                'name'           => 'id',
                'type'           => 'INT(11)',
                'auto_increment' => true
            ],
            [
                'name' => 'name',
                'type' => 'VARCHAR(255)'
            ],

        ],

        'keys' => [
            'id' => [
                'type'   => 'PRIMARY',
                'name'   => 'id',
                'fields' => [
                    [
                        'name' => 'id',
                    ],
                ]
            ],
        ],
    ],


    'translations_data' => [
        'name'   => 'translations_data',
        'fields' => [
            [
                'name' => 'translation_id',
                'type' => 'INT(11)'
            ],
            [
                'name' => 'language_id',
                'type' => 'SMALLINT(6)'
            ],
            [
                'name' => 'translation',
                'type' => 'TEXT',
            ],
            [
                'name' => 'machineTranslated',
                'type' => 'TINYINT(1)',
            ],
        ],

        'keys' => [
            'id' => [
                'type'   => 'PRIMARY',
                'name'   => 'id',
                'fields' => [
                    [
                        'name' => 'translation_id',
                    ],
                    [
                        'name' => 'language_id',
                    ],
                ]
            ],
        ],
    ],


    'object_ancestors' => [
        'name'   => 'object_ancestors',
        'fields' => [
            [
                'name' => 'object_id',
                'type' => 'INT(11)',
            ],
            [
                'name' => 'ancestor_id',
                'type' => 'INT(11)',
            ],
            [
                'name'    => 'level',
                'type'    => 'INT(11)',
                'default' => '0'
            ],
        ],

        'keys' => [
            'id' => [
                'type'   => 'PRIMARY',
                'name'   => 'id',
                'fields' => [
                    [
                        'name' => 'object_id',
                    ],
                    [
                        'name' => 'ancestor_id',
                    ],
                ]
            ],
        ],
    ],

    'files' => [
        'name'   => 'files',
        'fields' => [
            [
                'name' => 'object_id',
                'type' => 'INT(11)',
            ],
            [
                'name' => 'file_name',
                'type' => 'VARCHAR(255)',
            ],
            [
                'name' => 'extra_info',
                'type' => 'TEXT',
            ],
            [
                'name' => 'original_name',
                'type' => 'VARCHAR(255)',
            ],
            [
                'name' => 'extension',
                'type' => 'VARCHAR(12)',
            ],
            [
                'name' => 'secure',
                'type' => 'TINYINT(1)',
            ],
            [
                'name' => 'optimized',
                'type' => 'DATETIME'
            ]
        ],

        'keys' => [
            'object_id' => [
                'type'   => 'unique',
                'name'   => 'object_id',
                'fields' => [
                    [
                        'name' => 'object_id',
                    ],
                ]
            ],
        ],
    ],

];
