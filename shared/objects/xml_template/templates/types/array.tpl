{if !$node_get}
{if $field.properties.count}({$field.properties.count|escape}){/if}
<ul class="block array content-space " id="{$field.input_id|escape}">
    {assign var = "arrayId" value = $field.input_id}
    {/if}
    {if $field.value}
    {foreach from = $field.value item=array_item name = array_items}
    <li class="arrayItem itemNr{if $node_get_nr}{$node_get_nr|escape}{else}{$smarty.foreach.array_items.iteration}{/if}">
        <div class="arrayItemH ">
            <a class="delHref pure-btn pure-btn-icon" href="#" onclick="item_delete_href(this); return false;">
                <svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#trash"></use></svg>
            </a>
            <span class="drag pure-btn pure-btn-icon">
                <svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#parvietot"></use></svg>
            </span>
        </div>
        {strip}
        {foreach from=$array_item item=array_item}
        {assign var = "field" value = $array_item}

        {assign var=col_size value="pure-u-1 pure-u-md-1-2"}
        {assign var=input_size value="pure-u-23-24"}
        {if $field.type == 'richtext' || $field.type == 'array' ||
            $field.type == 'google_map_point'}
            {assign var=col_size value="pure-u-1 pure-u-md-1"}
            {assign var=input_size value="pure-u-1 pure-u-md-1"}
        {/if}

        <div class="templateField {$field.type|escape}Field {$col_size}">
            {if $field.type != 'hidden'}
            <span class="{$input_size}">
                <label for="{$field.input_id|escape}" class="fieldName">
                    {if $field.properties.description}
                        {$field.properties.description|escape}
                        {else}
                        {$field.name|escape}
                    {/if}
                </label>
            </span>
            {/if}
            {include file = "types/`$field.type`.tpl" className=$input_size}
        </div>
        {/foreach}
        {/strip}
    </li>
    {/foreach}
    {/if}
    {if !$node_get}
</ul>
{/if}
