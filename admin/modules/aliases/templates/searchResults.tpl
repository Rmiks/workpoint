<div class="panelLayout module-aliases method-edit">
    <div class="secondaryPanel">
        {include file = "list.tpl"}
    </div>
    <div class="primaryPanel">
        <div class="leafTable  alternateRows alias-search-table">
            <div class="thead">
                <div>
                    <span>{alias code=searchTranslationsHeading}{if isset($smarty.get.filter)} ({$smarty.get.filter|escape}){/if}</span>
                </div>
            </div>
            <div class="tbody ">
                {foreach from=$aliases item=item key=key}
                    <a href="{$_module->header_string|escape}&do=edit&id={$item.group_id|escape}#translation_{$key|escape}">
                        <span> <strong>{$item.name|escape}</strong>{if $item.translation|escape} ({$item.translation|escape}){/if}
                            -
                        <small>{$item.context|escape}/{$item.groupName|escape}</small></span>
                    </a>
                {/foreach}
            </div>
        </div>
    </div>
</div>