{alias_context code=product}
{capture}
    {defun name=product}
    {if is_object( $product )}
        <img src="{$product->getImageUrl()|escape}" alt="{$product->name|escape}">
        <a href="{$_object|orp}{$item->getRewriteName()|escape}">
            <h2>{$product->name|escape}</h2>
        </a>
        <span>
                            {$product->getReadablePrice()|escape}
                        </span>
        <p class="item__text">
            {$product->description|escape|nl2br}
        </p>
    {/if}
    {/defun}
{/capture}

<div>
    {foreach item=item name=list from=$list}
        {fun name=product product=$item}
    {/foreach}
</div>