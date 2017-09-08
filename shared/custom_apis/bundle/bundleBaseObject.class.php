<?php

class bundleBaseObject extends leafBaseObject
{

    protected static $searchFields = [ 'name' ];

    public static function processParams( $field, $params, & $queryParts )
    {
        if( !empty( $params[ $field ] ) )
        {
            if( is_array( $params[ $field ] ) )
            {
                foreach( $params[ $field ] as $value )
                {
                    $queryParts[ $field ][] = $value;
                }
            }
            else
            {
                $queryParts[ $field ][] = $params[ $field ];
            }
        }
    }

    public static function getQueryParts( $params = [] )
    {
        $queryParts = parent::getQueryParts( $params );

        if( isset( $params[ 'orderBy' ] ) && !is_array( $params[ 'orderBy' ] ) && isset( $params[ 'direction' ] ) )
        {
            $params[ 'orderBy' ] .= ' ' . dbse( $params[ 'direction' ] );
        }

        static::processParams( 'where', $params, $queryParts );
        static::processParams( 'select', $params, $queryParts );
        static::processParams( 'orderBy', $params, $queryParts );
        static::processParams( 'groupBy', $params, $queryParts );
        static::processParams( 'leftJoins', $params, $queryParts );
        static::processParams( 'limit', $params, $queryParts );
        static::processParams( 'having', $params, $queryParts );

        if( isset( $params[ 'search' ] ) )
        {
            $queryParts = static::getQueryPartsSearch( $queryParts, $params, static::$searchFields );
        }


        return $queryParts;
    }

    public function makeOptional( $definition )
    {
        return [ 'optional' => true ];
    }

    public function getRewriteName( $isI18n = false, $languageRewrite = null )
    {
        if( $isI18n )
        {
            $languageRewrite = isset( $languageRewrite ) ? $languageRewrite : leaf_get_property( 'language_code' );
            $this->getI18nValue( 'name', $languageRewrite );
        }
        $name = $this->__get( 'name' );
        return strtolower( $this->id . '-' . stringToLatin( $name, true, true ) );
    }


    public function getImageSizes()
    {
        return property_exists( get_called_class(), "imageSizes" ) ? static::$imageSizes : [];
    }

    public function getObjectRelations()
    {
        return $this->objectRelations;
    }

