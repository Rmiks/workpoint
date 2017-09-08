{strip}
<script type="text/javascript">
    var googleTranslateApiKey = {if $_module->options.googleTranslate.APIKey}'{$_module->options.googleTranslate.APIKey|escape:javascript|escape}'
    {else}null{/if} ;
</script>
<div class="panelLayout module-aliases method-edit">
    <div class="secondaryPanel">
        {include file=list.tpl}
    </div>

    <div class="primaryPanel">
        <div class="primaryPanel__content alias">
            <div class="alias__header">
                <div class="visibleLanguagesSwitch">
                    <button type="button">
                        {alias code=visibleLanguages}
                        <img src="images/expandTool/closeHoverShim.png" alt=""/>
                    </button>

                    <ul class="block">
                        {foreach from=$_module->options.languages key=languageId item=languageName}
                            <li>
                                <label class="pure-cbox">
                                    <input type="checkbox" name="{$languageName|escape}" value="{$languageId|escape}"
                                           {if $_module->isLanguageVisible($languageId)}checked="checked"{/if}/>
                                    <span>
                                    <svg><use xmlns:xlink="http://www.w3.org/1999/xlink"
                                              xlink:href="#checkbox_check"></use></svg>
                                </span>
                                    {$languageName|escape}
                                </label>
                            </li>
                        {/foreach}
                    </ul>

                </div>
            </div>
            <div class="alias__content">
                <form id="editForm" action="{$_module->getModuleUrl()|escape}" method="post">
                    <input type="hidden" name="action" value="save"/>
                    <input type="hidden" name="id" value="{$id|escape}"/>
                    <div class="header padding pure-form pure-form-aligned">
                        <div class="groupInfo pure-g">
                            <div class="pure-u-1-3">
                                <input type="text" name="name" id="name"
                                       value="{$_module->options.groups[$id].name|escape}"
                                       class="{if empty($smarty.get.id)}autofocus {/if} pure-u-23-24"
                                       placeholder="{alias code=group}"/>
                            </div>
                            <div class="pure-u-1-3">
                                <input type="text" name="context" id="context"
                                       value="{$translations.context|escape}" class="pure-u-23-24"
                                       placeholder="{alias code=context}"/>
                            </div>
                        </div>

                        {*  TODO: ADD this headers functionality to new table header
                            <div class="aliasesTable aliasesTableHead">
                                <div class="th codeHeader">{alias code=name}</div>
                                {foreach from=$_module->options.languages key=language_id item=language_name}
                                    <div class="th languageHeader languageId-{$language_id|escape} languageCode-{$language_name|escape} {if in_array($language_id, $translations.languagesWithMachineTranslations)}hasMachineTranslations{/if}" data-languageId="{$language_id|escape}" data-languageCode="{$_module->options.languages.$language_id|escape}"  {if $_module->isLanguageVisible($language_id)==false}style="display:none;"{/if}>

                                        <div class="approveColumnButtonBox">
                                            <button type="button" class="noStyling approveColumn" title="{alias code=approveMachineTranslationForColumn}">
                                                <img src="{$smarty.const.WWW|escape}images/icons/tick.png" alt="" />
                                            </button>
                                        </div>

                                        <span class="name">{$language_name|escape}</span>
                                    </div>
                                {/foreach}
                            </div>
                        *}

                    </div>
                    <div class="content noShadow">
                        {include file=translationsTable.tpl translations=$translations.translations languages=$_module->options.languages}
                        <table id="nodeT">
                            <tr id="cloneme">
                                <td class="translation_name codeColumn">
                                    <input type="text" name="_translations_name" value=""/>
                                    <input type="hidden" name="_translations_type" value="" class="short"/>
                                    <input type="hidden" name="_translations_id" value=""/>
                                </td>
                                {foreach name = languages from=$_module->options.languages key=language_id item=language}
                                    <td class="translationCell languages col_{$language_id|escape} languageId-{$language_id|escape} languageCode-{$language_name|escape} {*{if is_array($expandedLangs)}{if in_array($language_id, $expandedLangs)} expanded{/if}{/if}*}"
                                        data-languageId="{$language_id|escape}"
                                        data-languageCode="{$_module->options.languages.$language_id|escape}"
                                        {if $_module->isLanguageVisible($language_id)==false}style="display:none;"{/if}>
                                        <div class="wrap">
                                            <input type="text" class="translationText"
                                                   name="_translations_lang_{$language_id|escape}" value=""/>
                                        </div>
                                        <input type="hidden" class="machineTranslation"
                                               name="_translations_lang_{$language_id|escape}_machine" value="0"/>
                                    </td>
                                {/foreach}
                                <td class="deleteColumn">
                                    <i class="icon icon--warning" style="cursor:pointer;" onclick="removeEntry(this)">
                                        <svg>
                                            <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                                 xlink:href="{request_url}#trash"></use>
                                        </svg>
                                    </i>
                                </td>
                            </tr>
                        </table>

                        {if $_module->options.googleTranslate.APIKey}
                            <div id="machineTranslationButtonBox" class="machineTranslationButtonBox">
                                <button id="machineTranslationButton" class="noStyling machineTranslationButton"
                                        type="button" tabindex="-1">
                                    <img class="normal" src="{$smarty.const.WWW|escape}images/icons/google.gif" alt=""
                                         title="{alias code=translateWithGoogle}"/>
                                    <img class="notAvailable"
                                         src="{$smarty.const.WWW|escape}images/icons/google-not-available.gif" alt=""
                                         title="{alias code=googleTranslateNotAvailable}"/>
                                </button>
                                <div id="machineTranslationSelector" class="machineTranslationSelector">
                                    <div class="sourceLanguageLabel">
                                        {alias code=translateFrom}
                                    </div>
                                    <div class="sourceLanguages">
                                        {foreach from=$_module->options.languages key=language_id item=language_name}
                                            <div class="sourceLanguage sourceLanguage-{$_module->options.languages.$language_id|escape}">
                                                <button type="button" class="noStyling"
                                                        value="{$_module->options.languages.$language_id|escape}">{$language_name|escape}</button>
                                            </div>
                                        {/foreach}
                                    </div>
                                </div>
                            </div>
                            {if !$_module->options.googleTranslate.disableBatchRequests}
                                <div class="machineColumnTranslationButtonBox machineColumnTranslationButtonBoxTemplate">
                                    <button class="noStyling machineColumnTranslationButton" type="button"
                                            title="{alias code=translateColumnWithGoogle}">
                                        <img class="normal" src="{$smarty.const.WWW|escape}images/icons/google.gif"
                                             alt=""/>
                                    </button>
                                    <div class="machineColumnTranslationSelector">
                                        <div class="sourceLanguageLabel">
                                            {alias code=translateFrom}
                                        </div>
                                        <div class="sourceLanguages">
                                            {foreach from=$_module->options.languages key=language_id item=language_name}
                                                <div class="sourceLanguage sourceLanguage-{$_module->options.languages.$language_id|escape}">
                                                    <button type="button" class="noStyling"
                                                            value="{$_module->options.languages.$language_id|escape}">{$language_name|escape}</button>
                                                </div>
                                            {/foreach}
                                        </div>
                                    </div>
                                </div>
                            {/if}
                        {/if}
                    </div>
                </form>
            </div>
            <div class="alias__footer">
                {if $id}
                    <div id="operationsBox" class="footer operationsBox ">
                        <div class="padding btn-panel">
                            <button type="button" class="btn-panel_btn " onclick="new_variable()" title="Alt+N">
                                {alias code=add}
                            </button>
                            <button class="btn-panel_btn " type="submit" onclick="variables_submit()">
                                {alias code=save}
                            </button>
                            <form class="deleteForm"
                                  data-confirmation="{alias code=deleteConfirmation var_name=$_module->options.groups[$id].name|escape}"
                                  action="{$_module->getModuleUrl()|escape}&amp;do=delete" method="post">
                                <input type="hidden" name="id" value="{$id|escape}"/>
                                <button type="submit" class="btn-panel_btn">
                                    {alias code=delete}
                                </button>
                            </form>
                            <form class="exportForm" method="get" action="./">
                                <input type="hidden" name="module" value="aliases"/>
                                <input type="hidden" name="do" value="export"/>
                                <input type="hidden" name="id" value="{$id|escape}"/>
                                <button type="submit" class="btn-panel_btn">
                                    {alias code=export}
                                </button>
                            </form>
                            <form id="importForm" class="importForm" method="post" action="./?module=aliases"
                                  enctype="multipart/form-data" target="translations">
                                <div class="pure-u-5-5">
                                    <input type="hidden" name="action" value="import"/>
                                    <input type="hidden" name="id" value="{$id|escape}"/>
                                    <input type="file" class="file pure-file" id="translations_file"
                                           name="translations_file"
                                           size="1"/>
                                    <label for="translations_file">{alias code="select-file"}&nbsp;</label>
                                    <button type="submit" class=""
                                            onclick="return importTranslations(this);"> {alias code=import} </button>
                                </div>
                            </form>
                            <iframe width="100%" height="200" name="translations" id="translations"></iframe>
                        </div>
                    </div>
                {else}
                    <div id="operationsBox" class="footer operationsBox ">
                        <div class="padding btn-panel">
                            <button type="button" class="btn-panel_btn " onclick="new_variable()" title="Alt+N">
                                {alias code=add}
                            </button>
                            <button class="btn-panel_btn " type="submit" onclick="variables_submit()">
                                {alias code=save}
                            </button>
                            <iframe width="100%" height="200" name="translations" id="translations"></iframe>
                        </div>
                    </div>
                {/if}
            </div>
            <div>
            </div>
        </div>
        {/strip}
