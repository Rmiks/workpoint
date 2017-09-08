<?php

class bundleNews extends bundleBaseModule
{

    protected $mainObjectClass = 'bundleNewsPost';
    public $features = [
        'create' => true,
        'view'   => false,
        'edit'   => true,
        'delete' => true
    ];
    
    public $actions = [ 'save', 'delete', 'removeImage' ];

    public $tableMode = 'css';

    public function edit()
    {
        $this->useWidget( 'richtext' );
        $this->useWidget( 'sortable' );
        $this->useWidget( 'tabs' );

        $return = parent::edit();

        return $return;
    }

    public function removeImage()
    {
        $item = getObject( 'bundleNewsPostImage', get( $_GET, 'imageId' ) );

        if ( !empty( $item->id ) ) {
            $item->delete();
        }

        die();
    }
}