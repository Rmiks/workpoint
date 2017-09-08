<?php

class Products_Category extends xml_template
{

    public $on_field_load = 'onFieldLoad';
    public $hasPathParts = true;

    public
        $productId,
        $product;

    public function dynamic_output( & $module = null )
    {
        $data = & $this->object_data['data'];

        pp($productId, $rest);

        if (!is_null( $productId ))
        {
            $this->productId = $productId;
            $this->itemView( $productId, $rest );
        }
        else
        {
            $this->gridView();
        }
    }

    public function getProductId()
    {
        return $this->productId;
    }

    public function getName()
    {
        $name = get( $this->object_data['data'], 'nameFormatted', $this->object_data['name'] );
        return nl2br( htmlspecialchars( $name ) );
    }

    public function itemView( $productId, $rest )
    {
        if (is_null( $rest ))
        {
            $this->product = $this->getProductObject( $productId );

            if (is_object( $this->product ))
            {
                leaf_set( 'path_part', null );
                $this->_setTemplate( 'product-item.tpl' );
                $this->object_data['data']['product']  = $this->product ;
            }
            else
            {
                $this->triggerError();
            }
        }
        else
        {
            $this->triggerError();
        }
    }

    public function gridView()
    {
        $this->_setTemplate( 'product-grid.tpl' );

        $data = & $this->object_data['data'];        

        $country = bundleCountry::getCurrentCountry();

        $params['where'][] = 't.categoryId = ' . get( $data, 'category', 0 );

     
        $params['where'][] = 'x.countryId = ' . $country->id;
        $params['leftJoins'][] = 'bundleProductCountryRelations AS x ON t.id = x.productId';
        

        $list = bundleProduct::getCollection( $params );        

        $data['list'] = $list;
    }

    public function getProductObject( $productId )
    {
        $parts = explode( '-', $productId );
        $id    = current( $parts );
        if (isPositiveInt( $id ))
        {
            $product = getObject( 'bundleProduct', $id );
            if (is_object( $product ))
            {
                return $product;
            }
        }

        return null;
    }

    public function triggerError()
    {
        $lr = new leaf_rewrite();
        $lr->error_404( true );
    }

    public function onFieldLoad( & $fields )
    {
        $options = array();
        $categories = bundleProductCategory::getCollection();


        $options[] = '';
        foreach ($categories as $category)
        {
            $options[$category->id] = $category->name;
        }

        $fields['category']['properties']['options'] = $options;
        return;
    }

    public function getMetaDescription()
    {
        if ( !empty( $this->product ) )
        {
            if ( !empty( $this->product->__get( 'metaDescription' ) ) )
            {
                return $this->product->metaDescription;
            }

            if ( !empty( $this->product->__get( 'description' ) ) )
            {
                return $this->product->description;
            }
        }
        return false;
    }
}
