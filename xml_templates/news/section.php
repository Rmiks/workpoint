<?php

class section extends xml_template
{
    public $hasPathParts = true;

    public
        $slug,
        $post;

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

        $this->post = bundleNewsPost::getByUrl( $this->slug );
        if ( !$this->post )
        {
            $rewrite = _core_load_module( 'leaf_rewrite' );
            $rewrite->error_404( true, true );
        }

        $data[ 'post' ]  = $this->post;
        $data[ 'posts' ] = $this->post->getRelatedPosts();
        $this->_setTemplate( 'item.tpl' );
        leaf_set( 'path_part', null );
    }

    public function listView()
    {
        $data = & $this->object_data[ 'data' ];

        $page = get( $_GET, 'page', 1 );
        $data[ 'posts' ] = bundleNewsPost::getPosts( $page );
    }

    public function getMetaDescription()
    {
        if ( !empty( $this->post->metaDescription  ) )
        {
            return $this->post->metaDescription;
        }

        if ( !empty( $this->post->text ) )
        {
            return $this->post->text;
        }

        return false;
    }
}