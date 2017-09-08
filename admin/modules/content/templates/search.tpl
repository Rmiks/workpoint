{alias_context code="admin:content"}
<div class="content noShadow searchBlock" >

    <form action="?" class="searchForm pure-form" method="get">
        <div class="pure-input-btn">
            <input type="hidden" name="module" value="content" />
            <input type="hidden" name="do" value="search" />
            <input type="text" name="searchString" class="search" id="content_search" autofocus="autofocus" placeholder="{alias code=searchKeyword}" value="{$smarty.get.searchString|escape}" />
            <button type="submit" class="pure-btn">{alias code=submitSearch}</button>
        </div>
    </form>

    <div class="searchResultsBlock">

        {if $searchProcessed}

            <div class="resultsFoundMessage">{alias code=resultsFound var_count=$resultsCount amount=$resultsCount}</div>

            {if $searchResults}

                <ul class="searchResultsList block">
                    {foreach from=$searchResults item=result}
                        <li>
                            <a href="?module=content&amp;do=edit_object&amp;object_id={$result.id}" title="{$result.name|escape}">
                                <h3>{$result.name|escape}</h3>
                                <p class="frontendLink">{$result.id|orp|escape}</p>
                                {if $result.text}
                                    <div class="resultDescription">{$result.text|escape|highlight:$searchString}</div>
                                {/if}
                            </a>
                        </li>
                    {/foreach}
                </ul>

            {/if}

        {/if}

    </div>

</div>
