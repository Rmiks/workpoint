{php}$this->assign('languageList', leafLanguage::getLanguages());{/php}
<div class="field i18nInput
    {if $type=='richtext'}
        textFieldContainer richtextField pure-u-1
    {else}
        {if $fieldWrapClass}{$fieldWrapClass}{else}pure-u-1 pure-u-md-1-2{/if}
    {/if}
    ">
    <div class="field_content {if $type=='richtext'}pure-u-1 content-space{else}pure-u-23-24{/if}">

        <div class="labelWrap">
            <label for="{$name|escape}{$namespace}">
                {assign var=aliasString value=$name}
                {if $alias}
                    {assign var=aliasString value=$alias}
                {/if}
                {if $aliasContext}
                    {alias code=$aliasString context=$aliasContext}
                {else}
                    {alias code=$aliasString}
                {/if}
            </label>
        </div>

        <div class="value">
            <div class="i18nWrap">
                {strip}
                    {if count($languageList) > 1}
                        <div class="languageWrap pull-right">
                            <select class="languageWrap_select" name="">
                                {foreach from=$languageList item=language name=i18nLanguages}
                                    <option value="{$language->code|escape}"
                                            {if $smarty.foreach.i18nLanguages.iteration==1}selected="selected"{/if}
                                    >
                                        {$language->code|escape}
                                    </option>
                                {/foreach}

                            </select>
                        </div>
                    {/if}
                {/strip}
                <div class="inputWrap">
                    {foreach from=$languageList item=language name=i18nInputs}
                        {if $propertyName}
                            {assign var=value value=$item->getI18NValue($propertyName, $language->code)}
                        {elseif is_object($item)}
                            {assign var=value value=$item->getI18NValue($name, $language->code)}
                        {/if}
                        {assign var=i18nName value="i18n:`$language->code`:`$name`"}
                        {if $type=="textarea" || $type=="richtext"}
                            <div class="input"
                                 data-language="{$language->code|escape}"
                                 {if $smarty.foreach.i18nInputs.iteration!=1}style="display:none"{/if}
                            >
    							<textarea
                                        name="{$i18nName|escape}"
                                        id="{$i18nName|escape}{$namespace}"
                                        class="{if $type=='richtext'}richtextInput{/if} {$className}"
                                        {if $readonly}readonly="readonly"{/if}
                                        {if $disabled}disabled="disabled"{/if}
                                        cols="50" rows="5"
                                >{if isset($value)}{$value|escape}{else}{$item->$i18nName|escape}{/if}</textarea>
                            </div>
                        {elseif $type=="file"}
                            <div class="input"
                                 data-language="{$language->code|escape}"
                                 {if $smarty.foreach.i18nInputs.iteration!=1}style="display:none"{/if}
                            >
                                {if $name && $objectProperty}
                                    {assign var=fileObject value=$item->getI18nValue($objectProperty,$language->code)}
                                    {if $fileObject instanceof leafFile}
                                        {assign var=previewLink value=$fileObject->getFullUrl()}
                                    {/if}
                                {/if}
                                {leafFileInput accept=$accept name=$i18nName id="`$i18nName``$namespace`" file=$value previewLink=$previewLink}{/leafFileInput}
                            </div>
                        {elseif $type=="objectlink"}
                            <div class="input"
                                 data-language="{$language->code|escape}"
                                 {if $smarty.foreach.i18nInputs.iteration!=1}style="display:none"{/if}
                            >
                                {input type=objectlink value=$value name=$i18nName id="`$i18nName``$namespace`"}
                            </div>
                        {elseif $type=="slug"}
                            <div class="pure-input-btn slugInput"
                                 data-language="{$language->code|escape}"
                                 {if $smarty.foreach.i18nInputs.iteration!=1}style="display:none"{/if}
                            >
                                <input
                                        type="text"
                                        name="{$i18nName|escape}"
                                        id="{$i18nName|escape}{$namespace}"
                                        class="input pure-u-1 {$className}"
                                        value="{if isset($value)}{$value|escape}{else}{$item->$i18nName|escape}{/if}"
                                        data-was="{if $value}filled{else}empty{/if}"
                                        data-language="{$language->code|escape}"
                                        {if $readonly}readonly="readonly"{/if}
                                        {if $disabled}disabled="disabled"{/if}
                                        {if isset($autocomplete) && $autocomplete==false}autocomplete="off"{/if}
                                />
                                <button class="pure-btn pure-btn-icon"
                                        onclick="rewriteI18nSlug('{$language->code|escape}', 'input')"
                                        type="button"
                                >
                                    <svg>
                                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#search"></use>
                                    </svg>
                                </button>
                            </div>
                        {elseif $type eq "checkbox"}
                            <input
                                    type="checkbox"
                                    name="{$i18nName|escape}"
                                    id="{$i18nName|escape}{$namespace}"
                                    class="input pure-u-1 {$className}"
                                    {if $value}checked{/if}
                                    value="1"
                                    data-language="{$language->code|escape}"
                                    {if $readonly}readonly="readonly"{/if}
                                    {if $disabled}disabled="disabled"{/if}
                                    {if $smarty.foreach.i18nInputs.iteration!=1}style="display:none"{/if}
                            />
                        {else}
                            <input
                                    type="{if $type}{$type|escape}{else}text{/if}"
                                    name="{$i18nName|escape}"
                                    id="{$i18nName|escape}{$namespace}"
                                    class="input pure-u-1 {$className}"
                                    value="{if isset($value)}{$value|escape}{else}{$item->$i18nName|escape}{/if}"
                                    data-language="{$language->code|escape}"
                                    {if $readonly}readonly="readonly"{/if}
                                    {if $disabled}disabled="disabled"{/if}
                                    {if isset($autocomplete) && $autocomplete==false}autocomplete="off"{/if}
                                    {if $smarty.foreach.i18nInputs.iteration!=1}style="display:none"{/if}
                            />
                        {/if}

                    {/foreach}
                    {if $type=="slug"}
                        <script>
                            var slugifyThis = "{if $watch}{$watch|escape}{else}name{/if}";
                        </script>
                    {/if}
                </div>

            </div>
            <div class="pure-input-info cf">
                {if $descriptionAlias}
                    <div class="description">
                        {alias code=$descriptionAlias vars=$descriptionAliasVars}
                    </div>
                {/if}
                {if $postAlias}<span class="post">{alias code=$postAlias}</span>{/if}
                {if $post}<span class="post">{$post|escape}</span>{/if}
            </div>
        </div>
    </div>
</div>
