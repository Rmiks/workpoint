<div class="content" id="content">
    <div class="textContent section">{if array_key_exists('success',
        $smarty.get)}
            <p>You have succesfuly authenticated! Yay!</p>
        {/if}
        <p><a href="{$smarty.const.WWW}">Click
                here to go back.</a></p>
    </div>
</div>
