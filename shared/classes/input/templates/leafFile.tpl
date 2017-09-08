{alias_context code=leafFile}
<div class="leafFile-field-wrap{if !empty($file) && $file != "-1"} field-has-leafFile{/if}">
    <input type="hidden" name="{$name|escape}{if $multiple}[{$index|escape}]{/if}"{if $id} id="{$id|escape}"{/if} value="{$file|default:-1}" class="leafFile-id-field" />
    <input type="hidden" class="leafFile-remove-confirmation-field"
        value="{if isset($removeConfirmationMessage)}
                    {$removeConfirmationMessage}
                {else}
                    {alias code=confirmationRemoveFile}
                {/if}" />
    <div class="pure-u-1">
        <div class="pure-u-4-5">
            <input type="file" name="{$name|escape}{$customSuffix|default:$inputFieldSuffix|escape}{if $multiple}[{if !$selectMultiple}{$index|escape}{/if}]{/if}" {if $id}id="{$id|escape}{$inputFieldSuffix|escape}"{/if} class="file pure-file" />
            <label class="pure-u-1 pull-left" for="{$id|escape}{$inputFieldSuffix|escape}">{alias code=chooseFile}</label>
        </div>
        <div class="pure-u-1-5 pull-right">
            <button type="button" class="removeFileButton pure-btn pure-btn-icon pure-u-1" title="{$removeLabel}">
                {capture assign=removeLabel}{if isset($removeFileLabel)}{$removeFileLabel}{elseif $removeFileAlias}{alias code=$removeFileAlias context=$removeFileAliasContext}{else}{alias code=removeFile}{/if}{/capture}
                {if $buttonImage}
                    <img src="{$buttonImage|escape}" alt="{$removeLabel}" />
                {elseif $buttonText}
                    {$removeLabel}
                {else}
                    <svg>
                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#trash"></use>
                    </svg>
                {/if}
            </button>
        </div>
    </div>
    {if $fileInstance}
        <div class="pure-u-1 pure-file-preview">
            <div class="pure-file-preview_img-wrap pull-left">
                <a target="_blank" href="{$fileInstance->getUrl()}" class='pure-file-preview_link'>
                    <img src="{$fileInstance->getUrl()}" alt="{$fileInstance->getFileName()|escape}" class="pure-file-preview_img"
                    onError="this.onerror=null;this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode);" />
                    {* TODO: Find a better way for image/file type detection and presentation *}
                </a>
            </div>
            <div class="pure-file-preview_info">
                <a target="_blank" href="{$fileInstance->getUrl()}" class="filename">
                    {$fileInstance->getFileName()|escape}
                </a>
            </div>
        </div>
    {/if}
    {$content}
</div>
