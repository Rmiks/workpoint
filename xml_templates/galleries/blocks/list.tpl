{if $galleries}
	<ul>
		{foreach from=$galleries item=item name="galleries"}
			<li>
				<a href="{$item->getUrl()}">
					{$item->name|escape}
				</a>
			</li>
		{/foreach}
	</ul>
{/if}