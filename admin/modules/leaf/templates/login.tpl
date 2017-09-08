{alias_context code=admin}

<div class="loginForm">
	<div class="loginForm__container">
		<img class="loginForm__logo" src="{$smarty.const.ADMIN_WWW}images/logo.png" alt="Leaf logo" />
		<form method="post">
			<div class="loginForm__field">
				<label for="username">{alias code=login}</label>
				<input type="text" id="username" name="leafAuthUsername" />
			</div>
			<div class="loginForm__field">
				<label for="password">{alias code=password}</label>
				<input type="password" id="password" name="leafAuthPassword" />
			</div>
			<div class="loginForm__field">
				<input type="submit" value="{alias code=signIn}">
			</div>
			{if $redirect_url}
				<input type="hidden" name="redirect_url" value="{request_url}" />
			{/if}
		</form>
	</div>
</div>

<div class="loginFooter">
	<ul class="loginFooter__links">
		<li class="loginFooter__link">
			<a href="http://www.cube.lv" target="_blank">cube.lv</a>
		</li>
	</ul>

	<p class="loginFooter__copyright">
		{'Y'|date} &copy; Leaf. Admin Panel 
	</p>
</div>