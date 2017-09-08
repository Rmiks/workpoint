{assign var=moduleClass value=$_module|get_class}
{alias_context code="admin:`$moduleClass`"}
{alias_fallback_context code="admin"}

<div class="errors">

    <div class="leafTable">

        <div class="thead">
            <div>
                <span class="edit">&nbsp;</span>
                <span>{include file=$_module->pathTo('_all.orderBy') column=count}</span>
                <span>{include file=$_module->pathTo('_all.orderBy') column=level}</span>
                <span class="edit">&nbsp;</span>
                <span class="date">{include file=$_module->pathTo('_all.orderBy') column=add_date}</span>
                <span>{include file=$_module->pathTo('_all.orderBy') column=user_ip}</span>
                <span class="last">{alias code=message}</span>
                <span>&nbsp;</span>
            </div>
        </div>

        <div class="tbody sortables">

            {if sizeof($collection)}
                {foreach item=item name=list from=$collection}
                    <div id="row-{$item->id}">
                        <span class="tree">
                            <button class="noStyling expandTool hash-{$item->getMessageHash()}" type="button"></button>
                        </span>
                        <span>
                            {$item->count}
                        </span>
                        <span>
                            {$item->getLevelName()}
                        </span>
                        <span class="edit url">
                            <ul class="block">
                                <li>
                                    <a href="{$_module->getModuleUrl()|escape}&amp;do=view&amp;id={$item->id}">
                                        <img src="images/icons/page_white_text.png" alt="" />
                                    </a>
                                </li>
                            </ul>
                        </span>
                        <span class="date">
                            <ul class="block">
                                <li>
                                    {" "|str_replace:"&nbsp;":$item->add_date}
                                </li>
                            </ul>
                        </span>
                        <span class="ip">
                            <ul class="block">
                                <li>
                                    {$item->user_ip}{if !empty($item->user_forwarded_ip)} ({$item->user_forwarded_ip|escape}){/if}
                                </li>
                            </ul>
                        </span>
                        <span class="last">
                            {$item->message} in "{$item->file}" on line {$item->line}.
                        </span>
                        <span>
                            {simpleForm module="errors" do="deleteError" id=$item->getMessageHash() button="images/icons/bin_empty.png"}{/simpleForm}
                        </span>
                    </div>
                {/foreach}

            {else}
                <div class="unselectable">
                    {alias code="nothingFound"}
                </div>
            {/if}

        </div>

    </div>

</div>