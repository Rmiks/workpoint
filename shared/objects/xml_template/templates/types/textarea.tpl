<textarea
    {if $field.properties.maxchars} onkeypress="return canAddCharacter(this, {$field.properties.maxchars|escape:javascript})"
    onchange="trimLength(this, {$field.properties.maxchars|escape:javascript})" {/if}
    id="{$field.input_id|escape:javascript}"
    cols="75" rows="5" name="{$field.input_name|escape:javascript}" class="{$className}">{$field.value|escape}</textarea>
