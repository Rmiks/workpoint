{assign var=moduleClass value=$_module|get_class}
{alias_context code="admin:`$moduleClass`"}
{alias_fallback_context code="admin"}

<form action="{request_url remove="listUrl&returnUrl" add="do=save"}" method="post" enctype="multipart/form-data"
      class="edit validatable pure-form pure-form-stacked">

    {include file=$_module->pathTo('_edit.header')}
    <div class="content">
        {* MAIN INFO *}
        <div id="main-info">
            {include file=$_module->pathTo('_input.checkbox') name="active"}
            <div class="section pure-g">
                {include file=$_module->pathTo('_edit.input') name="name"}
                {include file=$_module->useWidget('rewrite') name=slug}
            </div>
        </div>

    </div>
    {include file=$_module->pathTo('_edit.footer')}
</form>
