{capture assign=breadcrumbsTitle}
	{if $title}
		{alias code=$title} 
	{else}
		{alias code=allTitle} 
	{/if}
{/capture}

<header class="header">
	{include file=$_module->pathTo('_all.breadcrumbs') title=$breadcrumbsTitle}

	{include file=$_module->pathTo('_all.search')}

	{include file=$_module->pathTo('_all.additionalHeader')}
</header>

<div class="content outsetBox">
	{if $_module->tableMode=="css"}
		<div class="leafTable noBorder alternateRows selectable moz-unselectable" data-continuous="{$_module->continuousScroll*1}" data-total="{$collection->total}" data-itemsPerPage="{$_module->getItemsPerPage()}" data-loading="{alias code=loading}">
			{if count($collection) > 0}
				{include file=$_module->pathTo('_rows')}
			{else}
				<div class="nothingFoundMessage">
					{alias code=$nothingFoundAlias|default:nothingFound}
				</div>
			{/if}
		</div>
	{else}
		<table class="leafTable noBorder alternateRows selectable moz-unselectable" cellspacing="0" cellpadding="0">
			{include file=$_module->pathTo('_rows')}
		</table>
	{/if}
</div>

{include file=$_module->pathTo('all._footer')}
