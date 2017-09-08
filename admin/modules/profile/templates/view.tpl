{alias_context code="admin:users"}
{alias_fallback_context code="admin"}

<div class="content">

    <div class="section">
    	{include file=$_module->pathTo('_view.field') name="login"}
    	{include file=$_module->pathTo('_view.field') name="name" disabled=true}
    	{include file=$_module->pathTo('_view.field') name="surname" disabled=true}
    	{include file=$_module->pathTo('_view.field') name="email" disabled=true}
    	<div class="field">
			<div class="labelWrap"></div>
			<div class="value">
				<a class="button" href="{request_url add="do=edit&returnUrl=?module=profile"}">
					<img src="{$smarty.const.ADMIN_WWW}images/icons/pencil.png" alt="" />
					{alias context=admin code=edit}
				</a>
			</div>
			<div class="clear"></div>
		</div>
    </div>

</div>