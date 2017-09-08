{if $menu}
    <ul class="block menu {if $children}menu--child{/if}">
        {foreach from=$menu item=item name="menu"}
            <li class="menu__item
                {if $smarty.foreach.menu.first} first{/if}
                {if $smarty.foreach.menu.last} last{/if}
                {if $item->object_data.active} active{/if}
                {if $item->object_data.children} menu__item--has-children js-menu-item-expandable{/if}">
                <a href="{$item|orp|escape}">{$item|escape}</a>
                {include file="blocks/menu.tpl" menu=$item->object_data.children children=true}
            </li>
        {/foreach}
    </ul>
{/if}