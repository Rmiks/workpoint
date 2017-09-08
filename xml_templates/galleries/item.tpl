<h1>Imma, {$gallery->name|escape}</h1>

{if $galleries}
	<h2>Related</h2>
	{include file="blocks/list.tpl"}
{/if}