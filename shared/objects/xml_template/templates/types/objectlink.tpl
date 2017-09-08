<div class="{$className}">
    <div class="pure-input-btn">
        {strip}
        <input onkeyup="correctObjectLink(this)" onmouseover="correctObjectLink(this)" type="text" name="{$field.input_name|escape}" id="{$field.input_id|escape}" value="{$field.value|escape}" onchange="updateObjectFieldPreview(this);" class="pure-u-1 pure-u-md-3-5"
        />
        <button onclick="Leaf.openLinkDialog(this, event, '?module=content&amp;do=object_manager&amp;type=link&amp;target_id={$field.input_id|escape:javascript|escape}'); return false;" class="pure-btn pure-btn-icon"
            class="pure-btn pure-btn-icon"
        >
            <svg>
                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#settings"></use>
            </svg>
        </button>
        {/strip}
    </div>
    <span class="objectPreview">
        {if $field.preview} {include file=objectFieldPreview.tpl} {/if}
    </span>
</div>
