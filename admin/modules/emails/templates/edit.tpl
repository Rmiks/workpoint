<form action="{$_module->getModuleUrl()}&amp;do=save&amp;id={$item->id}&amp;email={$_module->getMainObjectClass()}" method="post" enctype="multipart/form-data" class="edit validatable pure-form pure-form-stacked">

	{include file=$_module->pathTo('_edit.header') overrideAlias=$_module->getMainObjectClass() context="admin:leafBaseModule:`$_module->submenuGroupName`"}

	<div class="content">
        <div class="section pure-g">
            {include file=$_module->useWidget('emailBody')}
        </div>
    </div>

	{include file=$_module->pathTo('_edit.footer')}

</form>