    public function getImageUrl( $name = 'image', $size = null, $path = false, $forceReload = false )
    {
        if( !isset( static::$imageSizes ) )
        {
            $config = [ 'asis' => true ];
        }
        else
        {
            if( $size === "default" || is_null( $size ) || !isset( static::$imageSizes[ $size ] ) )
            {
                $size = get( static::$imageSizes, 'default' );
            }


            $config = get( static::$imageSizes, $size, [] );
        }

        $name_id = $name . 'Id';

        if( !empty( $this->$name_id ) )
        {
            $image = $this->__get( $name );
            $id    = $this->$name_id;
        }
        else
        {
            return !empty( $config[ 'default' ] ) ? $config[ 'default' ] : false;
        }

        if( !empty( $config[ 'asis' ] ) )
        {
            return $image->getFullUrl();
        }

        $ext = pathinfo( $image->getFullUrl(), PATHINFO_EXTENSION );

        $fileName    = $size . '-' . $id . substr( md5( serialize( $config ) ), 0, 8 ) . '.' . $ext;
        $tmpFilePath = CACHE_PATH . $fileName;
        $tmpFileUrl  = CACHE_WWW . $fileName;

        if( !file_exists( $tmpFilePath ) )
        {
            $resize = getObject( ( $config[ 'crop' ] ) ? 'imageResizeAndCrop' : 'imageResize' );
            $params = [
                'targetFile' => $tmpFilePath,
                'width'      => $config[ 'width' ],
                'height'     => $config[ 'height' ],
            ];

            if( !$config[ 'crop' ] )
            {
                $params[ 'forceExactSize' ] = ( !empty( $config[ 'forceExactSize' ] ) );
            }


            if( is_object( $image ) )
            {
                $filePath = $this->__get( $name )->getFullPath();
            }
            else
            {
                $imageContent = @file_get_contents( $image );
                if( $imageContent )
                {
                    $filePath = $tmpFilePath . '.src';
                    file_put_contents( $filePath, $imageContent );
                }
                else
                {
                    if( !empty( $config[ 'default' ] ) )
                    {
                        $defaultContent = file_get_contents( BASE_PATH . $config[ 'default' ] );
                        file_put_contents( $tmpFilePath, $defaultContent );

                        return $path ? $tmpFilePath : $tmpFileUrl;
                    }

                    return false;
                }
            }

            if( empty( $filePath ) )
            {
                return false;
            }

            $imageSize = getimagesize( $filePath );

            // invalid image
            if( !$imageSize )
            {
                return array_key_exists( 'default', $config ) ? $config[ 'default' ] : false;
            }

            $resize->processInput( $filePath, $params );


            if( !empty( $config[ 'corners' ] ) )
            {
                $params = [
                    //png corner file must be 24bit PNG
                    //file must be made so that transparent segment will be transparent in final picture
                    //black segment will be the picture
                    'targetFile'         => $tmpFilePath,
                    'cornerFile'         => WWW_PATH . 'images/render/corners/' . $config[ 'corners' ] . '.png',
                    'transparentCorners' => true,
                ];

                $corners = new imageCorners();
                $corners->processInput( $tmpFilePath, $params );
            }

            // make grayscale
            if( !empty( $config[ 'grayscale' ] ) )
            {
                $im = imagecreatefrompng( $tmpFilePath );
                imagefilter( $im, IMG_FILTER_GRAYSCALE );
                imagepng( $im, $tmpFilePath );
            }

            $properties = leaf_get( 'properties' );
            $pngquant   = get( $properties, 'pngquant' );
            if( !empty( $config[ 'optimize' ] ) && $pngquant )
            {
                $cmd = $pngquant . ' -f --ext ".png" ' . $tmpFilePath;
                exec( $cmd );
            }
        }

        if( $forceReload && !$path )
        {
            $tmpFileUrl .= '?h=' . filemtime( $tmpFilePath );
        }

        return $path ? $tmpFilePath : $tmpFileUrl;
    }

    private function imageIsJpg( $image )
    {
        if( $image instanceof leafFile )
        {
            return $image->type === 'image/jpeg';
        }

        $parts = explode( '.', $image );
        if( sizeof( $parts > 1 ) && in_array( end( $parts ), [ 'jpg', 'jpeg' ] ) )
        {
            return true;
        }
        return false;
    }

    public function slugify( $field )
    {
        return strtolower( stringToLatin( $field, true, true ) );
    }

    public function createSlug( $value = null, $language = null )
    {
        if( !empty( $language ) )
        {
            $i18n        = $this->__get( 'i18n' );
            $currentSlug = !empty( $i18n[ $language ][ 'slug' ] ) ? $i18n[ $language ][ 'slug' ] : null;

            if( !empty( $i18n[ $language ] ) && !empty( $value ) )
            {
                if( !empty( $currentSlug ) && $currentSlug === $this->slugify( $value ) )
                {
                    return $i18n[ $language ][ 'slug' ];
                }
            }
        }
        else if( $value && $this->slugify( $value ) === $this->slug )
        {
            return $this->slug;
        }

        $slug        = $this->slugify( $value );
        $currentSlug = !empty( $currentSlug ) ? $currentSlug : $this->slug;

        $i = 1;
        while( !self::isUniqueSlug( $slug, $language ) && $slug !== $currentSlug )
        {
            $slug = $this->slugify( $value . ' ' . $i );
            $i++;
        }

        return $slug;
    }

    public static function isUniqueSlug( $slug, $language = null )
    {
        $params = [];

        if( !empty( $language ) )
        {
            $params[ 'where' ][]       = '`i18n:' . $language . '`.`slug` = "' . $slug . '"';
            $params[ 'loadLanguages' ] = [ $language ];
        }
        else
        {
            $params[ 'where' ][] = 't.slug = "' . $slug . '"';
        }

        $qp  = self::getQueryParts( $params );
        $sql = dbBuildQuery( $qp );

        return dbGetOne( $sql ) ? false : true;
    }
}