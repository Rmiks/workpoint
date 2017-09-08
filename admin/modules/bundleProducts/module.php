<?php

class bundleProducts extends bundleBaseModule
{

    protected $mainObjectClass = 'bundleProduct';

    public $features = [
        'additional' => [ 'all.header' ],
        'edit'       => true,
        'create'     => true,
        'delete'     => true
    ];

    public function all( $extraParams = [] )
    {
        _core_add_js( SHARED_WWW . '3rdpart/jquery/jquery-core.js' );
        _core_add_js( SHARED_WWW . '3rdpart/jquery/ui/ui.core.min.js' );
        _core_add_js( SHARED_WWW . '3rdpart/jquery/ui/ui.widget.min.js' );
        _core_add_js( SHARED_WWW . '3rdpart/jquery/ui/ui.mouse.min.js' );
        _core_add_js( SHARED_WWW . '3rdpart/jquery/ui/ui.draggable.min.js' );
        _core_add_js( SHARED_WWW . '3rdpart/jquery/ui/ui.droppable.min.js' );
        _core_add_js( SHARED_WWW . '3rdpart/jquery/ui/ui.sortable.min.js' );

        $where = get( $_GET, 'filterCategory', null );

        if( !getObject( 'bundleProductCategory', $where ) )
        {
            $id                       = bundleProductCategory::getCollection()->first()->id;
            $extraParams[ 'where' ][] = 't.categoryId = ' . $id;
        }
        elseif( sizeof( $where ) )
        {
            $extraParams[ 'where' ][] = 't.categoryId = ' . $where;
        }

        $assign = parent:: all( $extraParams );

        $this->options[ 'categories' ] = bundleProductCategory:: getCollection();
        return $assign;
    }

    public function edit()
    {
        $this->useWidget( 'tabs' );
        $this->useWidget( 'richtext' );
        $return = parent:: edit();

        $return[ 'countries' ]  = bundleCountry::getCollection();
        $return[ 'categories' ] = bundleProductCategory::getCollection();

        $currCountryIds               = $return[ 'item' ]->getCountryIDs();
        $return[ 'currCountriesIds' ] = $currCountryIds;

        return $return;
    }

    public function save()
    {
        $data = array_merge( $_POST, $_FILES );

        if( empty( $data[ 'outofstock' ] ) )
        {
            $data[ 'outofstock' ] = 0;
        }

        $item = getObject( $this->mainObjectClass, get( $_GET, 'id', null ) );
        $item->variablesSave( $data );
        leafHttp::redirect( $_POST[ 'listUrl' ] );
    }

}
