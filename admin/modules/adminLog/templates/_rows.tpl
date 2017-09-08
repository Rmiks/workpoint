
{assign var=moduleClass value=$_module|get_class}
{alias_context code="admin:`$moduleClass`"}
{alias_fallback_context code="admin"}

<div class="thead">
    <div>
        <span>{include file=$_module->pathTo('_all.orderBy') column=user_name}</span>
        <span>{include file=$_module->pathTo('_all.orderBy') column=request_time}</span>
        <span>{include file=$_module->pathTo('_all.orderBy') column=module_name}</span>
        <span>{include file=$_module->pathTo('_all.orderBy') column=action_name}</span>
        <span>{include file=$_module->pathTo('_all.orderBy') column=object_title}</span>
        <span>{include file=$_module->pathTo('_all.orderBy') column=object_url}</span>
    </div>
</div>

<div class="tbody sortables">
    {if sizeof($collection)}
    {foreach item=item name=list from=$collection}
    <a class="rowLink" data-id="{$item->id}" id="row-{$item->id}" href="{include file=$_module->pathTo('all._url')}" >
        <span>{$item->user_name|escape}</span>
        <span>{$item->request_time|escape}</span>
        <span>{alias code=$item->module_name|escape context="admin:moduleNames"}</span>
        <span>{alias code=$item->action_name|escape context="admin:logActions"}</span>
        <span>{$item->object_title|escape}</span>
        <span>{$item->object_url|escape}</span>
    </a>
    {/foreach}
    {else}
    <div class="unselectable">
        {alias code="nothingFound"}
    </div>
    {/if}
</div>
