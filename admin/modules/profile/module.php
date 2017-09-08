<?
class profile extends leafBaseModule{
	public $actions = array('save');
	public $output_actions = array('view','edit');
	public $default_output_function = 'view';

	var $assigns = array('css');

	function save(){
		$user = getObject('leafUser', $_SESSION[SESSION_NAME]['user']['id']);
		$user->setMode('user');
		$user->variablesSave($_POST);
		// backward compatibility
		$_SESSION[SESSION_NAME]['user'] = dbGetRow('SELECT * FROM users WHERE id = ' . $user->id);
	}

	function view(){
		$assign['item'] = getObject('leafUser', $_SESSION[SESSION_NAME]['user']['id']);
		return $assign;
	}

	function edit(){
		$assign['item'] = getObject('leafUser', $_SESSION[SESSION_NAME]['user']['id']);
		return $assign;
	}
}
?>