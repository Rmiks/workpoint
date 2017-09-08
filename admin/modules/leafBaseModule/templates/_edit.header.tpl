{capture assign=breadcrumbsTitle}
	{if $overrideAlias && $context}
		{alias code=$overrideAlias context=$context}
	{elseif $overrideAlias}
		{alias code=$overrideAlias}
	{elseif $item->id == 0}
		{alias code="new"}
	{elseif $overrideName}
		{$overrideName|escape}
	{else}
		{$item->getDisplayString()|escape}
	{/if}
{/capture}

<header class="header">
	{include file=$_module->pathTo('_all.breadcrumbs') title=$breadcrumbsTitle}
</header>