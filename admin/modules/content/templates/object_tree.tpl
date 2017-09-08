{assign var=space value=" "}

{strip}
<ul class="secondaryPanel__menu" id="childs{$root_id|escape}">
	{defun name=recursion list=$objects}
		{assign var=name value=$list.0.id}
		{foreach from=$list item=object name=$name}
			<li class="
					secondaryPanel__menuItem

					{if $object.allowed}
						{$space}secondaryPanel__menuItem--allowed
					{/if}

					{if $object.id == $smarty.get.object_id}
						{$space}secondaryPanel__menuItem--active
					{/if}

					{if !$object.visible}
						{$space}secondaryPanel__menuItem--hidden
					{/if}

					{if !$object.childs}
						{$space}secondaryPanel__menuItem--collapsed
					{/if}
				" id="objectli{$object.id|escape}"
			>
				<p class="secondaryPanel__title">
					{if !$dialog}
						<span class="secondaryPanel__checkbox">
							<input type="checkbox" name="objects[]" value="{$object.id|escape}" id="checkbox{$object.id|escape}" {if !$object.allowed || !$object.allDescendantsAllowed} disabled="disabled" title="{alias code=operationsNotPermitted context=admin:objectAccess}"{/if} />
							<label class="objectCheck{if !$object.allowed} objectCheckDisabled{/if}" for="checkbox{$object.id|escape}">
								<svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#checkbox_check"></use></svg>
							</label>
						</span>
					{/if}

					{if $object.group_image}
						<span class="secondaryPanel__expandBox toggleChildren groupBox{if $object.group_image == 'open'}Open{else}Close{/if}"{if $object.group_image} data-id="{$object.id|escape}"{if $dialog} data-dialog="{$dialog|escape}"{/if}{/if}>
							<svg class="secondaryPanel__expand toggleChildren"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#arrow_up"></use></svg>
						</span>
					{else}
						<span class="secondaryPanel__expandBox"></span>
					{/if}


					{if $dialog}
				      {assign var=href value="#"}
				    {else}
				      {assign var=href value="?module=content&do=edit_object&object_id=`$object.id`"}
				    {/if}

                    <a href="{$href|escape}" {if !$object.allowed}onclick="alert(jQuery('input.messageAccessDeniedTreeClick').val());return false;"{elseif $dialog}onclick="{$dialog|escape}('{$object.id|escape:javascript|escape}')"{/if} id="object_{$object.id|escape}" title="{$object.name|escape:"html"}">
                    	<img class="secondaryPanel__icon {if $object.iconIsFlag}flag{/if}" src="{$object.icon_path|escape}" alt="" />
						{if !$object.allowed}
							<img class="iconOverlay" src="images/icons/notAllowed.png" alt="" />
						{/if}
                    	{$object.name|mb_truncate:"UTF8":22:"..."|escape:"html"}
                    </a>

                    {if !$dialog && $object.allowed}
						<a onclick="return start_panel(this, {$object.id|escape:javascript|escape})" href="?module=content&amp;do=get_context_menu&amp;group_id={$object.id|escape}">
							<svg class="secondaryPanel__add"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#plus"></use></svg>
						</a>
					{/if}
                </p>
                
				




                {*
				<div class="template-{$object.template|stringtolatin:true:true|escape}">
					{if !$dialog && $object.allowed}
						<a class="objectPanelHref" onclick="return start_panel(this, {$object.id|escape:javascript|escape})" href="?module=content&amp;do=get_context_menu&amp;group_id={$object.id|escape}">
							<img src="modules/content/img/add.png" alt="" />
						</a>
					{/if}
					{if !$dialog}
						<label class="objectCheck{if !$object.allowed} objectCheckDisabled{/if}">
							<input type="checkbox" name="objects[]" value="{$object.id|escape}" {if !$object.allowed || !$object.allDescendantsAllowed} disabled="disabled" title="{alias code=operationsNotPermitted context=admin:objectAccess}"{/if} />
						</label>
					{/if}
					{if $object.group_image}
						<button class="toggleChildren groupBox groupBox{if $object.group_image == 'open'}Open{else}Close{/if}" type="button" data-id="{$object.id|escape}" {if $dialog}data-dialog="{$dialog|escape}"{/if}></button>
					{else}
						<span class="groupBox"><!-- --></span>
					{/if}
					<span class="objectHref">
					    {if $dialog}
					      {assign var=href value="#"}
					    {else}
					      {assign var=href value="?module=content&do=edit_object&object_id=`$object.id`"}
					    {/if}
						<a href="{$href|escape}" {if !$object.allowed}onclick="alert(jQuery('input.messageAccessDeniedTreeClick').val());return false;"{elseif $dialog}onclick="{$dialog|escape}('{$object.id|escape:javascript|escape}')"{/if} id="object_{$object.id|escape}" title="{$object.name|escape:"html"}" class="{if $object.id == $smarty.get.object_id}activeObject{/if} {if !$object.visible}hidden{/if}">
							<img class="noIEPngFix {if $object.iconIsFlag}flag{/if}" src="{$object.icon_path|escape}" alt="" />
							{if !$object.allowed}
							<img class="iconOverlay" src="images/icons/notAllowed.png" alt="" />
							{/if}
							{$object.name|mb_truncate:"UTF8":22:"..."|escape:"html"}
						</a>
					</span>
				</div>
				*}
				







				{if $object.childs}
					<ul class="secondaryPanel__menu" id="childs{$object.id|escape}">
						{fun name=recursion list=$object.childs submenu=true}
					</ul>
				{/if}

			</li>
		{/foreach}
	{/defun}
</ul>
{/strip}