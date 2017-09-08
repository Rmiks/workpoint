<form class="filterForm pure-form" action="">
    <input type="hidden" name="module" value="{$_module|get_class}"/>
    <input type="hidden" name="do" value="{if $smarty.get.do}{$smarty.get.do}{else}all{/if}"/>
    <div class="special-field-wrap">
        {strip}
            <select name="filterCategory">
                {foreach from=$_module->options.categories item=cats}
                    <option {if $cats->id eq $smarty.get.filterCategory}selected="selected"{/if} value="{$cats->id}">{$cats->name|escape}</option>
                {/foreach}
            </select>
        {/strip}
    </div>
</form>
