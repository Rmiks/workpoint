{alias_context code=$aliasContext}
<div class="date-field-wrap {if $autoInit === false} no-auto-init{/if} {$className}">
	{strip}
        <div class="pure-input-btn">
        	{if $useNormalized == true}
        		<input name="{$name|escape}" {if $id}id="{$id|escape}"{/if} type="text" class="normalized " value="{$value|escape}" />
        		<input name="localizedFor-{$name|escape}" {if $id}id="localizedFor-{$id|escape}"{/if} type="text" class="date localized" value="" onfocus="if( jQuery(this).hasClass('hasDatepicker') != true ) initDatepicker(this, true);" />
        	{else}
        		<input name="{$name|escape}" {if $id}id="{$id|escape}"{/if} type="text" class="date" value="{$value|escape}" onfocus="if( jQuery(this).hasClass('hasDatepicker') != true ) initDatepicker(this, true);" />
        	{/if}
        	<button type="button" class="pure-btn pure-btn-icon">
        		{if $removeButtonImage != true}
        			{if $buttonImage}
        				<img src="{$buttonImage|escape}" alt="{alias code="datePickDate"}" />
        			{else}
        				<span><svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#kalendars"></use></svg></span>
        			{/if}
        		{/if}
        		{$buttonText|escape}
        	</button>
        </div>
	{/strip}

    {if !$format}
        {assign var=languageCode value="properties"|leaf_get:"language_code"}
        {assign var=format value="properties"|leaf_get:"datePickerFormats":$languageCode}
    {/if}

	{if $format}
		<span class="format" style="display: none;">{$format|escape}</span>
	{/if}
</div>
