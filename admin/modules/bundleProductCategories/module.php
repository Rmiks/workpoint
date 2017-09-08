<?php

class bundleProductCategories extends bundleBaseModule {

    protected $mainObjectClass = 'bundleProductCategory';
    
    public $features = array(
        'edit'       => true,
        'create'     => true,
        'delete'     => true
    );

    public function all($extraParams = array()) {

        $assign = parent :: all($extraParams);
        return $assign;
    }

    public function edit() {
        $this->useWidget( 'richtext' );
        $return = parent :: edit();
        return $return;
    }

    public function save() {
        $data = array_merge($_POST, $_FILES);

        $item = getObject($this->mainObjectClass, get($_GET, 'id', null));
        $item->variablesSave($data);
    }

}
