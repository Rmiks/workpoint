<form action="{$actionUrl}" method="POST">

    <input type="hidden" name="action" value="login">

    <div>
        <label for="email">email</label>
        <input type="email" id="email" name="email">
    </div>

    <div>
        <label for="password">password</label>
        <input type="password" id="password" name="password">
    </div>


    <button type="submit">submit</button>
</form>
{if $emailRegisterFormUrl}
    <a href="{$emailRegisterFormUrl}">{alias code="register"}</a>
{/if}
