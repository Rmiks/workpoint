<div class="footer">
	<div class="btn-panel">
		{strip}
			{if $smarty.get.listUrl}
				<a href="{$smarty.get.listUrl|escape}" class="button btn-panel_btn">
					<img src="images/icons/arrow_left.png" alt="{alias code="back"}" />
				</a>
				{assign var=listUrl value=$smarty.get.listUrl|urlencode}
				{assign var=listUrlArg value="&listUrl=`$listUrl`"}
			{else}
				<span class="button-group compact btn-panel_btn" style="margin-right: 8px;" >
					<button disabled="disabled" class="" style="padding-left: 4px;">
						<img src="images/icons/arrow_left.png" alt="{alias code="back"}" />

					</button>
				</span>
			{/if}
			{if $_module->features.edit == true}
				<a href="{$_module->getModuleUrl()|escape}&amp;do=edit&amp;id={$item->id|escape}{$listUrlArg|escape}" class="button btn-panel_btn">
					<img src="images/icons/page_white_edit.png" alt="{alias code="edit"}" />
				</a>
			{/if}
			
			{include file=$_module->pathTo('view._extras')}

			{if $_module->features.delete == true}
				<form action="{request_url add='do=delete' remove='listUrl'}" class="deleteForm" method="post">
					{if $smarty.get.listUrl}
						<input type="hidden" name="returnUrl" value="{$smarty.get.listUrl|escape}" /> {* returnUrl = confirmation success url *}
					{/if}
                    <input type="hidden" name="listUrl" value="{request_url}" />
                    {* listUrl = confirmation cancel url *}
					<button class="deleteButton" type="submit">
						<img src="images/icons/bin_empty.png" class="icon" alt="{alias code="delete"}"/>
					</button>
				</form>
			{/if}
			
		{/strip}
	</div>
</div>