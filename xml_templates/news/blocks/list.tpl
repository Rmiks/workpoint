{if $posts}
	<ul>
		{foreach from=$posts item=item name="posts"}
			<li>
				<a href="{$item->getUrl()}">
					{$item->name|escape}
				</a>
			</li>
		{/foreach}
	</ul>
{/if}