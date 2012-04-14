{include file="header.tpl"}
{if $loginfailed}
	<div class="alert fade-in alert-error">
		<a class="close" data-dismiss="alert" href="#">×</a>
		Login fehlgeschlagen!
	</div>
{/if}

<form action="{$PHP_SELF}" method="post" class="form-horizontal">
	<fieldset>
		<div class="control-group">
			<label for="user" class="control-label">Benutzername:</label>
			<p class="controls">
				<input type="text" name="user" />
			</p>
		</div>
		
		<div class="control-group">
			<label for="pass" class="control-label">Passwort:</label>
			<p class="controls">
				<input type="password" name="pass"/>
			</p>
		</div>
		
		<div class="form-actions">
			<button type="submit" class="btn btn-primary" name="login" value="1">Anmelden</button>
		</div>
	</fieldset>
</form>
{include file="footer.tpl"}

