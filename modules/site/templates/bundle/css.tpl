{if $smarty.const.PRODUCTION_SERVER}
    <script>
        {$assetCacherJs}
    </script>
    <noscript>
        <link rel="stylesheet" type="text/css"
              href="styles/fonts.{$fontsHash}.css"/>
        <link rel="stylesheet" type="text/css"
              href="styles/style.{$styleHash}.css"/>
    </noscript>
{else}
    <script>
        {literal}
        function _stylesLoaded() {
            STORE.stylesLoaded = true;
        }
        {/literal}
    </script>
    <link rel="stylesheet" type="text/css" href="styles/fonts.css"/>
    <link onload="_stylesLoaded()" rel="stylesheet" type="text/css" href="styles/style.css"/>
{/if}