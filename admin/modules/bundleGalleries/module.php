<?php

class bundleGalleries extends bundleBaseModule
{

    protected $mainObjectClass = 'bundleGallery';
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
        $item = getObject( 'bundleGalleryImage', get( $_GET, 'imageId' ) );

        if ( !empty( $item->id ) ) { 
            $item->delete();
        }

        die();
    }
}