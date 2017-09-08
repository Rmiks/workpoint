{assign var=submenuGroupName value=$_module->getSubmenuGroupName()}
{alias_context code="admin:leafBaseModule:`$submenuGroupName`"} {* TODO: refactor this *}
<div class="panelLayout module-{$_module|get_class} method-{$_module->getCurrentOutputMethod()}">

    {assign var=cookieName value="submenu:collapsed"}
    <nav class="secondaryPanel{if $smarty.cookies.$cookieName} secondaryPanel--collapsed{/if}">
        <div class="secondaryPanel__triggerArea">
            <div class="secondaryPanel__trigger">
                <img src="{$smary.const.ADMIN_WWW}images/secondaryPanel-trigger.png" alt="" />
            </div>
        </div>
        <ul class="secondaryPanel__menu" id="moduleTree">
            {foreach from=$_module->getSubMenu() item=menuSection key=sectionTitle}
                {if $_module->hasEnabledItems($sectionTitle)}
                    {assign var=cookieName value="submenu:`$sectionTitle`"}
                    <li class="secondaryPanel__menuItem{if $_module->isAnyMenuItemActive($menuSection)} secondaryPanel__menuItem--active{/if}{if $smarty.cookies.$cookieName} secondaryPanel__menuItem--collapsed{/if}" data-title="{$sectionTitle}">
                        <p class="secondaryPanel__title">
                            <img class="secondaryPanel__icon" src="{$_module->getMenuIcon($sectionTitle)}" alt="{$sectionTitle}" />
                            {alias code=$sectionTitle context="admin:moduleNames"}
                            <svg class="secondaryPanel__expand"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="{request_url}#arrow_up"></use></svg>
                        </p>
                        <ul class="secondaryPanel__submenu">
                            {foreach from=$menuSection item=item key=title}
                                <li class="secondaryPanel__submenuItem{if $_module->isDisabledMenuItem($title)} secondaryPanel__submenuItem--disabled{/if}{if $_module->isActiveMenuItem($title)} secondaryPanel__submenuItem--active{/if}">
                                        {if is_array($item)}
                                            {if !$item.disabled}
                                                <a href="{$item.url|escape}" >
                                                    {alias code=$title context="admin:moduleNames"}
                                                    {*
                                                    {if $item.badgeHtml}
                                                        {$item.badgeHtml}
                                                    {/if}
                                                    *}
                                                </a>
                                            {/if}
                                        {else}
                                            <a href="{$item|escape}" >
                                                {alias code=$title context="admin:moduleNames"}
                                            </a>
                                        {/if}
                                    </li>
                            {/foreach}
                        </ul>
                    </li>
                {/if}
            {/foreach}
        </ul>
    </nav>

    {assign var=message value=$_module->getMessage()}
	<div class="primaryPanel {if !is_null($message)}hasMessage{/if}">
        <div class="primaryPanel__content">
            {if $message}
                <ul class="block leafMessages">
                    <li class="leafMessage {$message.level}">
                        {if $message.aliasCode}
                            {alias code=$message.aliasCode}
                        {/if}
                    </li>
                </ul>
            {/if}
            {$content}
        </div>
	</div>
</div>
<div class="webkitTestBlock"></div>
