{assign var=moduleClass value=$_module|get_class}
{alias_context code="admin:`$moduleClass`"}
{alias_fallback_context code="admin:leafBaseModule"}

<div class="thead">
    <div>
        <span>{include file=$_module->pathTo('_all.orderBy') column="name"}</span>
        <span>{include file=$_module->pathTo('_all.orderBy') column="countryCode"}</span>
        <span>{include file=$_module->pathTo('_all.orderBy') column="currencyCode"}</span>
        <span>{include file=$_module->pathTo('_all.orderBy') column="active"}</span>
    </div>
</div>
<div class="tbody">

    {if count($collection) > 0}
        {foreach from=$collection item=item name=name}
            <a id="row-{$item->id}" href="{include file=$_module->pathTo('all._url')}">
                <span>{$item->name}</span>
                <span>{$item->countryCode}</span>
                <span>{$item->currencyCode}</span>
                <span>
                    {if $item->active}
                        <img alt="{alias code=active}" title="{alias code=active}" src="images/icons/tick.png" />
                    {else}
                        <img alt="{alias code=inactive}" title="{alias code=inactive}" src="images/icons/bullet_black.png" />
                    {/if}
                </span>               
            </a>
        {/foreach}
    {else}

        <div class="unselectable">
            {alias code="nothingFound"}
        </div>

    {/if}
</div>