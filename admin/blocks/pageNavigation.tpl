{if !$page_var}
	{assign var="page_var" value="page"}
{/if}
{*{if $pageNavigation->pageCount > 1}*}
	<div class="page-navigation-box-wrap">
		<div class="page-navigation-box">
		    {*<span class="label">
				{alias code="pages" context="pages_admin"}:
			</span>*}
			{strip}
                {if $pageNavigation->previous}
    				<div class="page-previous page">

    						<a href="{request_url add="`$page_var`=`$pageNavigation->previous`" remove="ajax"}" class="">
    							{*{alias code='previous' context='pages_admin'}*}
    							<img src="{$smarty.const.WWW}images/icons/left_arrow.png" />
    						</a>
    				</div>
                {/if}
                {if $pageNavigation->next}
                <div class="page-next page">

						<a href="{request_url add="`$page_var`=`$pageNavigation->next`" remove="ajax"}" class="">
							<img src="{$smarty.const.WWW}images/icons/right_arrow.png" />
						</a>
				</div>
                {/if}
                <div class="page page-input">
                    <form >
                        <input type="hidden" name="module" value="{$_module->_class_name}" >
                        <input name="page" placeholder="{alias code='writeNo'}" type="number">
                    </form>
                </div>
			    <ol class="block page-navigation">
				    {foreach from=$pageNavigation->pages item=item}
					    {strip}
					        <li class="page{if $item.active} active{/if} {if $item.skipped} skipped {/if}">
						        {if $item.skipped}
                                    <span class="">...</span>
						        {else}
									<a href="{request_url add="`$page_var`=`$item.number`" remove="ajax"}" class=" {if $item.active} active{/if}">
										{$item.number}
									</a>
						        {/if}
					        </li>
					    {/strip}
				    {/foreach}
			    </ol>
			{/strip}
		</div>
	</div>
{*{/if}*}
