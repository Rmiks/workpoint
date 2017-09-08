<header class="pageHeader">
    <div class="container">

        {assign var=languages value=$menu->getLanguages()}
        {if count($languages) > 1}
            <ul class="block languageMenu">
                {foreach item=item from=$languages name=languages}
                    <li{if $item.active} class="active"{/if}>
                        <a href="{$item.object_id|orp|escape}{if $item.path_part}{$item.path_part|escape}{/if}{if $item.get_query}?{$item.get_query|escape}{/if}">{$item.name|escape}</a>
                    </li>
                {/foreach}
            </ul>
        {/if}

        <div class="menu-toggle js-menu-btn">
            <button class="menu-toggle__button">
                <span class="menu-toggle__line"></span>
            </button>
            <div class="menu-toggle__text menu-toggle__text--open">
                {alias code="menu-open"}
            </div>
            <div class="menu-toggle__text menu-toggle__text--close">
                {alias code="menu-close"}
            </div>
        </div>

        <nav class="mainMenu" role="navigation">
            {include file="blocks/menu.tpl" menu=$menu->getMainMenu()}
        </nav>

    </div>
</header>