{if
	$field.properties.default and
	(
		$field.value == '0000-00-00 00:00:00' or
		!$field.value ||
		$field.properties.default == $field.value
	)
}
	{assign var=fieldValueDate value=$field.properties.default|strtotime|date_format:"%Y-%m-%d"}
	{assign var=fieldValueTime value=$field.properties.default|strtotime|date_format:"%H:%M"}
{elseif $field.value}
	{assign var=fieldValueDate value=$field.value|date_format:"%Y-%m-%d"}
	{assign var=fieldValueTime value=$field.value|date_format:"%H:%M"}
{/if}

<div class="pure-datetime-cont {$className}" >
    {strip}
        {input type="date" name=$field.input_name id=$field.input_id value=$fieldValueDate format="yy-mm-dd" className="pure-u-md-3-5"}
        <div class="pure-u-1 pure-u-md-2-5">

            <input type="text" maxlength="5" class="pure-u-1 pure-u-md-3-5 pull-right" name="{$_object->getArrayNamePostfix($field.input_name, "Timefield")}" id="{$field.input_id|escape}Timefield" value="{$fieldValueTime|escape}" />

        </div>
    {/strip}
</div>
