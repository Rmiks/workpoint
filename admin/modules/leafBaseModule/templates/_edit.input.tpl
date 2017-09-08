{if $type=="checkbox"}
    <div class="field {if $fieldWrapClass}{$fieldWrapClass}{else}pure-u-1 pure-u-md-1-2{/if} checkboxFieldWrap">
        <div class="pure-u-23-24">

            <div class="labelWrap">
                <span class="label">&nbsp;</span>
            </div>

            <div class="value">

                <label class="pure-cbox">

                    <input
                        type="checkbox"
                        name="{$name|escape}" id="{$name|escape}{$namespace}"
                        class="{$className}"
                        value="{if isset($value)}{$value|escape}{else}1{/if}"
                        {if $readonly}readonly="readonly"{/if}
                        {if $disabled}disabled="disabled"{/if}
                        {if isset($autocomplete) && $autocomplete==false}autocomplete="off"{/if}
                        {if $item->$name || $checked}checked="checked"{/if}
                    />

                    <span>
                        <svg>
                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#checkbox_check"></use>
                        </svg>
                    </span>

                    {if $alias}
                        {alias code=$alias}
                    {else}
                        {alias code=$name}
                    {/if}

                </label>

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
    </div>
{else}
    <div class="field {if $fieldWrapClass}{$fieldWrapClass}{else}pure-u-1 pure-u-md-1-2{/if}">

        <div class="pure-u-23-24">

            <div class="labelWrap">
                {if $type neq "hidden"}
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
                {/if}
            </div>

            <div class="value">

                {if $type == "date"}

                    {if !$value}
                        {assign var=value value=$item->$name}
                    {/if}

                    {if $value=="0000-00-00"}
                        {assign var=value value=""}
                    {/if}

                    {input type=date name=$name value=$value id="`$name``$namespace`" useNormalized=true}

                {else}
                    {if $type == "leafFile"}

                        {leafFileInput accept=$accept name=$name id="`$name``$namespace`" file=$value|default:$item->$name previewLink=$previewLink}{/leafFileInput}

                    {else}

                        <input
                            class="{if $type == 'file'}file pure-file{else}pure-u-1{/if} {$className}"
                            type="{if $type}{$type}{else}text{/if}"
                            name="{$name|escape}" id="{$name|escape}{$namespace}"
                            value="{if isset($value)}{$value|escape}{else}{$item->$name|escape}{/if}"
                            {if $multiple}multiple="true"{/if}
                            {if $readonly}readonly="readonly"{/if}
                            {if $disabled}disabled="disabled"{/if}
                            {if isset($autocomplete) && $autocomplete==false}autocomplete="off"{/if}
                        />

                        {if $type == 'file'}
                            <label class="pure-u-1" for="{$name|escape}{$namespace}">
                                {if $multiple}
                                    {alias code=chooseFiles}
                                {else}
                                    {alias code=chooseFile}
                                {/if}
                            </label>
                        {/if}

                    {/if}

                {/if}

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

    </div>
{/if}
