{alias_context code="admin:users"}
{alias_fallback_context code="admin"}

<form action="{request_url add="do=save"}" method="post" class="edit validatable">

    <div class="content">

        <div class="section">
        	{include file=$_module->pathTo('_edit.input') name="login" disabled=true}
        	{include file=$_module->pathTo('_edit.input') name="name" disabled=true}
        	{include file=$_module->pathTo('_edit.input') name="surname" disabled=true}
        	{include file=$_module->pathTo('_edit.input') name="email" type="email"}
        	{include file=$_module->pathTo('_edit.input') name="oldpassword" type="password" alias="current_password" value=""}
        	{include file=$_module->pathTo('_edit.input') name="password1" type="password" value=""}
        	{include file=$_module->pathTo('_edit.input') name="password2" type="password" value=""}
        </div>

    </div>

    {include file=$_module->pathTo('_edit.footer')}

</form>