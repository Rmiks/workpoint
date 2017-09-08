{include file=$_module->pathTo('_edit.header')}

<div class="content view">
	<div class="section">
		{include file=$_module->pathTo('_view.field') name=name}
		{include file=$_module->pathTo('_view.field') name=default_module}
	</div>
    <div class="groupTable leafTable  alternateRows alias-search-table">
        <div class="thead">
            <span class="groupTable__col"></span>
            <span class="groupTable__col">{alias code="disabled"}</span>
            <span class="groupTable__col">{alias code="enabled"}</span>
        </div>
        <div class="tbody">
            {foreach from=$modules item=module}
            <a>
                <span class="groupTable__col">
                    {alias code=$module.name context="admin:moduleNames"}
                </span>
            {foreach item=process from=$module.processes}
                {if $process.id != 1}
                    </a><a>
                        <span class="groupTable__col">{$process.name}</span>
                {/if}
                    <span class="groupTable__col">{if !$process.value}<img src="images/icons/tick.png" alt="" />{else}-{/if}</span>
                    <span class="groupTable__col">{if $process.value}<img src="images/icons/tick.png" alt="" />{else}-{/if}</span>
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
        {/foreach}
        </table>
	</div>
</div>
</div>
{include file=$_module->pathTo('_view.footer')}
