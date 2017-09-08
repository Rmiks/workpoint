<?php

class section extends xml_template
{
    public $hasPathParts = true;

    public
        $slug,
        $gallery;

    function dynamic_output( & $module = null )
    {
        pp( $slug );

        if ( $slug )
        {
            $this->slug = $slug;
            $this->itemView();
        }
        else
        {
            $this->listView();
        }
    }

    public function itemView()
    {
        $data = & $this->object_data[ 'data' ];

        $this->gallery = bundleGallery::getByUrl( $this->slug );

        if ( !$this->gallery )
        {
            $rewrite = _core_load_module( 'leaf_rewrite' );
            $rewrite->error_404( true, true );
        }

        $data[ 'gallery' ]   = $this->gallery ;
        $data[ 'galleries' ] = $this->gallery ->getRelatedPosts();
        $this->_setTemplate( 'item.tpl' );
        leaf_set( 'path_part', null );
    }

    public function listView()
    {
        $data = & $this->object_data[ 'data' ];

        $page = get( $_GET, 'page', 1 );
        $data[ 'galleries' ] = bundleGallery::getPosts( $page );
    }

    public function getMetaDescription()
    {
        if ( !empty( $this->gallery->metaDescription  ) )
        {
            return $this->gallery->metaDescription;
        }

        if ( !empty( $this->gallery->text ) )
        {
            return $this->gallery->text;
        }
        return false;
    }
}