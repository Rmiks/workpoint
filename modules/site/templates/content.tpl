<!DOCTYPE html>
<html lang="{$properties.language_code|escape}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{foreach item=item from=$menu->getTitle()}{$item.name|escape} : {/foreach} {$rootVars.siteTitle|escape}</title>
    <meta name="description" content="{$metaDescription|strip_tags|truncate:'500'|escape|trim}">

    <meta property="og:description" content="{$metaDescription|strip_tags|truncate:'500'|escape|trim}">
    <meta property="og:image" content="{$metaImage|escape}"/>
    <meta property="og:title" content="{$rootVars.siteTitle|escape}"/>
    <meta property="og:type" content="website"/>
    <meta property="og:url" content="{$smarty.const.WWW}"/>

    <meta name="keywords" content="{$metaKeywords|escape}">
    <meta name="author"
          content="{if $smarty.const.DEVELOPED_BY == "cubesystems"}Cube Systems / www.cubesystems.lv{else}Cube / www.cube.lv{/if}">
    <meta name="Robots" content="index,follow">
    <base href="{$smarty.const.WWW|escape}"/>
    <!--[if IE]></base><![endif]-->

    {if $smarty.const.PRODUCTION_SERVER}
        <style>
            {$aboveTheFoldCSS}
        </style>
    {else}
        <link rel="stylesheet" type="text/css" href="styles/abovethefold.css"/>
    {/if}

    {foreach from='rss'|leaf_get item = item}
        <link rel="alternate" type="application/rss+xml" title="{$item.title|escape}" href="{$item.url|escape}"/>
    {/foreach}

    <script>
        var STORE       = {ldelim}{rdelim};
        STORE.styleHash = '{$styleHash}';
        STORE.fontsHash = '{$fontsHash}';
    </script>


    {include file="bundle/css.tpl"}


    <link rel="stylesheet" type="text/css" {if !isset($smarty.get.print)} media="print"{/if} href="styles/print.css"/>

    {if $properties.googleAnalyticsId}
        {include file="bundle/analytics.tpl"}
    {/if}

    <link rel="shortcut icon" href="{$smarty.const.WWW|escape}favicon.ico"/>
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" href="/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="/favicon-16x16.png" sizes="16x16">
    <link rel="manifest" href="/manifest.json">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="theme-color" content="#ffffff">
</head>
<body class="{$properties.language_code|escape} body-{$openedObject->object_data.template|stringtolatin:true:true|escape}">

<script>
    document.body.className += ' js';
</script>

{*
<div style="display: none;">
    {php}
        include(PATH . 'images/svg.svg');
    {/php}
</div>
*}

<div class="skipLinks">
    <a href="{request_url}#content">{alias code=skipLinks}</a>
</div>

{include file="blocks/header.tpl"}

<div class="mainContainer" id="mainContainer">

    <main id="content">
        {$openedObject->content}
    </main>

</div>

{include file="blocks/footer.tpl"}

<script src="js/index.{if $smarty.const.PRODUCTION_SERVER}{$jsHash}.{/if}js" async></script>

{if $smarty.const.ENABLE_BROWSERSYNC}
    <script id="__bs_script__">//<![CDATA[
        document.write( "<script async src='http://HOST:3000/browser-sync/browser-sync-client.js?v=2.18.2'><\/script>".replace( "HOST", location.hostname ) );
        //]]></script>
{/if}

</body>
</html>