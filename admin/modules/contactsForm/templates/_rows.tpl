{assign var=moduleClass value=$_module|get_class}
{alias_context code="admin:`$moduleClass`"}
{alias_fallback_context code="admin:leafBaseModule"}

<div class="thead">
    <div>
        <span></span>
        <span>{include file=$_module->pathTo('_all.orderBy') column="id"}</span>
        <span>{include file=$_module->pathTo('_all.orderBy') column="add_date"}</span>
        <span>{include file=$_module->pathTo('_all.orderBy') column="name"}</span>
        <span>{include file=$_module->pathTo('_all.orderBy') column="company"}</span>
        <span>{include file=$_module->pathTo('_all.orderBy') column="email"}</span>
    </div>
</div>
<div class="tbody">

    {if count($collection) > 0}
        {foreach from=$collection item=item name=name}
            <a id="row-{$item->id}" href="{include file=$_module->pathTo('all._url')}">
                <span> </span>
                <span>{$item->id}</span>
                <span>{$item->add_date}</span>
                <span>{$item->name}</span>
                <span>{$item->company}</span>
                <span>{$item->email}</span>
            </a>
        {/foreach}
    {else}

        <div class="unselectable">
            {alias code="nothingFound"}
        </div>

    {/if}
</div>