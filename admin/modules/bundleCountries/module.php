<?php

class bundleCountries extends bundleBaseModule
{

    protected $mainObjectClass = 'bundleCountry';

    public $features = array(
        'additional' => array( 'all.header' ),
        'edit'       => true,
        'create'     => true,
        'delete'     => true
    );


    public function all( $extraParams = array() )
    {
        $assign = parent :: all( $extraParams );
        return $assign;
    }

    public function edit()
    {

        $this->useWidget( 'tabs' );
        $return = parent :: edit();
        return $return;
    }

    public function save()
    {
        $data = $_POST;

        if ( empty( $data['active'] ) )
        {
            $data['active'] = 0;
        }

        $item = getObject( $this->mainObjectClass, get( $_GET, 'id', null ) );
        $item->variablesSave( $data );
    }

}
