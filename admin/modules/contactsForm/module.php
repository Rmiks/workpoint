<?php

class contactsForm extends bundleBaseModule
{

    protected $mainObjectClass = 'contacts';
    public $features = array
    (
        'create'    => false,
        'view'      => true,
        'edit'      => true,
        'delete'    => true,
    );

}

?>