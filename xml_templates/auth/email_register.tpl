<form action="{$actionUrl}" method="POST">

    <input type="hidden" name="action" value="register">

    <div>
        <label for="name">name</label>
        <input type="text" id="name" name="name">
    </div>

    <div>
        <label for="email">email</label>
        <input type="email" id="email" name="email">
    </div>

    <div>
        <label for="password">password</label>
        <input type="password" id="password" name="password">
    </div>

    <div>
        <label for="email">passwordRepeat</label>
        <input type="password" id="passwordRepeat" name="passwordRepeat">
    </div>

    <button type="submit">submit</button>
</form>