<?php
class userGroups extends bundleBaseModule
{
    protected $mainObjectClass = null;

    public $features = [
        'create' => false,
        'view'   => true,
        'edit'   => true,
        'delete' => true
    ];

    public $tableMode = 'css';

    public function __construct()
	{
        $this->mainObjectClass = leafAuthorization::getGroupClass();
        return parent::__construct();
    }

    public function view()
    {
        $assign = parent::view();
        $assign['modules'] = leafAdminAbility::getModuleConfigurations($assign['item']->id);
        $this->hideGroupModules($assign['item']->__toString(), $assign['modules']);
        $this->hideUserGroupModules($assign['modules']);
        $this->hideInaccessibleModules($assign['item']->id, $assign['modules']);
        $assign['moduleNames'] = leafAdminAbility::getModuleNames();
        foreach($assign['modules'] as $moduleName => $foo)
        {
            $assign['moduleNames'][$moduleName] = $moduleName;
        }

        return $assign;
    }

    public function edit()
    {

        $assign = parent::edit();
        $assign['modules'] = leafAdminAbility::getModuleConfigurations($assign['item']->id);
        $this->hideGroupModules($assign['item']->__toString(), $assign['modules']);
        $this->hideUserGroupModules($assign['modules']);
        $this->hideInaccessibleModules($assign['item']->id, $assign['modules']);

        $assign['moduleNames'] = leafAdminAbility::getModuleNames();
        foreach($assign['modules'] as $moduleName => $foo)
        {
            $assign['moduleNames'][$moduleName] = $moduleName;
        }

        return $assign;
    }

    public function isItemDeletable( $item )
    {
        if ($item && $item->getUsersCount() < 1)
        {
            return true;
        }
        return false;
    }

    //takes module arr by ref.  and removes hidden modules for that group
    //hiden modules are defined in admin config per user group
    private function hideGroupModules($groupName, &$moduleArr){
        //get from config values
        $hideableModules = leaf_get_property('hideGroupModules');

        //check if we have something to hide at all
        if(false == isset($hideableModules[$groupName])){
            return false;
        }
        foreach ($hideableModules[$groupName] as $key => $moduleName) {
            unset($moduleArr[$moduleName]);
        }

        return $moduleArr;
    }

    //takes module arr by ref.  and removes modules if current user group module is hidden
    //this is used so that user cant change other group modules that are not available for him
    //hiden modules are defined in admin config per user group
    private function hideUserGroupModules(&$moduleArr){
        $groupId = call_user_func(leafAuthorization::getUserClass() . '::getCurrentUserGroupId');
        $groupClass = leafAuthorization::getGroupClass();
        $group = $groupClass::getById($groupId);
        $this->hideGroupModules($group->name, $moduleArr);
    }

    //hide module that user group has no access
    //allow all modules for admin group
    private function hideInaccessibleModules($groupId, &$moduleArr){
        $groupId = call_user_func(leafAuthorization::getUserClass() . '::getCurrentUserGroupId');
        $groupClass = leafAuthorization::getGroupClass();
        $group = $groupClass::getById($groupId);
        //allow all modules for admin group
        if($group->id == 1 || $group->name == 'administrators'){
            return false;
        }

        foreach ($moduleArr as $moduleName => $value) {
            if(leafAdminModuleAccess::checkAccess($groupId, $moduleName, 1) != true){
                unset($moduleArr[$moduleName]);
            }
        }
        return $moduleArr;
    }

}
