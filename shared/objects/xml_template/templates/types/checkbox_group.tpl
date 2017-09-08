<fieldset>
{foreach from = $field.properties.options key = option_value item = option_name name = options}
<div class="sideLabel">
    <label for="{$field.input_id|escape}_{$smarty.foreach.options.iteration}" class="pure-cbox">
    <input id="{$field.input_id|escape}_{$smarty.foreach.options.iteration}" {if in_array($option_value, (array) $field.value)}checked="checked"{/if} type="checkbox" name="{$field.input_name|escape}[]" value="{$option_value|escape}" />
    <span><svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#checkbox_check"></use></svg></span>
    {$option_name|escape}</label>
</div>
{/foreach}
</fieldset>
