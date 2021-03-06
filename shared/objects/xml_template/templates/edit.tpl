<script type="text/javascript">
//<![CDATA[
var query_string = '?{$query_string|escape:javascript}';
//]]>
</script>
{if $fieldTypes.richtext}
<script language="javascript" type="text/javascript">
//<![CDATA[
{literal}
var tinyMCEConfig;
var leafTinyOldOnload = window.onload;
window.onload = function()
{
    if (typeof leafTinyOldOnload == 'function')
    {
         leafTinyOldOnload();
    }
    initLeafTiny();
}
function initLeafTiny()
{
    var leafStyleClassFilter = function(v, ov)
    {
        if (!tinyMCEConfig.class_filter_names)
        {
            return v;
        }

        var filterNames = '|'.concat( tinyMCEConfig.class_filter_names, '|');
        var needle = '|'.concat(v, '|');
        if (filterNames.indexOf(needle) != -1)
        {
            return;
        }
        return v;
    }

	tinyMCEConfig =
	{/literal}
		{$config.properties|@json_encode}
	{literal};
	tinyMCEConfig.class_filter = leafStyleClassFilter;



	// ticket #32, Jānis Grigaļuns
	function getStyle( domNode, styleProp, iframe )
	{
		if (domNode.currentStyle)
		{
			var y = domNode.currentStyle[styleProp];
			if( !y && styleProp == 'float' ) // IE uses "styleFloat"
			{
				y = domNode.currentStyle['styleFloat'];
			}
		}
		else if (iframe && iframe.win.getComputedStyle)
		{
			var y = iframe.doc.defaultView.getComputedStyle(domNode,null).getPropertyValue(styleProp);
		}
		else if (window.getComputedStyle)
		{
			var y = document.defaultView.getComputedStyle(domNode,null).getPropertyValue(styleProp);
		}
		return y;
	}

	tinyMCEConfig.setup = function( editor )
	{
        editor.onInit.add( function( editor )
        {
    		$(".richtextField").css("visibility","visible");

			// richtext focus effect
			tinymce.dom.Event.add
			(
				editor.settings.content_editable ? editor.getBody() : (tinymce.isGecko ? editor.getDoc() : editor.getWin()), 'focus', function()
				{
					jQuery( '#' + editor.editorContainer ).children('.mceLayout').addClass('focus');
				}
			);
			tinymce.dom.Event.add
			(
				editor.settings.content_editable ? editor.getBody() : (tinymce.isGecko ? editor.getDoc() : editor.getWin()), 'blur', function()
				{
					jQuery( '#' + editor.editorContainer ).children('.mceLayout').removeClass('focus');
					tinyMCE.triggerSave(); // update textarea contents
				}
			);

        });

		// image class names need to be fixed when getting the content from the editor before saving the template
        //
        // since onBeforeGetContent may fire multiple times during the initialization phase of the editor (when textFormat.css is not yet loaded)
        // only bind the handler after the first activation of the editor
        var onBeforeGetContentAdded = false;
		editor.onActivate.add( function( editor )
		{
            if (onBeforeGetContentAdded)
            {
                return;
            }

			editor.onBeforeGetContent.add( function(editor, event)
			{
				// image classes
				jQuery( editor.contentDocument ).find('img').each(function()
				{
					// prototype converts dom nodes to arrays
					// this should be removed when prototype is gone
					if( this.length === 0 )
					{
						return;
					}
					// prototype/jquery collision
					if( this[0] )
					{
						var domNode = this[0];
					}
					else
					{
						var domNode = this;
					}
					// attach classes
					var item = jQuery( domNode );
					if( getStyle( domNode, 'float', editor.dom ) == 'left' )
					{
						item.addClass('contentImageLeft');
					}
					else
					{
						item.removeClass('contentImageLeft');
					}

					if( getStyle( domNode, 'float', editor.dom ) == 'right' )
					{
						item.addClass('contentImageRight');
					}
					else
					{
						item.removeClass('contentImageRight');
					}
				});
			});

            onBeforeGetContentAdded = true;

		});
	}
	//-- ticket #32
	tinyMCE.init(tinyMCEConfig);
{/literal}
}
//]]>
</script>
{/if}
<div class="pure-g">
{foreach from = $fields item=field}

    {assign var=col_size value="pure-u-1 pure-u-md-1-2"}
    {assign var=input_size value="pure-u-23-24"}
    {if $field.type == 'richtext' ||
        $field.type == 'array' ||
        $field.type == 'google_map_point'
    }
        {assign var=col_size value="pure-u-1 pure-u-md-1"}
        {assign var=input_size value="pure-u-1 pure-u-md-1"}
    {/if}

	<fieldset class="templateField {$field.type|escape}Field {$col_size}">

		{if $field.type != 'hidden'}
			<label for="{$field.input_id|escape}">
				{strip}
					{if $field.properties.description}
						{$field.properties.description|escape}
					{elseif $label_context || $field.properties.label_context}
						{if $field.properties.label_context}
							{assign var=label_alias_context value=$field.properties.label_context}
						{else}
							{assign var=label_alias_context value=$label_context}
						{/if}

						{if $field.properties.label_alias}
							{assign var=label_alias_code value=$field.properties.label_alias}
						{else}
							{assign var=label_alias_code value=$field.name}
						{/if}

						{if $label_language}
							{alias code=$label_alias_code context=$label_alias_context language=$label_language}
						{else}
							{alias code=$label_alias_code context=$label_alias_context}
						{/if}
					{else}
						{$field.name|escape}
					{/if}
				{/strip}
			</label>
		{/if}


		{if $field.type == "array"}
			{strip}
				<span style="display:block;">
					<button type="button" onclick="add_array_item('{$field.input_id|escape:javascript|escape}')">Add item</button>
				</span>
			{/strip}
		{/if}

		{assign var=fieldTemplate value=$_object->getFieldTemplate($field)}
		{include file=$fieldTemplate className=$input_size}

		{if $field.type == "array"}
			<button type="button" class="secondary-button" onclick="add_array_item('{$field.input_id|escape:javascript|escape}')">Add item</button>
		{/if}

	</fieldset>
{/foreach}


