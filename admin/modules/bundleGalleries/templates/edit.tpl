{assign var=moduleClass value=$_module|get_class}
{alias_context code="admin:`$moduleClass`"}
{alias_fallback_context code="admin"}

<form action="{request_url remove="listUrl&returnUrl" add="do=save"}" method="post" enctype="multipart/form-data" class="edit validatable pure-form pure-form-stacked">

    {include file=$_module->pathTo('_edit.header')}

    <div class="tabbedContent ui-tabs">

        <ul class="tabs ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
            <li class="ui-state-default ui-corner-top ui-state-active">
                <a href="#main-info">{alias code=mainInfo}</a>
            </li>
            <li class="ui-state-default ui-corner-top">
                <a href="#images">{alias code=images}</a>
            </li>
        </ul>

        <div class="content">


            {* MAIN INFO *}
            <div id="main-info">
                <div class="section pure-g">
                    {include file=$_module->pathTo('_edit.input') name="name"}
                    {include file=$_module->useWidget('rewrite') name=slug}
                    {include file=$_module->pathTo('_edit.input') type="date" name="date"}
                    {include file=$_module->pathTo('_edit.input') type="leafFile" name="imageId"}
                    {include file=$_module->pathTo('_input.checkbox') name="active"}
                    {include file=$_module->pathTo('_edit.input') type="text" name="metaDescription"}
                    {include file=$_module->pathTo('_edit.textarea') textareaClass="richtextInput" name="text"}
                </div>
            </div>


            {* GALLERY *}
            <div id="images">

                {include file=$_module->pathTo('_edit.input') name='images[]' multiple=true alias=addImages type=file}

                <fieldset class="images">
                    <legend>{alias code=images}</legend>

                    {if sizeof($item->images)}
                        <ul class="images__list sortable" id="images">
                            {foreach item=image name=list from=$item->images}
                                <li class="images__item">
                                    <button class="images__item-delete delete-image" data-action="remove">
                                        <svg class="images__item-deleteIcon">
                                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#trash"></use>
                                        </svg>
                                    </button>
                                    <a class="images__item-url" href="{$image->getImageUrl('file')}" target="_blank">
                                        <span class="images__item-content">
                                            <img src="{$image->getImageUrl('file', 'thumb')}" alt="" title="" />
                                        </span>
                                    </a>
                                    <input type="hidden" name="image-id[]" value="{$image->id}" />
                                </li>
                            {/foreach}
                        </ul>
                    {/if}
                </fieldset>

            </div>

        </div>

    </div>

    {include file=$_module->pathTo('_edit.footer')}

</form>