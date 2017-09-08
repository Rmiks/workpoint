<?php

class bundleGalleryImage extends bundleBaseObject
{
    const tableName = 'bundleGalleryImages';

    protected
        $galleryId,
        $fileId,
        $sort,
        $add_date,
        $author_ip;

    protected $fieldsDefinition = [
        'fileId'    => [
            'optional'  => true,
            'type'      => 'file'
        ],
        'galleryId' => [
            'not_empty' => true
        ],
        'sort'      => [
            'optional'  => true
        ]
    ];

    protected
        $file,
        $gallery;

    protected $objectRelations = [
        'file'    => [
            'key'    => 'fileId',
            'object' => 'leafFile'
        ],
        'gallery' => [
            'key'    => 'galleryId',
            'object' => 'bundleGallery'
        ]
    ];

    protected static $_tableDefsStr = [
        self::tableName => [
            'fields'      =>
                '
                id          int auto_increment
                galleryId   int
                fileId      int
                sort        int
                add_date    datetime
                author_ip   varchar(15)
                '
            ,
            'indexes'     =>
                '
                primary     id
                index       galleryId
                '
            ,
            'foreignKeys' =>
                '
                galleryId   bundleGalleries.id CASCADE CASCADE
                '
            ,
            'engine'      => 'InnoDB',
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

    public static function saveImages ( $images, $galleryId ) {
        $sort = self::getNewSortNumber( $galleryId );
        $count = count( $images[ 'name' ] );
        $allowedTypes = [
            'image/png',
            'image/jpeg',
            'image/jpg',
            'image/gif'
        ];

        for ( $i = 0; $i < $count; $i++ ) {

            if ( in_array( $images[ 'type' ][ $i ], $allowedTypes ) ) {

                $file = [
                    'name'     => $images[ 'name' ][ $i ],
                    'type'     => $images[ 'type' ][ $i ],
                    'tmp_name' => $images[ 'tmp_name' ][ $i ],
                    'error'    => $images[ 'error' ][ $i ],
                    'size'     => $images[ 'size' ][ $i ]
                ];

                $variables = [
                    'galleryId' => $galleryId,
                    'fileId_file' => $file,
                    'sort' => $sort++
                ];

                $newImage = getObject( 'bundleGalleryImage' );
                $newImage->variablesSave( $variables );
            }

        }
    }

    public static function getNewSortNumber ( $galleryId ) {
        $estate = getObject( 'bundleGallery', $galleryId );
        $images = $estate->__get( 'images' );
        $count = count( $images );
        return ( $count+1 );
    }

    public static function updateSort ( $imageIds ) {
        $count = count( $imageIds );

        for ( $i = 0; $i < $count; $i++ ) {
            $image = getObject( 'bundleGalleryImage', $imageIds[ $i ] );
            $image->sort = $i+1;
            $image->save();
        }
    }

}