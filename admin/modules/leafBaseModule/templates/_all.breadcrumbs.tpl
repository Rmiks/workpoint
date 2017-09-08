{strip}
	<ul class="header__breadcrumbs">
		<li class="header__breadcrumbsItem">
			<a href="/admin">{alias code=home context="admin"}</a>
			<svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="{request_url}#arrow_right"></use></svg>
		</li>
		<li class="header__breadcrumbsItem">
			{if $title}
				<a href="?module={$_module|@get_class|escape}">{alias code=$_module|@get_class context="admin:moduleNames"}</a>
				<svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="{request_url}#arrow_right"></use></svg>
			{else}
				<span>{alias code=$_module|@get_class context="admin:moduleNames"}</span>
			{/if}
		</li>
		{if $title}
			<li class="header__breadcrumbsItem">
				<span>{$title}</span>
			</li>
		{/if}
	</ul>
{/strip}