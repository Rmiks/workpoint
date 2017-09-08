<form class="validatable edit pure-form" method="post" action="{$_module->getModuleUrl()}&amp;do=save&amp;id={$item->id}" enctype="multipart/form-data">
	{include file=$_module->pathTo('_edit.header')}
	<div class="content">
		<div class="pure-g">
			{include file=$_module->pathTo('_edit.input') name='name' alias='groupsName'}
			{include file=$_module->pathTo('_edit.select') type=enum translateOptions=false name=default_module options=$moduleNames showEmptyValue=false}
		</div>

        <div class="groupTable leafTable  alternateRows alias-search-table">
            <div class="thead">

                    <span class="groupTable__col"></span>
                    <span class="groupTable__col"><label for="disableAll"><input type="radio" name="setAll" value="0" id="disableAll" /> {alias code="disable"}</label></span>
                    <span class="groupTable__col"><label for="enableAll"><input type="radio" name="setAll" value="1" id="enableAll" /> {alias code="enable"}</label></span>
            </div>
            <div class="tbody">
                {foreach from=$modules item=module}
                    <a>
                        <span class="groupTable__col">{alias code=$module.name context="admin:moduleNames"}</span>

                        {foreach item=process from=$module.processes}
                            {if $process.id != 1}
                                </a><a>
                                <span class="nameColumn">{$process.name}</span>
                            {/if}
                            <span class="groupTable__col">
                                <label><input type="radio" name="configurations[{$module.name}][process][{$process.id}]" {if !$process.value}checked="checked"{/if} value="0"> {alias code=disabled}</label>
                            </span>
                            <span class="groupTable__col">
                                <label><input type="radio" name="configurations[{$module.name}][process][{$process.id}]" value="1" {if $process.value}checked="checked"{/if}> {alias code=enabled}</label>
                            </span>
                            </a>
                        {/foreach}
                        {foreach from=$module.configurations item=config}
                            <a>
                                <span class="groupTable__col">
                                    {if $config.description}{$config.description}{else}{$config.name}{/if}
                                </span>
                                <span class="groupTable__col">
                                    {if $config.type=='boolean'}
                                        <select name="configurations[{$module.name}][config][{$config.name}]">
                                            {if $smarty.get.target_group}<option value="">Inherit from parent</option>{/if}
                                            <option {if $config.value=='1'} selected="selected" {/if} value="1">Yes</option>
                                            <option {if !$config.value} selected="selected" {/if} value="0">No</option>
                                        </select>
                                    {elseif $config.type=='select'}
                                        <select name="configurations[{$module.name}][config][{$config.name}]">
                                            {if $smart.get.target_group}<option value="">Inherit from parent</option>{/if}
                                            {html_options options=$config.values selected=$config.value}
                                        </select>
                                    {elseif $config.type=='hidden'}
                                        <input type="hidden" value="{$config.value}" name="configurations[{$module.name}][config][{$config.name}]" />
                                    {else}
                                        <input type="text" value="{$config.value}" name="configurations[{$module.name}][config][{$config.name}]" />
                                    {/if}
                                </span>
                            </a>
                        {/foreach}
                    </a>
                {/foreach}
            </div>
        </div>
	</div>

	{include file=$_module->pathTo('_edit.footer')}
</form>