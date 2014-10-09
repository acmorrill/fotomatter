<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('#forgot_password_link').click(function() {
			jQuery('#UserLoginForm').hide();
			jQuery('#forgot_password_form').show();
		});
	});
</script>
<div class='login_padding_div'></div>
<div class='generic_palette_container'>
	<div class='fade_background_top'></div>
	<?php echo $this->Session->flash('auth'); ?>
	<?php echo $this->Session->flash(); ?>
	<form action="/admin/users/login" id="UserLoginForm" method="post" accept-charset="utf-8">
		<div style="display:none;">
			<input type="hidden" name="_method" value="POST">
		</div>
		<div class="input text">
			<label for="UserEmailAddress"><?php echo __('Enter Email Address', true); ?></label>
			<input name="data[User][email_address]" type="text" class="text defaultText" maxlength="127" id="UserEmailAddress" title='john@example.com' autocorrect="off" autocapitalize="off">
		</div>
		<div class="input password">
			<label for="UserPassword"><?php echo __('Enter Password', true); ?></label>
			<input type="password" name="data[User][password]" class="text" id="UserPassword">
			<a id='forgot_password_link'><?php echo __('Forgot password?', true); ?></a>
		</div>
		<div class="submit custom_ui">
			<div class="add_button javascript_submit">
				<div class="content"><?php echo __('Sign In', true); ?></div>
				<div class="right_arrow_lines"><div class=""></div></div>
			</div>
		</div>
		<div class="hide_submit">
			<input type="submit" value="login user" />
		</div>
	</form>

	<form id='forgot_password_form' action="/users/request_admin_password_change" method="post">
		<div class="input">
			<label><?php echo __('Enter Account Email', true); ?></label>
			<input title='john@example.com' name="data[User][forgot_password_email]" type="text" class="text defaultText" maxlength="127" autocorrect="off" autocapitalize="off" />
		</div>
		<div class="submit custom_ui">
			<div class="add_button javascript_submit">
				<div class="content"><?php echo __('Change Password', true); ?></div>
				<div class="right_arrow_lines"><div class=""></div></div>
			</div>
		</div>
		<div class="hide_submit">
			<input type="submit" value="Send Forgot Password Email" />
		</div>
	</form>
</div>