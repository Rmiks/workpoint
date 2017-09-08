<form class="searchForm" action="">
	<input type="hidden" name="module" value="{$_module|get_class|escape}"/>
	<input type="hidden" name="do" value="{if $smarty.get.do}{$smarty.get.do|escape}{else}all{/if}"/>
	<div class="special-field-wrap">
		{strip}
			<input name="search" type="text" class="search focusOnReady" placeholder="{alias code='searchInModule...'}" value="{$smarty.get.search|escape}"/>
			<button type="submit" class="noStyling">
				<svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="{request_url}#search"></use></svg>
			</button>
		{/strip}
	</div>
</form>
