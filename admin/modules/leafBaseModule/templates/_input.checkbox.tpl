<div class="field {$fieldWrapClass} pure-u-1 pure-u-md-1-2">
    <div class="pure-u-23-24">
        <div class="labelWrap">
            <label for="{$name|escape}{$namespace}">
                {if $alias}
                    {alias code=$alias}:
                {else}
                    {alias code=$name}:
                {/if}
            </label>
            {if $descriptionAlias}
                <div class="description">
                    {alias code=$descriptionAlias}
                </div>
            {/if}
        </div>
        <label class="pure-cbox">
            <input type="checkbox"
                   name="{$name|escape}"
                   id="{$name|escape}{$namespace}"
                   value="1"
                   class="{$className}"
                {if $item->$name || (!$item->id && $defaultValue)} checked="checked"{/if}
            />
            <span><svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#checkbox_check"></use></svg></span>
        </label>

    </div>
</div>
