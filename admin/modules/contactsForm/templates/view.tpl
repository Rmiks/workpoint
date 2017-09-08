{assign var=moduleClass value=$_module|get_class}
{alias_context code="admin:`$moduleClass`"}
{alias_fallback_context code="admin"}

<form action="{request_url remove="listUrl&returnUrl" add="do=save"}" method="post" enctype="multipart/form-data" class="edit validatable">

    {include file=$_module->pathTo('_edit.header')}

    <div class="content">

        <div class="section">
            {include file=$_module->pathTo('_edit.input') name="name"}
            {include file=$_module->pathTo('_edit.input') name="company"}
            {include file=$_module->pathTo('_edit.input') name="email"}
            {include file=$_module->pathTo('_edit.input') name="phone"}
            {include file=$_module->pathTo('_edit.textarea') name="text"}

        </div>

        <div class="section">
            <div class="field">
                <div class="labelWrap"><label>add date:</label></div>
                <div class="value">{$item->add_date}</div>
            </div>
            <div class="field">
                <div class="labelWrap"><label>author ip:</label></div>
                <div class="value">{$item->author_ip}</div>
            </div>



        </div>


    </div>

    {include file=$_module->pathTo('_edit.footer')}

</form>