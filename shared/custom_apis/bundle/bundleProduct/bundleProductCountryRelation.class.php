<?php

class bundleProductCountryRelation extends bundleBaseObject
{

    const tableName = 'bundleProductCountryRelations';

    protected
        $productId,
        $countryId,
        $price;
    protected
        $product,
        $country;
    protected $objectRelations = array(
        'country' => array(
            'key'    => 'countryId',
            'object' => 'bundleCountry'
        ),
        'product' => array(
            'key'    => 'productId',
            'object' => 'bundleProduct'
        )
    );
    protected $fieldsDefinition = array(
        'productId' => array(
            'type'      => 'id',
            'not_empty' => true
        ),
        'countryId' => array(
            'type'      => 'id',
            'not_empty' => true
        ),
        'price'     => array(
            'not_empty' => true
        )
    );
    protected static $_tableDefsStr = array(
        self::tableName => array(
            'fields'      => '
        id                int auto_increment
        productId        int not null
        countryId        int not null
        price             VARCHAR(255)
      ',
            'indexes'     => '
        primary         id
        index           productId
        index           countryId
      ',
            'foreignKeys' => '
        countryId      bundleCountries.id CASCADE CASCADE
        productId      bundleProducts.id CASCADE CASCADE
			',
            'engine'      => 'InnoDB'
        )
    );

    public static function saveCountries( $product_id, $data )
    {
        if ( ( empty( $product_id ) ) || ( !is_array( $data[ 'countries' ] ) ) )
        {
            return null;
        }

        foreach ( $data[ 'countries' ] as $row )
        {
            if ( isPositiveInt( $row[ 'id' ] ) && isset( $row[ 'price' ] ) )
            {
                $vars = array(
                    'productId' => $product_id,
                    'countryId' => $row[ 'id' ],
                    'price'     => $row[ 'price' ]
                );

                $obj = getObject( __CLASS__, 0 );
                $obj->variablesSave( $vars );
                unset( $obj );
            }
        }
    }

}