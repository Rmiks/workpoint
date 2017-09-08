{assign var=moduleClass value=$_module|get_class}
{alias_context code="admin:`$moduleClass`"}
{alias_fallback_context code="admin"}

<div class="thead">
    <div>
        <span>{include file=$_module->pathTo('_all.orderBy') column=date}</span>
        <span>{include file=$_module->pathTo('_all.orderBy') column=name}</span>
        <span>{include file=$_module->pathTo('_all.orderBy') column=active}</span>
    </div>
</div>

<div class="tbody sortables">

    {if sizeof($collection)}

        {foreach item=item name=list from=$collection}
            <a id="row-{$item->id}" href="{include file=$_module->pathTo('all._url')}">

                <span>{$item->date|escape}</span>
                <span>{$item->name|escape}</span>
                <span>
                    {if $item->active}
                        <img src="images/icons/accept.png" alt="accept" />
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