{assign var=moduleClass value=$_module|get_class}
{alias_context code="admin:`$moduleClass`"}
{alias_fallback_context code="admin:leafBaseModule"}

<div class="thead">
    <div>
        <span>{include file=$_module->pathTo('_all.orderBy') column="name"}</span>
        <span>{include file=$_module->pathTo('_all.orderBy') column="active"}</span>
        <span>{include file=$_module->pathTo('_all.orderBy') column="add_date"}</span>
    </div>
</div>
<div class="tbody">
    {if count($collection) > 0}
        {foreach from=$collection item=item name=name}
            <a id="row-{$item->id}" href="{include file=$_module->pathTo('all._url')}">
                <span class="cell">{$item->name}</span>
                <span>{if $item->active}<img src="images/icons/accept.png" alt="active"/>{/if}</span>
                <span class="cell">{$item->add_date}</span>
            </a>
        {/foreach}
    {else}
        <div class="unselectable">
            {alias code="nothingFound"}
        </div>
    {/if}
</div>