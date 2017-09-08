<h1>Imma, {$post->name|escape}</h1>

{if $posts}
	<h2>Related</h2>
	{include file="blocks/list.tpl"}
{/if}