<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{$siteTitle|escape} : Leaf</title>
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<meta http-equiv="content-type" content="text/html; charset=utf-8"  />
    <link rel="stylesheet" type="text/css" href="styles/pure.css" />
    <link rel="stylesheet" type="text/css" href="styles/grids-responsive.css" />
<link rel="stylesheet" type="text/css" href="styles/pure-extend.css" />
<link rel="stylesheet" type="text/css" href="styles/style.css" />
{section name=entry loop=$css}
	<style type="text/css">	@import "{$css[entry]|escape}"; </style>
{/section}
<!--[if lt IE 7]>
    <style type="text/css">
		@import "styles/ie6.css";
    </style>
<![endif]-->
<!--[if IE]>
    <style type="text/css">
		@import "styles/ie_all.css";
    </style>
<![endif]-->
<!--[if lt IE 6]>
	<style type="text/css">
		@import "styles/ie5.css";
	</style>
<![endif]-->
{foreach item=set key=condition from=$css_ie}
	<!--[if {$condition}]>
		<style type="text/css">
			{foreach item=item  from=$set}
				@import "{$item|escape}";
			{/foreach}
		</style>
	<![endif]-->
{/foreach}
</head>
<body{if !$_module} class="leafLogin"{/if}>
	<div style="display: none;">
		{php}
			include('images/icons/svgs.svg');
		{/php}
	</div>
	{if !isset($smarty.get.single_module) && $menu}
		<nav class="mainMenu">
			<ul class="mainMenu__primary">
				{foreach from=$menu item=moduleItem}
					<li class="mainMenu__item{if $moduleItem.isActive || $smarty.get.module == $moduleItem.module_name} mainMenu__item--active{/if}">
						<a href="?module={$moduleItem.module_name|escape}{if $moduleItem.isGroup}&amp;submenuGroup={$moduleItem.groupName|escape}{/if}">
							<img src="{$_module->getMenuIcon($moduleItem.module_name, $moduleItem.groupName)}" alt="" />
							{if $moduleItem.isGroup}
								{alias code="`$moduleItem.module_name`:`$moduleItem.groupName`" context="admin:moduleNames"}
							{else}
								{$moduleItem.name|escape}
							{/if}
						</a>
					</li>
				{/foreach}
			</ul>

			<ul class="mainMenu__userMenu">
				{if $profileModuleName}
					<li class="mainMenu__item mainMenu__item--small{if $smarty.get.module == $profileModuleName} mainMenu__item--active{/if}">
						<a href="?module={$profileModuleName|rawurlencode|escape}">
							<img src="{$smarty.const.ADMIN_WWW}images/profile.png" alt="" />
						</a>
					</li>
                {/if}
                <li class="mainMenu__item mainMenu__item--small mainMenu__item--logout">
					<a href="?leafDeauthorize=1">
						<img src="{$smarty.const.ADMIN_WWW}images/logout.png" alt="" />
					</a>
				</li>
			</ul>
		</nav>
	{/if}

	{if isset($smarty.get.single_module)}
		{$module}
	{else}
		<div id="mainLeafContent">
			{$module}
		</div>
	{/if}

	{foreach from=$js item=src}
		<script type="text/javascript" src="{$src|escape}"></script>
	{/foreach}

	{foreach item=set key=condition from=$js_ie}
		<!--[if {$condition}]>
			{foreach item=item  from=$set}
				<script type="text/javascript" src="{$item|escape}"></script>
			{/foreach}
		<![endif]-->
	{/foreach}
</body>
</html>
