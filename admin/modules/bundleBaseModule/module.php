<?php
// project specific features go here
class bundleBaseModule extends leafBaseModule
{
    // feel free to add or edit properties below based on specific project needs
	public $actions = array( 'save', 'delete' );
	public $output_actions = array( 'all', 'view',  'edit', 'confirmDelete', 'saveAndRespond', 'deleteAndRespond' );
	public $features = array
	(
		'create' => true,
		'view' 	 => true,
		'edit' 	 => true,
		'delete' => true
	);

    public $tableMode = 'css';

    public function all( $extraParams = array() )
    {
        $params = parent::all( $extraParams );
        return $params;
    }

    public function edit()
    {
        $params = parent::edit();

        if ( !empty( $_GET[ 'suggest_rewrite_name' ] ) )
        {
            $language = !empty( $_GET[ 'i18n_language' ] ) ? $_GET[ 'i18n_language' ] : null;

            $rewrite = $params[ 'item' ]->createSlug( $_GET[ 'suggest_rewrite_name' ], $language );

            die( $rewrite );
        }
        
        return $params;
    }

	public function view()
    {
        $params = parent::view();
        return $params;
    }

}