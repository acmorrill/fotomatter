<?php if ($can_change_password === true): ?>
	<div id="login_container">
		<?php echo $this->Session->flash(); ?>
		<form action="/users/change_admin_password/<?php echo $user_id; ?>/<?php echo $passed_modified_hash; ?>" method="post">
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
<?php endif; ?>



