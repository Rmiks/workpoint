{if is_array($field.value)}
    {assign var=currentLat value=$field.value.lat}
    {assign var=currentLng value=$field.value.lng}
{else}
    {assign var=currentLat value=""}
    {assign var=currentLng value=""}
{/if}

{if !empty($field.properties.defaultZoom)}
    {assign var=defaultZoom value=$field.properties.defaultZoom}
{else}
    {assign var=defaultZoom value="13"}
{/if}

{if !empty($field.properties.centerLat)}
    {assign var=centerLat value=$field.properties.centerLat}
{else}
    {assign var=centerLat value="56.94725473000847"}
{/if}

{if !empty($field.properties.centerLng)}
    {assign var=centerLng value=$field.properties.centerLng}
{else}
    {assign var=centerLng value="24.099142639160167"}
{/if}


{if !empty($field.properties.width)}
    {assign var=width value=$field.properties.width}
{else}
    {assign var=width value=""}
{/if}

{if !empty($field.properties.height)}
    {assign var=height value=$field.properties.height}
{else}
    {assign var=height value=""}
{/if}
{*
{if (!empty($field.properties.googleMapsKey))}
    {assign var=googleMapsKey value=$field.properties.googleMapsKey}
{elseif $smarty.const.googleMapsKey}
    {assign var=googleMapsKey value=$smarty.const.googleMapsKey}
{else}
    {assign var=googleMapsKey value=false}
{/if}
*}

{*
{if !$googleMapsKey}
    <div> GOOGLE MAPS KEY MISSING. </div>
{else}
*}
<div class="{$className} content-space">

    <div id="mapCanvas_{$field.input_id|escape}" class="googleMapContainer" style="{if $width}width:{$width}px;{else}width: 100%;{/if}
        {if $height}height:{$height}px;{/if}"></div>
    <div id="mapSearchPanel" style="">
        <input class="address" type="text" value="" placeholder="search" id="googleMapSearch_{$field.input_id|escape}">
    </div>
    <div class="pure-u-1 pure-md-1 pure-input-btn">
        <input class="coords" type="text" id="{$field.input_id|escape}" name="{$field.input_name|escape}" value="{$currentLat|escape};{$currentLng|escape}" />
        <button type="button" id="googleMapClearButton_{$field.input_id|escape}">{alias code=clear context="admin"}</button>
    </div>


    <script type="text/javascript">

        var mapField = {ldelim}
            'mapCanvas' : "{$field.input_id|escape:javascript}",
            'vars' :
            {ldelim}
                'centerLat'   :  {$centerLat|escape:javascript},
                'centerLng'   :  {$centerLng|escape:javascript},
                'defaultZoom' :  {$defaultZoom|escape:javascript},
            {rdelim}
        {rdelim};

        {if !empty($currentLat)}
        mapField.vars.lat = '{$currentLat|escape:javascript}';
        {/if}

        {if !empty($currentLng)}
        mapField.vars.lng = '{$currentLng|escape:javascript}';
        {/if}

        if (typeof googleMapPointFields == 'undefined')
        {ldelim}
            var googleMapPointFields = [];
        {rdelim}

        googleMapPointFields[googleMapPointFields.length] = mapField;

    </script>
</div>

{*{/if}*}
