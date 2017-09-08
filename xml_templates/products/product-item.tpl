{alias_context code=product}
{strip}
    <a href="{$_object|orp}">
        {alias code="back_to_`$product->categoryId`"}
    </a>
    <h1>{$product->name|escape}</h1>
    <span>{$product->getReadablePrice()|escape}</span>
    <p>{$product->description|escape|nl2br}</p>
    <img src="{$product->getImageUrl()}" alt="{$product->name|escape}">
{/strip}