{* GROUPS *}
{foreach from=$groups item=group}
	<div class="pure-g templateFieldGroup">

		{if $group.description}
			<h3 class="pure-u-1 templateFieldGroup__title">{$group.description|escape}</h3>
		{elseif $label_context || $group.label_context}
			{if $group.label_context}
				{assign var=label_alias_context value=$group.label_context}
			{else}
				{assign var=label_alias_context value=$label_context}
			{/if}

			{if $group.label_alias}
				{assign var=label_alias_code value=$group.label_alias}
			{else}
				{assign var=label_alias_code value=$group.name}
			{/if}

			{if $label_language}
				<h3 class="pure-u-1 templateFieldGroup__title">{alias code=$label_alias_code context=$label_alias_context language=$label_language}</h3>
			{else}
				<h3 class="pure-u-1 templateFieldGroup__title">{alias code=$label_alias_code context=$label_alias_context}</h3>
			{/if}
		{else}
			<h3 class="pure-u-1 templateFieldGroup__title">{$group.name|escape}</h3>
		{/if}

		{foreach from=$group.fields item=field}

		    {assign var=col_size value="pure-u-1 pure-u-md-1-2"}
		    {assign var=input_size value="pure-u-23-24"}
		    {if $field.type == 'richtext' ||
		        $field.type == 'array' ||
		        $field.type == 'google_map_point'
		    }
		        {assign var=col_size value="pure-u-1 pure-u-md-1"}
		        {assign var=input_size value="pure-u-1 pure-u-md-1"}
		    {/if}

			<fieldset class="templateField {$field.type|escape}Field {$col_size}">

				{if $field.type != 'hidden'}
					<label for="{$field.input_id|escape}">
						{strip}
							{if $field.properties.description}
								{$field.properties.description|escape}
							{elseif $label_context || $field.properties.label_context}
								{if $field.properties.label_context}
									{assign var=label_alias_context value=$field.properties.label_context}
								{else}
									{assign var=label_alias_context value=$label_context}
								{/if}

								{if $field.properties.label_alias}
									{assign var=label_alias_code value=$field.properties.label_alias}
								{else}
									{assign var=label_alias_code value=$field.name}
								{/if}

								{if $label_language}
									{alias code=$label_alias_code context=$label_alias_context language=$label_language}
								{else}
									{alias code=$label_alias_code context=$label_alias_context}
								{/if}
							{else}
								{$field.name|escape}
							{/if}
						{/strip}
					</label>
				{/if}

				{if $field.type == "array"}
					{strip}
						<span style="display:block;">
							<button type="button" onclick="add_array_item('{$field.input_id|escape:javascript|escape}')">Add item</button>
						</span>
					{/strip}
				{/if}

				{assign var=fieldTemplate value=$_object->getFieldTemplate($field)}
				{include file=$fieldTemplate className=$input_size}

				{if $field.type == "array"}
					<button type="button" class="secondary-button" onclick="add_array_item('{$field.input_id|escape:javascript|escape}')">Add item</button>
				{/if}

			</fieldset>

		{/foreach}

	</div>
{/foreach}

</div>

{foreach from = $_object->_editIncludes item = _edit_template}
{include file = $_edit_template}
{/foreach}
