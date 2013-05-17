{extends file="parent.tpl"}
{block name=content}
{if $success}
	<h1>Sign Up</h1>
	<p class="alert alert-success">{$success}</p>
{else}
	<h1>Sign Up</h1>
	{foreach from=$signupErrors item=error}
		<div class="alert alert-error">
			{$error.message}
		</div>
	{/foreach}
	<form method="post" action="/users/signup" class="form-horizontal">
		<fieldset>
			<div class="control-group {if $registerNameError}error{/if}">
				<label class="control-label">Name</label>
				<div class="controls">
					<input type="text" name="name" value="{$smarty.request.name}" class="input-medium" />
					<span class="help-inline">{$registerNameError}</span>
				</div>
			</div>
			<div class="control-group {if $registerEmailError}error{/if}">
				<label class="control-label">E-mail Address</label>
				<div class="controls">
					<input type="text" name="user_email" class="email input-medium" value="{$smarty.request.user_email}" autocapitalize="off" />
					<span class="help-inline">{$registerEmailError}</span>
				</div>
			</div>
			<div class="control-group {if $registerPasswordError}error{/if}">
				<label class="control-label">Password</label>
				<div class="controls">
					<input type="password" name="user_password[]" class="password input-medium" />
					<span class="help-inline">{$registerPasswordError}</span>
				</div>
			</div>
			<div class="control-group {if $registerPasswordError}error{/if}">
				<label class="control-label">Confirm Password</label>
				<div class="controls">
					<input type="password" name="user_password[]" class="password input-medium" />
					<span class="help-inline"></span>
				</div>
			</div>
			<div class="control-group">
				<div class="controls">
					<input type="submit" name="Submit-1" value="Sign me up!" class="submit btn btn-success" />
				</div>
			</div>
		</fieldset>
	</form>
{/if}
{/block}