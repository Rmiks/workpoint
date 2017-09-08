<div class="footer">
	<div class="stats">
		<span class="total">{$collection->total|number_format:0:".":" "}</span> {alias code="itemsFound"}
	</div>
	{if $collection->pages > 1 && !$_module->continuousScroll}
		{include file=$_module->useWidget('pageNavigation')}
	{/if}

	{assign var=optionCount value=0}
	{if $_module->features.create == true}
		{assign var=optionCount value=$optionCount+1}
	{/if}
	{if $_module->features.view == true}
		{assign var=optionCount value=$optionCount+1}
	{/if}
	{if $_module->features.edit == true}
		{assign var=optionCount value=$optionCount+1}
	{/if}
	{if $_module->features.delete == true}
		{assign var=optionCount value=$optionCount+1}
	{/if}

	{if $optionCount > 1}
		<div class="left btn-panel">
			{if $_module->features.create == true}
				<a class="createNewItem btn-panel_btn"
				   href="{$_module->getModuleUrl()}&amp;do=edit&amp;id=0&amp;returnUrl={request_url remove=ajax encode=true}">
					<svg class="plus">
						<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#plus"></use>
					</svg>
					{alias code='createNewItem'}
				</a>
			{/if}
			<span class="buttonGroup listViewActionSwitcher">
			{strip}
				{if $_module->features.view == true}
					<button type="button" class="btn-panel_btn {if $_module->getListViewAction()=="view"}active{/if}"
							title="{alias code=switchToView}"
							{if $_module->getListViewAction()=="view"}class="active"{/if} data-action="view">
					<img src="images/icons/eye.png" alt="{alias code=switchToView}"/>
				</button>
				{/if}
				{if $_module->features.edit == true}
				<button type="button" class="btn-panel_btn {if $_module->getListViewAction()=="edit"}active{/if}"
						title="{alias code=switchToEdit}" data-action="edit">
						<img src="images/icons/pencil.png" alt="{alias code=switchToEdit}"/>
					</button>
			{/if}
				{if $_module->features.delete == true}
				<button type="button"
						class="btn-panel_btn {if $_module->getListViewAction()=="confirmDelete"}active{/if}"
						title="{alias code=switchToDelete}" data-action="confirmDelete">
						<img src="images/icons/bin_empty.png" alt="{alias code=switchToDelete}"/>
					</button>
			{/if}
			{/strip}
				<div style="clear:both;"> </div>
		</span>
			{include file=$_module->pathTo('all._extras')}
		</div>
	{/if}
</div>