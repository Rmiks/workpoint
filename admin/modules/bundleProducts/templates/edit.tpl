{assign var=moduleClass value=$_module|get_class}
{alias_context code="admin:`$moduleClass`"}
{alias_fallback_context code="admin:leafBaseModule"}

<form class="validatable edit pure-form pure-form-stacked" method="post"
      action="{$_module->getModuleUrl()}&amp;do=save&amp;id={$item->id}"
      enctype="multipart/form-data">

    {include file=$_module->pathTo('_edit.header')}
    <input type="hidden" name="moduleUrl" value="{$_module->getModuleUrl()|escape}"/>

    <div class="tabbedContent ui-tabs">
        <ul class="tabs ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
            <li class="ui-state-default ui-corner-top ui-state-active"><a href="#common">{alias code=basic}</a></li>
            <li class="ui-state-default ui-corner-top"><a href="#countries_tab">{alias code=countries}</a></li>
        </ul>
        <div class="content">

            <div id="common">
                <div class="section pure-g">
                    {include file=$_module->pathTo('_edit.input') name=label }
                    {include file=$_module->pathTo('_edit.input') type=checkbox name=outofstock }
                    {include file=$_module->useWidget('i18nInput') type=text name=name }
                    {include file=$_module->useWidget('i18nInput') type=text name=metaDescription }
                    {include file=$_module->useWidget('i18nInput') type=richtext name=description }
                    {include file=$_module->pathTo('_edit.input') name=imageId type="leafFile"}
                    {include file=$_module->pathTo('_edit.select') options=$categories name="categoryId" alias=categoryId nameMethod=getRewriteName}
                </div>
            </div>

            <div id="countries_tab">

                <table cellpadding="0" cellspacing="0" class="leafTable orderDetails">
                    <thead>
                    <tr>
                        <th>{alias code=active}</th>
                        <th>{alias code=country}</th>
                        <th>{alias code=price}</th>
                    </tr>
                    </thead>
                    <tbody>

                    {foreach from=$countries item=countryItem name=list}
                        {assign var=className value=""}
                        {if $smarty.foreach.list.iteration % 2}
                            {assign var=className value="alternate"}
                        {/if}
                        <tr class="orderRow {$className}">
                            <td>
                                <input name="countries[{$countryItem->id}][id]" value="{$countryItem->id}"
                                       type="checkbox"{if in_array($countryItem->id, $currCountriesIds)} checked="checked"{/if} >
                            </td>
                            <td>
                                {$countryItem->name}
                            </td>
                            <td>
                                <input name="countries[{$countryItem->id}][price]"
                                       value="{$item->getLocalizedPrice($countryItem->id)}" type="text"/>
                            </td>
                        </tr>
                    {/foreach}

                    </tbody>
                </table>
            </div>

        </div>

    </div>
    {include file=$_module->pathTo('_edit.footer')}
</form>
