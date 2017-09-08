<div class="field {$fieldWrapClass} pure-u-1 pure-u-md-1-2">
    <div class="pure-u-23-24">
        <div class="labelWrap">
            <label for="{$name|escape}{$namespace}">
                {assign var=aliasString value=$name}
                {if $alias}
                    {assign var=aliasString value=$alias}
                {/if}
                {if $aliasContext}
                    {alias code=$aliasString context=$aliasContext}:
                {else}
                    {alias code=$aliasString}:{if $required} *{/if}
                {/if}
            </label>
            {if $descriptionAlias}
                <div class="description">
                    {alias code=$descriptionAlias}
                </div>
            {/if}
        </div>
        <div class="value">
        	<script>
				var slugifyThis = "{if $watch}{$watch|escape}{else}name{/if}";
			</script>
			<div class="pure-input-btn">
                <input class="pure-u-1"
                       type="text"
                       name="{$name|escape}" id="{$name|escape}{$namespace}" class="{$className}"
                       value="{$item->$name|escape}"
                       {if !$item->name}data-was="empty"{/if}
                       {if $readonly}readonly="readonly"{/if}
                       {if $disabled}disabled="disabled"{/if}
                />
				<button class="pure-btn pure-btn-icon"
					onclick="rewriteSlug('input')"
					type="button"
				>
					<svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#search"></use></svg>
				</button>
			</div>
        </div>
    </div>
</div>