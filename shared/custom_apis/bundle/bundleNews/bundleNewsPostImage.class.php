<?php

class bundleNewsPostImage extends bundleBaseObject
{
    const tableName = 'bundleNewsPostsImages';

    protected
        $postId,
        $fileId,
        $sort,
        $add_date,
        $author_ip;

    protected $fieldsDefinition = [
        'fileId' => [
            'optional'  => true,
            'type'      => 'file'
        ],
        'postId' => [
            'not_empty' => true
        ],
        'sort'   => [
            'optional'  => true
        ]
    ];

    protected
        $file,
        $post;

    protected $objectRelations = [
        'file' => [
            'key'    => 'fileId',
            'object' => 'leafFile'
        ],
        'post' => [
            'key'    => 'postId',
            'object' => 'bundleNewsPost'
        ]
    ];

    protected static $_tableDefsStr = [
        self::tableName => [
            'fields'      =>
                '
				id			int auto_increment
				postId      int
				fileId 		int
                sort        int
				add_date	datetime
				author_ip	varchar(15)
				'
            ,
            'indexes'     =>
                '
				primary     id
                index       postId
				'
            ,
            'foreignKeys' =>
                '
                postId       bundleNewsPosts.id CASCADE CASCADE
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

    public static function saveImages ( $images, $postId ) {
        $sort = self::getNewSortNumber( $postId );
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
                    'postId' => $postId,
                    'fileId_file' => $file,
                    'sort' => $sort++
                ];

                $newImage = getObject( 'bundleNewsPostImage' );
                $newImage->variablesSave( $variables );
            }

        }
    }

    public static function getNewSortNumber ( $postId ) {
        $post = getObject( 'bundleNewsPost', $postId );
        $images = $post->__get( 'images' );
        $count = count( $images );
        return ( $count+1 );
    }

    public static function updateSort ( $imageIds ) {
        $count = count( $imageIds );

        for ( $i = 0; $i < $count; $i++ ) {
            $image = getObject( 'bundleNewsPostImage', $imageIds[ $i ] );
            $image->sort = $i+1;
            $image->save();
        }
    }
}