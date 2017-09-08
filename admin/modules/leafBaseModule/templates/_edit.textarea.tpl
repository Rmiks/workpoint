<div class="field pure-u-1 pure-u-md-1-2 {$fieldClass}">
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
        </div>

        {strip}
        <textarea name="{$name|escape}" id="{$name|escape}{$namespace}" class="pure-u-1 {$textareaClass}" rows="5">
            {if isset($value)}{$value|escape}{else}{$item->$name|escape}{/if}
        </textarea>
        {/strip}

        <div class="pure-input-info cf">

            {if $postAlias}
                <span class="post">{alias code=$postAlias}</span>
            {/if}

            {if $post}
                <span class="post">{$post|escape}</span>
            {/if}

            {if $postHtml}
                <span class="post">{$postHtml}</span>
            {/if}

            {if $descriptionAlias}
                <div class="description">
                    {alias code=$descriptionAlias vars=$descriptionAliasVars}
                </div>
            {/if}

        </div>

    </div>
</div>
