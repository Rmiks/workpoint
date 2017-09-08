<?php

class bundleCountry extends bundleBaseObject {

    const tableName = 'bundleCountries';

    protected
            $name,
            $countryCode,
            $currencyCode,
            $active;
    protected
            $products;
    protected $objectRelations = array(
        'products' => array(
            'objectKey' => 'countryId',
            'object' => 'bundleProductCountryRelation'
        )
    );
    protected $fieldsDefinition = array(
        'name' => array(
            'not_empty' => true
        ),
        'countryCode' => array(
            'not_empty' => true
        ),
        'currencyCode' => array(
            'not_empty' => true
        ),
        'active' => array(
            'optional' => true,
            'input_type' => 'checkbox',
            'type' => 'int'
        )
    );
    protected static $_tableDefsStr = array(
        self:: tableName => array(
            'fields' =>
            'id                     int auto_increment
                name                varchar(255)
                countryCode         varchar(16)
                currencyCode        varchar(16)
                active              tinyint(1)
                '
            ,
            'indexes' => '
            primary id
            ',
            'engine' => 'InnoDB'
        )
    );

    /**
     * Returns currently active country.
     *
     * @return bundleCountry $country   Currently active bundleCountry object
     */
    public static function getCurrentCountry() {
        $languageCode = get($_COOKIE, 'language_code', leaf_get_property('language_code'));
        $array = explode('-', $languageCode);

        $params = array(
            'where' => array(
                't.countryCode = "' . $array[0] . '"'
            )
        );

        return bundleCountry::getObject($params);
    }

}
