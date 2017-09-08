<?php

namespace Leaf\Autoload;

class ClassLoader extends \Composer\Autoload\ClassLoader
{
	public function loadClass( $class )
	{
		if( parent::loadClass( $class ) && method_exists( $class, '_autoload' ) )
		{
			$class::_autoload( $class );
		}

		if( strpos( $class, \xmlize::classPrefix ) === 0 )
        {
            \xmlize::loadClass( $class );
        }
	}
}
