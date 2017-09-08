<div class="{$className} ">
    <div class="pure-input-btn">
        <input onkeyup="correctObjectLink(this, false)" onmouseover="correctObjectLink(this, false)"
        type="text"
        name="{$field.input_name|escape}"
        id="{$field.input_id|escape}"
        value="{$field.value|escape}"
        onchange="updateObjectFieldPreview(this);"
        />
        <button style="cursor: pointer;"
            onclick="Leaf.openLinkDialog(this, event, '?module=content&amp;do=object_manager&amp;type=link&amp;target_id={$field.input_id|escape}'); return false;"
            class="pure-btn pure-btn-icon"
            >
            <svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#settings"></use></svg>
        </button>
        <div class="">
            <span class="objectPreview">
                {if $field.preview}
                {include file=objectFieldPreview.tpl}
                {/if}
            </span>
        </div>
</div>
</div>
