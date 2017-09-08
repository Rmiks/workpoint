<?php
class adminLog extends bundleBaseModule
{
    protected $mainObjectClass      = 'massiveLog';
	public $actions                 = array();
	public $output_actions          = array( 'all', 'view');
    public $panelLayout             = true;
    public $tableMode               = 'css';
    protected $itemsPerPage         = 35;
	public $features                = array
	(
		'view' 	 => true,
	);

    private $skipActions = array(
        '"richtextEmbedDialog"',
        '"richtextImageDialog"',
        '"get_childs"',
        '"canDeleteObjects"',
        '"close_childs"',
        '"confirmDelete"'
    );

    private $skipModules = array(
        '"aliases"',
        '"adminLog"'
    );

    public function all( $extraParams = array() )
    {
        $this->useWidget( 'sortable' );
        //def sorting
        if(get($_GET, 'orderBy', false) === false){
            $extraParams[ 'orderBy' ][ ] = 't.id DESC';
        }
        $extraParams[ 'where' ][ ] = 't.action_name NOT IN ("", '. implode(',', $this->skipActions).')';
        $extraParams[ 'where' ][ ] = 't.module_name NOT IN ('. implode(',', $this->skipModules).')';

        $assign = parent::all( $extraParams );
        return $assign;
    }
}
