<?php if ($can_change_password === true): ?>
	<style type="text/css">
		/* temp styles */
		#login_container {
			margin-top: 30px;
			outline: 1px solid black;
			padding: 20px;
		}
		#login_container label {
			display: inline-block;
			vertical-align: top;
			width: 100px;
			text-align: right;
			margin-right: 10px;
		}
		#login_container .input {
			margin-bottom: 10px;

		}
		#login_container .submit {
			padding-left: 114px;
		}
	</style>
	<div id="login_container">
		<?php echo $this->Session->flash(); ?>
		<form action="/ecommerces/change_fe_password/<?php echo $user_id; ?>/<?php echo $passed_modified_hash; ?>" method="post">
			<div class="input">
				<label><?php __('Password'); ?>:</label> <input type="password" name="data[User][new_password]" value="" /><br/>
			</div>
			<div class="input">
				<label><?php __('Password Repeat'); ?>:</label> <input type="password" name="data[User][new_password_repeat]" value="" />
			</div>
			<div class="submit">
				<input type="submit" value="<?php __('Save'); ?>" />
			</div>
		</form>
	</div>
<?php else: ?>
	<div id="login_container">
		The change password link is invalid.
	</div>
<!--	DREW TODO - make this section look better-->
<?php endif; ?>



