<script type="text/javascript">
	jQuery(document).ready(function() {
		<?php if (isset($email)): ?>
			jQuery('#UserPassword').focus();
		<?php else: ?>
			jQuery('#UserEmailAddress').focus();
		<?php endif; ?>
		
		jQuery('#forgot_password_link').click(function() {
			jQuery('#UserLoginForm').hide();
			jQuery('#forgot_password_form').show();
		});
	});
</script>
<!--<div class='login_padding_div'></div>-->
<div class='generic_palette_container'>
	<div class='fade_background_top'></div>
	<?php echo $this->Session->flash('auth'); ?>
	<form action="/admin/users/login" id="UserLoginForm" method="post" accept-charset="utf-8">
		<fieldset>
			<div style="display:none;">
				<input type="hidden" name="_method" value="POST">
			</div>
			<div class="input text">
				<label for="UserEmailAddress"><?php echo __('Enter Email Address', true); ?></label>
				<input name="data[User][email_address]" type="text" class="text defaultText" maxlength="127" id="UserEmailAddress" title='john@example.com' autocorrect="off" autocapitalize="off" value="<?php if (isset($email)) { echo $email; } ?>">
			</div>
			<div class="input password">
				<label for="UserPassword"><?php echo __('Enter Password', true); ?></label>
				<input  autocorrect="off" autocapitalize="off" type="password" name="data[User][password]" class="text" id="UserPassword">
				<a id='forgot_password_link'><?php echo __('Forgot password?', true); ?></a>
			</div>
			<div class="submit custom_ui">
				<div class="add_button javascript_submit">
					<div class="content"><?php echo __('Sign In', true); ?></div>
					<div class="right_arrow_lines icon-arrow-01"><div class=""></div></div>
				</div>
			</div>
			<div class="hide_submit">
				<input type="submit" value="login user" />
			</div>
			<?php if ($facebook_url): ?>
			<div class="custom_ui facebook">
				<a href="<?php echo $facebook_url; ?>">
				<div class="add_button ">
					<div class="content"><?php echo __('Sign In With Facebook', true); ?></div>
					<div class="icon-facebook2"></div>
				</div>
				</a>
			</div>
			<?php endif; ?>
		</fieldset>
	</form>

	<form id='forgot_password_form' action="/users/request_admin_password_change" method="post">
		<fieldset>
			<div class="input">
				<label><?php echo __('Enter Account Email', true); ?></label>
				<input autocorrect="off" autocapitalize="off" title='john@example.com' name="data[User][forgot_password_email]" type="text" class="text defaultText" maxlength="127" />
			</div>
			<div class="submit custom_ui">
				<div class="add_button javascript_submit">
					<div class="content"><?php echo __('Change Password', true); ?></div>
					<div class="right_arrow_lines icon-arrow-01"><div class=""></div></div>
				</div>
			</div>
			<div class="hide_submit">
				<input type="submit" value="Send Forgot Password Email" />
			</div>
		</fieldset>
	</form>
</div>