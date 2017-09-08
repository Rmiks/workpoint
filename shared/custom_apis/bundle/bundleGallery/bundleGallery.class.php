<?php

class bundleGallery extends bundleBaseObject
{
    const
        tableName = 'bundleGalleries',
        itemsPerPage = 10;

    protected
        $name,
        $date,
        $text,
        $slug,
        $imageId,
        $active,
        $metaDescription,
        $add_date,
        $author_ip;


    protected $fieldsDefinition = [
        'name'    => [
            'optional' => true
        ],
        'slug'    => [
            'optional' => true
        ],
        'date'    => [
            'optional' => true
        ],
        'imageId' => [
            'optional' => true,
            'type'     => 'file'
        ],
        'text'    => [
            'optional' => true
        ],
        'active'  => [
            'optional'   => true,
            'input_type' => 'checkbox',
            'type'       => 'int'
        ],
        'metaDescription'  => [
            'optional'   => true,
        ]
    ];

    protected
        $image,
        $images;

    protected $objectRelations = [
        'image'  => [
            'key'    => 'imageId',
            'object' => 'leafFile'
        ],
        'images' => [
            'objectKey'  => 'galleryId',
            'object'     => 'bundleGalleryImage',
            'orderField' => 'sort',
            'order'      => 'asc'
        ]
    ];

    protected static $_tableDefsStr = [
        self:: tableName => [
            'fields'  =>
                '
                id              int auto_increment
                name            varchar(255)
                slug            varchar(255)
                date            date
                text            text
                imageId         int
                active          tinyint
                metaDescription text
                add_date        datetime
                author_ip       varchar(255)
                '
            ,
            'indexes' => 'primary id',
            'engine'  => 'InnoDB'
        ]
    ];

    protected static $imageSizes = [
        'thumb' => [
            'width'    => 100,
            'height'   => 100,
            'crop'     => false,
            'optimize' => true
        ]
    ];

    public function variablesSave( $variables, $fieldsDefinition = null, $mode = false )
    {
        //slugify
        $key               = 'slug';
        $variables[ $key ] = empty( $variables[ $key ] ) ? $this->slugify( $variables[ 'name' ] ) : $variables[ $key ];

        // save
        $return = parent::variablesSave( $variables, $fieldsDefinition, $mode );

        // update sort for existing images
        if( !empty( $variables[ 'image-id' ] ) )
        {
            bundleGalleryImage::updateSort( $variables[ 'image-id' ] );
        }

        // save new images
        if( !empty( $variables[ 'images' ][ 'name' ][ 0 ] ) )
        {
            bundleGalleryImage::saveImages( $variables[ 'images' ], $this->id );
        }

        return $return;
    }

    public static function getPosts( $page = 1 )
    {
        $params = [
            'where'   => [
                't.date <= "' . date( 'Y-m-d' ) . '"',
                't.active = 1'
            ],
            'orderBy' => 't.date DESC'
        ];

        return self::getCollection( $params, self::itemsPerPage, $page );
    }

    public static function getByUrl( $slug = null )
    {
        if( empty( $slug ) )
        {
            return false;
        }

        $params = [
            'where' => [
                't.date <= "' . date( 'Y-m-d' ) . '"',
                't.slug = "' . dbSE( $slug ) . '"',
                't.active = 1',
            ],
            'limit' => 1
        ];

        return self::getCollection( $params )->first();
    }

    public function getRelatedPosts()
    {
        $params = [
            'where' => [
                't.date <= "' . date( 'Y-m-d' ) . '"',
                't.active = 1',
                't.id != ' . $this->id
            ]
        ];

        return self::getCollection( $params, 3 );
    }

    public function getUrl()
    {
        $galleryObject = objectTree::getFirstObject( 'galleries/section' );
        return $galleryObject ? orp( $galleryObject ) . $this->slug : WWW . $this->slug;
    }
}