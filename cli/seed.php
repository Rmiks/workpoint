<?php
require_once(dirname(__FILE__) . '/prepend.cli.php');


// clear shared path xml_template files
array_map('unlink', glob( CACHE_PATH . "*.php"));

// normalize database charset
dbQuery('ALTER DATABASE `' . leaf_get('properties', 'dbconfig', 'database') . '` charset=UTF8');

// languages
$languages = array('lv', 'en');
foreach($languages as $language)
{
    getObject('leafLanguage', 0)->variablesSave(array(
        'short' => $language,
        'code' => $language,
    ));
}

// general permissions
$modulesConfiguration = array();
foreach(leafAdminAbility::getModuleConfigurations(0) as $moduleName => $module)
{
    $modulesConfiguration[$moduleName]['process'][1] = 1;
}

// admin user and group
$adminGroup = getObject('leafUserGroup', 0);
$adminGroup->variablesSave(array(
    'name' => 'administrators',
    'default_module' => 'content',
    'configurations' => $modulesConfiguration
));

//admin
$user = getObject('leafUser', 0);
$user->variablesSave(array(
        'login' => 'admin',
        'email' => 'support@cube.lv',
        'name' => 'admin',
        'surname' => 'admin',
        'group_id' => $adminGroup->id,
        'password1' => 'admin',
        'password2' => 'admin',
        'language' => leafLanguage::getByCode('lv')->id,
    ),
    null,
    'admin'
);

//create client group and user
//remove persmissions from modules
//TODO: set this per project
$modulesToRemove = array();
foreach ($modulesToRemove as $module) {
    if(isset($modulesConfiguration[$module])){
        $modulesConfiguration[$moduleName]['process'][1] = 0;
    }
}

// create client group
$clientGroup = getObject('leafUserGroup', 0);
$clientGroup->variablesSave(array(
    'name' => 'clients',
    'default_module' => 'content',
    'configurations' => $modulesConfiguration
));

//create def. client user
$user = getObject('leafUser', 0);
$user->variablesSave(array(
        'login' => 'client',
        'email' => 'client@client.lv',
        'name' => 'client',
        'surname' => 'client',
        'group_id' => $clientGroup->id,
        'password1' => 'client',
        'password2' => 'client',
        'language' => leafLanguage::getByCode('lv')->id,
    ),
    null,
    'admin'
);

// create some content objects - language_root (lv/en), services (robots/sitemap)
foreach($languages as $language)
{
    $object_param = array(
        'type' => 22,
        'parent_id' => 0,
        'id' => 0
    );

    $params = array(
        'template' => 'language_root',
        // 'parent_id' => 0,
        'name' => strtoupper($language),
        'rewrite_name' => $language,
        'postCompleted' => 1
    );

    $object = _core_load_object($object_param, NULL, $params);
    $object->saveObject($params);

    // create text object
    $object_param = array(
        'type' => 22,
        'parent_id' => $object->object_data['id'],
        'id' => 0
    );

    $params = array(
        'template' => 'text',
        'parent_id' => $object->object_data['id'],
        'name' => 'Text',
        'rewrite_name' => 'text',
        'text' => '<p>Lorem ipsum</p>',
        'postCompleted' => 1
    );

    $object = _core_load_object($object_param, NULL, $params);
    $object->saveObject($params);
}

// add robots
$object_param = array(
    'type' => 22,
    'parent_id' => 0,
    'id' => 0
);

$params = array(
    'template' => 'siteServices/robots',
    'name' => 'robots.txt',
    'rewrite_name' => 'robots.txt',
    'postCompleted' => 1
);

$object = _core_load_object($object_param, NULL, $params);
$object->saveObject($params);

// add sitemap.xml
$object_param = array(
    'type' => 22,
    'parent_id' => 0,
    'id' => 0
);

$params = array(
    'template' => 'siteServices/sitemap',
    'name' => 'sitemap.xml',
    'rewrite_name' => 'sitemap.xml',
    'postCompleted' => 1
);

$object = _core_load_object($object_param, NULL, $params);
$object->saveObject($params);

// translations
require_once(PATH . 'admin/modules/aliases/module.php');
$aliases = new aliases();
$aliases->updateAliases();
