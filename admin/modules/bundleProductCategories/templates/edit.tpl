{assign var=moduleClass value=$_module|get_class}
{alias_context code="admin:`$moduleClass`"}
{alias_fallback_context code="admin:leafBaseModule"}

<form class="validatable edit pure-form pure-form-stacked" method="post" action="{$_module->getModuleUrl()}&amp;do=save&amp;id={$item->id}" enctype="multipart/form-data">

    {include file=$_module->pathTo('_edit.header')}
    <input type="hidden" name="moduleUrl" value="{$_module->getModuleUrl()|escape}" />

    <div class="content">

        <div class="section pure-g">
            {include file=$_module->pathTo('_edit.input') name=label}
            {include file=$_module->useWidget('i18nInput') type=text name=name}
            {include file=$_module->useWidget('i18nInput') type=richtext name=description}
        </div>

        <div class="section pure-g">
            {include file=$_module->pathTo('_edit.input') name=imageId type="leafFile"}
            {if isset($item->imageId)}
                {assign var=imageUrl value=$item->getImageUrl('image')}
                {if $imageUrl}
                    <div class="imagePreviewBox">
                        <img src="{$imageUrl|escape}" alt="" />
                    </div>
                {/if}
            {/if}
        </div>
    </div>

    {include file=$_module->pathTo('_edit.footer')}
</form>
