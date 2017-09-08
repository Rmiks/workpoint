{*
    usage in form:

    {include file=$_module->useWidget('googleMapPoint') lngFieldName=lng latFieldName=lat}
    {include file=$_module->useWidget('googleMapPoint') lngFieldName=lng2 latFieldName=lat2 lat=56.94 lng=24.10} //default location provided
*}

{if !$latFieldName}
    {assign var=latFieldName value='lat'}
{/if}
{if !$lngFieldName}
    {assign var=lngFieldName value='lng'}
{/if}
{if $lat}
    {assign var=lat value=$lat}
{else}
    {assign var=lat value=$item->$latFieldName|default:0}
{/if}
{if $lng}
    {assign var=lng value=$lng}
{else}
    {assign var=lng value=$item->$lngFieldName|default:0}
{/if}

<div class="field {$fieldWrapClass} {if $type=="checkbox"}checkboxFieldWrap{/if} pure-u-1 js-gmap-field" data-lng="{$lng}" data-lat="{$lat}">
    <div class="field_content pure-u-1 content-space">
        <div class="labelWrap">
            {if $alias}{alias code=$alias}{else}{alias code=coordinates}{/if}
        </div>
        <div class="value">
            <div class="mapContainer">
                <div class="searchBox">
                    <table>
                        <tr>
                            <td>
                                <input class="pure-u-1 js-gmap-address-input" id="address" type="text" value="">
                            </td>
                            <td>
                                &nbsp;<input type="button" value="{alias code="search"}" class="js-gmap-code-address">
                                <input type="button" value="{alias code="clear"}" class="js-gmap-clear-coordinates">
                            </td>
                        </tr>
                    </table>
                </div>
                <div id="map_canvas" style="width: 100%; height: 300px; margin: 0 auto;"></div>
                <table>
                    <tr>
                        <td>
                            Lat:&nbsp;
                        </td>
                        <td>
                            <input type="text" name="{$latFieldName}" id="address_lat"
                                   value="{$lat}"/>
                        </td>
                        <td>
                            &nbsp;Lng:&nbsp;
                        </td>
                        <td>
                            <input type="text" name="{$lngFieldName}" id="address_lng"
                                   value="{$lng}"/>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        {$postfixedContent}
    </div>
</div>
