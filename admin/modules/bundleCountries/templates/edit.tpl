{assign var=moduleClass value=$_module|get_class}
{alias_context code="admin:`$moduleClass`"}
{alias_fallback_context code="admin:leafBaseModule"}
<input type="hidden" name="moduleUrl" value="{$_module->getModuleUrl()|escape}" />

<form class="validatable edit pure-form pure-form-stacked" method="post" action="{$_module->getModuleUrl()}&amp;do=save&amp;id={$item->id}" enctype="multipart/form-data">
    {include file=$_module->pathTo('_edit.header')}
    <div class="tabbedContent ui-tabs">

        <ul class="tabs ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
            <li class="ui-state-default ui-corner-top ui-state-active"><a href="#common">{alias code=basic}</a></li>
            {if $shippings|@sizeof}
                <li class="ui-state-default ui-corner-top"><a href="#shippings_tab">{alias code=shippings}</a></li>
            {/if}
        </ul>

        <div class="content">

            <div id="common">
                <div class="section pure-g">
                    {if !$item->id}
                        {assign var=default_val value=1}
                    {/if}

                    {include file=$_module->pathTo('_edit.input') name=active alias=active type=checkbox checked=$default_val}

                    {include file=$_module->pathTo('_edit.input') name=name}
                    {include file=$_module->pathTo('_edit.input') name=countryCode}
                    {include file=$_module->pathTo('_edit.input') name=currencyCode}
                </div>
            </div>
        </div>
    </div>

    {include file=$_module->pathTo('_edit.footer')}
</form>
