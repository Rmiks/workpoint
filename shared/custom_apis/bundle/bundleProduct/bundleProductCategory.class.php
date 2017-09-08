<?php

class bundleProductCategory extends bundleBaseObject
{

    const tableName = 'bundleCategories';

    protected
        $label,
        $imageId,
        $add_date,
        $author_ip
    ;

    protected static $i18nProperties = array(
        'name'       => array(
            'optional'  => true,
            'fieldType' => 'varchar(255)'
        ),
        'description' => array(
            'optional'  => true,
            'fieldType' => 'text'
        )
    );

    protected $fieldsDefinition = array(
        'label'    => array(
            'not_empty' => true
        ),
        'imageId' => array(
            'optional' => true,
            'type'     => 'file'
        ),
    );

    protected
        $image,
        $objectRelations = array(
        'image' => array(
            'key'    => 'imageId',
            'object' => 'leafFile'
        ),
    );

    protected static $imageSizes = array(
        'default' => 'subnav',
        'subnav'  => array(
            'width'          => 160,
            'height'         => 125,
            'crop'           => true,
            'corners'        => 0,
            'grayscale'      => false,
            'forceExactSize' => true,
            'optimize'       => true,
            'default'        => '/images/default-category--subnav.png',
        ),
        'large'   => array(
            'width'          => 320,
            'height'         => 250,
            'crop'           => true,
            'corners'        => 0,
            'grayscale'      => false,
            'forceExactSize' => true,
            'optimize'       => true,
            'default'        => '/images/default-category--large.png',
        ),
    );

    protected static $_tableDefsStr = array(
        self:: tableName => array(
            'fields'  =>
                'id             int auto_increment
                label                  varchar(255)
                imageId                INT
                add_date        datetime
                author_ip       varchar(255)
                '
            ,
            'indexes' => '
            primary id
            ',
            'engine'  => 'InnoDB'
        )
    );

}
