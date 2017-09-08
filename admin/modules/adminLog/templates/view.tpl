{assign var=moduleClass value=$_module|get_class}
{alias_context code="admin:`$moduleClass`"}
{alias_fallback_context code="admin"}

<div class="errors ">
    <div class="content view">
        <table class="leafTable errorDetails labelFirstColumn" cellspacing="0" cellpadding="0">
            {foreach from=$item->getDefinitionArr() item=defProps key=definition name=loop}
            <tr {if $smarty.foreach.loop.index is even} class="alternate" {/if}>
                <th>
                    {$definition}
                </th>
                <td>
                    {if $item->is_serialized($item->$definition)}
                    <div class="preWrap">
                        <pre>{$item->$definition|@unserialize|@print_r|escape}</pre>
                    </div>
                    {else}
                    {$item->$definition|escape}
                    {/if}
                </td>
            </tr>
            {/foreach}
        </table>
    </div>
</div>
{include file=$_module->pathTo('_view.footer')}
