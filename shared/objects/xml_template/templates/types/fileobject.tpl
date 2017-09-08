<div class="{$className}">
    <div class="pure-u-1">
        <div class="pure-u-1-2 pure-u-md-3-5">
            <input
                type="file" name="{$field.input_id|escape}_file"
                onchange="{if $field.properties.allowedExtensions}checkUploadFileExtension(this, '{$field.properties.allowedExtensions|escape:javascript|escape}');{/if}updateLinkedFieldsSwitch(this, '{$field.input_id|escape:javascript|escape}', '{$field.input_name|escape:javascript|escape}');"
                id="{$field.input_id|escape}_file"
                data-single-caption='File "[filename]" selected'
                data-multiple-caption="[count] files selected"
                class="pure-file"
            />
            <label for="{$field.input_id|escape}_file" class="pure-u-1 pull-left fileobjectField__input-label">{alias code=chooseFile context="admin"}</label>
        </div>
        <div class="pure-u-1-2 pure-u-md-2-5 pure-input-btn pull-right cf">
            <input
                onkeyup="correctObjectLink(this);jQuery(this).change();"
                onmouseover="correctObjectLink(this);jQuery(this).change();"
                type="text"
                name="{$field.input_name|escape}"
                class="short fileobject"
                id="{$field.input_name|escape}"
                value="{$field.value|escape}"
            />
            {if $field.file}
                <button type="button" class="fileobjectField__remove pure-btn pure-btn-icon" title="{alias code=delete context="admin:content"}">
                    <input class="fileobjectField__confirmMessage" type="hidden" value="{alias code=delete_confirmation context="admin"}">
                    <svg>
                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#trash"></use>
                    </svg>
                </button>
            {/if}
        </div>
    </div>
    <div class="pure-u-1">
        {if $field.properties.dialog}
            {input type=filepicker for=$field.input_name}
        {/if}

        {if $field.linked_fields && !$field.properties.local_import}
        <label for="{$field.input_id|escape}_update_linked" id="{$field.input_id|escape}_update_linked_switch" style="display: none">
            <input type="checkbox" name="{$field.input_id|escape}_update_linked" id="{$field.input_id|escape}_update_linked" value="1" />
            {alias code=update_linked_fields context="admin:contentObjects"}
        </label>
        {/if}

        {if $field.properties.local_import && $field.properties.local_import_options}
        <div>
            <label for="{$field.input_id|escape}_local_import">Uploaded file: </label>
            <select name="{$field.input_id|escape}_local_import" id="{$field.input_id|escape}_local_import">
                <option value="">-</option>
                {foreach item=fileName from=$field.properties.local_import_options}
                <option value="{$fileName|escape}">{$fileName|escape}</option>
                {/foreach}
            </select>
        </div>
        {/if}

        {if $field.file}
        <div class="fileobjectField__content pure-file-preview">

            {if in_array($field.file.extension, array('jpg', 'png', 'gif'))}
                <div class="pure-file-preview_img-wrap pull-left">
                    <a target="_blank" href="{$field.file.file_www|escape}{$field.file.file_name|escape}" class='pure-file-preview_link'>
                        <img src="{$field.file.file_www|escape}{if $field.file.extra_info.thumbnail_size}thumb_{/if}{$field.file.file_name|escape}"
                        alt="" class="pure-file-preview_img"/>
                    </a>
                </div>
            {elseif $field.file.extension == 'swf'}
                <div>
                    <object type="application/x-shockwave-flash" data="{$field.file.file_www|escape}{$field.file.file_name|escape}" width="{$field.file.extra_info.image_width|escape}" height="{$field.file.extra_info.image_height|escape}" >
                        <param name="quality" value="high" />
                        <param name="bgcolor" value="#FFFFFF" />
                        <param name="wmode" value="transparent" />
                        <param name="movie" value="{$field.file.file_www|escape}{$field.file.file_name|escape}" />
                    </object>
                </div>
            {/if}
            <div class="pure-file-preview_info">
                <a target="_blank" href="{$field.file.file_www|escape}{$field.file.file_name|escape}" class="filename">
                    {$field.file.name|escape}
                    <i class="pull-right cf">
                        {if $field.file.extra_info.image_width && $field.file.extra_info.image_height}
                            ( {$field.file.extra_info.image_width} x {$field.file.extra_info.image_height} px )
                        {/if}
                    </i>
                    <span style='display:none;'>({$field.file.file_name|escape})<span>
                </a>
            </div>
        </div>
        {/if}
    </div>
</div>
