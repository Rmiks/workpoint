<?php

/**
 * Class bundleProduct
 */

class bundleProduct extends bundleBaseObject
{

    const tableName = 'bundleProducts';

    protected
        $label,
        $outofstock,
        $categoryId,
        $metaDescription,
        $add_date,
        $author_ip,
        $imageId;

    protected static $i18nProperties = array(
        'name'        => array(
            'optional'  => true,
            'fieldType' => 'varchar(255)'
        ),
        'description' => array(
            'optional'  => true,
            'fieldType' => 'text'
        ),
        'metaDescription' => array(
            'optional'  => true,
            'fieldType' => 'text'
        )
    );

    protected
        $category,
        $image,
        $countries;

    protected $objectRelations = array(
        'category'  => array(
            'key'    => 'categoryId',
            'object' => 'bundleProductCategory'
        ),
        'image'     => array(
            'key'    => 'imageId',
            'object' => 'leafFile'
        ),
        'countries' => array(
            'objectKey' => 'productId',
            'object'    => 'bundleProductCountryRelation'
        )
    );

    protected $fieldsDefinition = array(
        'label'      => array(
            'not_empty' => true
        ),
        'outofstock' => array(
            'optional' => true,
            'input_type' => 'checkbox',
            'type' => 'int',
        ),
        'categoryId' => array(
            'type'     => 'id',
            'optional' => true
        ),
        'imageId'    => array(
            'type'     => 'file',
            'optional' => true
        ),
        'countries'  => array(
            'optional' => true,
            'saveWith' => 'saveCountries'
        )
    );

    protected static $_tableDefsStr = array(
        self:: tableName => array(
            'fields'  =>
                'id             int auto_increment
                label            varchar(255)
                outofstock      tinyint(1)
                categoryId     INT
                imageId        INT
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
    protected static $imageSizes = array(
        'default' => 'grid',
        'grid'    => array(
            'width'          => 310,
            'height'         => 310,
            'crop'           => true,
            'corners'        => 0,
            'grayscale'      => FALSE,
            'forceExactSize' => true,
            'optimize'       => true,
            'default'        => '/images/default-product--thumb.png',
        ),
        'basket'  => array(
            'width'          => 120,
            'height'         => 118,
            'crop'           => true,
            'corners'        => 0,
            'grayscale'      => FALSE,
            'forceExactSize' => true,
            'optimize'       => true,
            'default'        => '/images/default-product--basket.png',
        ),
        'preview' => array(
            'width'          => 1600,
            'height'         => 1200,
            'asis'           => true,
            // return image as is
            'crop'           => true,
            'corners'        => 0,
            'grayscale'      => FALSE,
            'forceExactSize' => false,
            'optimize'       => true,
            'default'        => '/images/default-product--preview.png',
        )
    );


    /**
     * @param $values
     *
     * Called when saving products. Used to store product price (and other info if needed) for each country that has a separate shop.
     */
    public function saveCountries( $values )
    {
        if ( !get( $values, 'countries' ) )
        {
            return null;
        }
        else
        {
            $where = 'productId = ' . intval( $this->id );
            dbDelete( leafBaseObject::getClassTable( 'bundleProductCountryRelation' ), $where );
            bundleProductCountryRelation::saveCountries( $this->id, $values );
        }
    }


    /**
     * @param null $countryId       Id of country that should be used to get price
     * @return bool|mixed|null
     *
     * Used to get pricing info for a product in a specific country
     */
    public function getLocalizedPrice( $countryId = null )
    {
        if ( is_null( $countryId ) )
        {
            $country = bundleCountry::getCurrentCountry();
            $countryId = $country->id;
        }

        $q = 'SELECT price FROM ' . leafBaseObject::getClassTable( 'bundleProductCountryRelation' ) . ' WHERE countryId = ' . $countryId . ' AND productId = ' . $this->id;
        return dbgetone( $q );
    }

    /**
     * @return array
     *
     * Returns an array of IDs of all countries that sell this product
     */
    public function getCountryIDs()
    {
        $countries = $this->__get( 'countries' );
        $countriesIDs = array();
        if ( sizeof( $countries ) )
        {
            foreach ( $countries as $value )
            {
                $countriesIDs[ ] = $value->countryId;
            }
        }
        return $countriesIDs;
    }


    /**
     * @return bool
     *
     * Returns whether the product is in stock
     */
    public function inStock()
    {
        return empty( $this->outofstock );
    }


    /**
     * @param int $amount
     * @return string
     *
     * Returns price in a readable format without currency symbol
     */
    public function getReadablePriceNoCurrency( $amount = 1 )
    {
        $country = bundleCountry::getCurrentCountry();
        $price = $this->getLocalizedPrice( $country->id ) * $amount;

        return number_format( round( $price / 100, 2 ), 2, '.', '' );
    }

    /**
     * @param int $amount
     * @return string
     *
     * Returns price in a readable format with currency symbol
     */
    public function getReadablePrice( $amount = 1 )
    {
        $country = bundleCountry::getCurrentCountry();

        return $country->currencyCode . ' ' . $this->getReadablePriceNoCurrency();
    }
}