{alias_context code="admin:contentObjects"}
{if $error_code}
<div style="color:#f00; font-size:130%; font-weight:bold; text-align:center; margin:100px 0 0 0; ">
    {if $error_code == "unallowed template"}
    <span title="{if $parent}{$parent->object_data.template|escape} -&gt; {/if}{$_object->object_data.template|escape}">{alias code=template_not_allowed}</span>
    {else}
    {alias code=unknown_error}
    {/if}
</div>
{else}
<form enctype="multipart/form-data" method="post"
action="{$_object->save_url|escape}" id="objectEditForm"
class="pure-form pure-form-stacked editForm {if $_object->areSnapshotsEnabled()}snapshotsEnabled{/if}
{if $smarty.cookies.showContentNodeRelationPanel} showRelationPanel{/if}"
>
<div class="content noShadow">
    {if $_object->object_data.id == 0}
    <input type="hidden" name="parent_id" value="{$_object->object_data.parent_id|escape}" />
    <input type="hidden" name="_leaf_object_type" value="{$_object->object_data.type|escape}" />
    {if !empty($smarty.get.seedRelationId)}
    <input type="hidden" name="seedRelationId" value="{$smarty.get.seedRelationId|escape}"/>
    {/if}
    {/if}
    <div class="globalFieldContainer collapsableSection pure-g {if $smarty.cookies.colapseSection_globalFieldContainer}collapsed{/if}">
        <div class="templateField objectNameContainer nameWrap pure-u-1 pure-u-md-1-2">
            <div class="pure-u-23-24">
                <label for="name" title="ID: {$_object->object_data.id|escape}">
                    {alias context="admin:contentObjects" code=name}
                </label>
                <input id="name" name="name" type="text" class="pure-input-1" value="{$_object->object_data.name|escape:'html'}" />
            </div>
        </div>
        {if $_object->object_type == 22}
        <div class="templateField templateWrap pure-u-1 pure-u-md-1-2">
            <div class="pure-u-23-24">
                <label for="template" title="{$template|escape}">{alias context="admin:contentObjects" code=template}</label>
                {if $_object->_config.change_templates}
                <select id="template" name="template" onchange="change_template(this)" title="{$template|escape}"
                class="pure-input-1" >
                {html_options options=$templates selected=$template}
            </select>
            {else}
            {$templates[$template]}
            <input type="hidden" id="template" name="template" value="{$template|escape}" />
            {/if}
        </div>
    </div>

    {/if}

    {if $_object->areSnapshotsEnabled()}

    {assign var=snapshots value=$_object->getSnapshots()}
    {if $snapshots && count($snapshots)}
    <div class="templateField snapshotsWrap pure-u-1 pure-u-md-1-2">
        <div class="pure-u-23-24">
            <label for="snapshot">{alias context="admin:contentObjects" code=snapshot}:</label>

            {assign var=currentSnapshot value=$_object->getCurrentSnapshot()}
            <select id="snapshot" name="snapshot" onchange="loadSnapshot(this)">
                {foreach item=snapshot from=$snapshots name=snapshots}

                {if (($_object->loadedSnapshot) && ($_object->loadedSnapshot->id == $snapshot->id))}
                {assign var=isSelectedSnapshot value=true}
                {else}
                {assign var=isSelectedSnapshot value=false}
                {/if}

                {if $currentSnapshot && $currentSnapshot->id == $snapshot->id}
                {assign var=isCurrentSnapshot value=true}
                {else}
                {assign var=isCurrentSnapshot value=false}
                {/if}

                <option value="{$snapshot->id|escape}"{if $isSelectedSnapshot} selected="selected"{/if}>{if $smarty.foreach.snapshots.first}{alias code=latestSnapshot}{else}{$snapshot->createdAt|escape}{/if}</option>
                {/foreach}
            </select>
        </div>
    </div>
    {/if}

    {/if}

    <div class="clear"></div>
    <div class="templateField rewriteNameWrap pure-u-1 pure-u-md-1-2">
        <div class="pure-u-23-24">
            <label for="rewrite_name">{alias context="admin:contentObjects" code=rewrite_name}</label>
            <div class="pure-input-btn">
                <input type="text" id="rewrite_name" onchange="updateUrlPart()" name="rewrite_name"
                value="{$rewrite_name|escape}" class="pure-input-1" />
                <button type="button" onclick="suggest_rewrite()" class="pure-btn pure-btn-icon" title="{alias code=suggest_rewrite context='admin:contentObjects'}">
                    <svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#search"></use></svg>
                </button>
            </div>
            <p class="pure-input-info pull-right error cf duplicateRewriteNameWarning">
                {alias code=duplicateRewriteName context=validation}
            </p>
            <div class="objectUrlPartContainer">
                {if $parentUrl}
                {strip}

                {assign var=urlParts value=$parentUrl|@parse_url}
                <a href="{$parentUrl|escape}{if $rewrite_name}{$rewrite_name|escape}{elseif $id}{$id|escape}{/if}/">

                    {if $urlParts.scheme && $urlParts.host}
                    <span class="host">{$urlParts.scheme|escape}://{$urlParts.host|escape}</span>
                    {/if}
                    <span class="ancestor">{$urlParts.path|escape}</span>
                    <span class="hidden" style="display:none;">/../</span>
                    <span class="objectUrlPart">{if $rewrite_name}{$rewrite_name|escape}{elseif $id}{$id|escape}{/if}</span>/
                </a>

                {/strip}
                {/if}
            </div>
        </div>
    </div>
    <div class="templateField orderNoWrap pure-u-1 pure-u-md-1-2">
        <div class="pure-u-23-24">
            <label for="order_nr">{alias context="admin:contentObjects" code=order}</label>
            <select id="order_nr" name="order_nr" class="pure-input-1" >
                {foreach item = option key = key from = $order_select}
                <option value="{$key|escape}" {if $order_nr==$key} selected="selected"{/if}>
                    {alias context="admin:contentObjects" code=$option.alias} {$option.name|mb_truncate:"UTF8":88:"..."|escape:"html"}
                </option>
                {/foreach}
            </select>
        </div>
    </div>

    <div class="clear"></div>

    <div class="sideLabel visibleWrap  pure-u-1 pure-u-md-1-2">
        <div class="pure-u-23-24">
            <label class="pure-cbox">
            <input
            onchange="document.getElementById('visible').value=(this.checked ? 1 : 0)"
            name="visible_box" id="visible_box" type="checkbox" value="1"
            {if $visible || $_object->new_object} checked="checked"{/if}
            />
            <input id="visible" type="hidden" name="visible" value="{if $_object->new_object}1{else}{$visible|escape}{/if}" />
            <span><svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#checkbox_check"></use></svg></span>
            {alias context="admin:contentObjects" code=visible}
            </label>
        </div>
    </div>
    <div class="sideLabel protectedWrap  pure-u-1 pure-u-md-1-2">
        <div class="pure-u-23-24">
            <label class="pure-cbox">
                <input
                onchange="document.getElementById('protected').value=(this.checked ? 1 : 0)"
                name="protected_box" id="protected_box" type="checkbox" value="1"
                {if $protected} checked="checked"{/if}
                />
                <input id="protected" type="hidden" name="protected" value="{$protected|intval}" />
                <span><svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#checkbox_check"></use></svg></span>
                {alias context="admin:contentObjects" code=protected}
            </label>
        </div>
    </div>

    {*<button class="noStyling toggleSection collapse" type="button" title="{alias code=collapseSection}">
        <img src="images/icons/130.png" alt="{alias code=collapseSection}" />
    </button>
    <button class="noStyling toggleSection expand" type="button" title="{alias code=expandSection}">
        <img src="images/icons/129.png" alt="{alias code=expandSection}" />
    </button>*}


    <div class="clear persistent"></div>

</div>
{include file = edit.tpl}
</div>

<div class="content relationsPanel" data-confirmCopy="{alias code=confirmCopy}">
    <div class="panelTitle">{alias code=relatedNodes}</div>
    <div class="body">
        <img src="images/loader.gif" class="loader" alt="{alias code=loading}" />
    </div>
</div>

<div class="footer">
    <div class="left btn-panel">
        <button type="submit" class="iconAndText btn-panel_btn ">
            {alias code=save}
        </button>

        {if $id}
        <a href="{$_object->object_data.id|orp}" class=" iconAndText btn-panel_btn " >
            {alias code=preview} (ID: {$_object->object_data.id|escape})
        </a>
        {/if}

        <button class="toggleRelationPanelButton btn-panel_btn btn-panel_btn--right" type="button" title="{alias code=toggleRelationPanel}">
            {alias code="relatedPages"}
        </button>

    </div>
</div>

<input type="hidden" name="postCompleted" value="1" />
</form>
<script type="text/javascript">
//<![CDATA[
var objectId = {$id};
//]]>
</script>
{/if}
