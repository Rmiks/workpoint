{assign var=moduleClass value=$_module|get_class}
{alias_context code="admin:`$moduleClass`"}
{alias_fallback_context code="admin:leafBaseModule"}

<div class="thead">
    <div>
        <span></span>
        <span>{include file=$_module->pathTo('_all.orderBy') column="id"}</span>
        <span>{include file=$_module->pathTo('_all.orderBy') column="name"}</span>
        <span>{include file=$_module->pathTo('_all.orderBy') column="category_id"}</span>
        <span>{include file=$_module->pathTo('_all.orderBy') column="outofstock"}</span>
    </div>
</div>
<div class="tbody sortables">

    {if count($collection) > 0}
        {foreach from=$collection item=item name=name}
            <a id="row-{$item->id}" href="{include file=$_module->pathTo('all._url')}">
                <span> </span>
                <span>{$item->id}</span>
                <span>{$item->name}</span>
                <span>{$item->category->name}</span>
                <span>
                    {if $item->outofstock}
                        <img alt="{alias code=outofstock}" title="{alias code=outofstock}" src="images/icons/tick.png" />
                    {else}
                        <img alt="{alias code=instock}" title="{alias code=instock}" src="images/icons/bullet_black.png" />
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