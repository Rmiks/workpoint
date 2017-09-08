{foreach from=$authData item=item key=authType}
    <div>
        <a href="{$item}" class="{$authType}">{$authType}</a>
    </div>
{/foreach}