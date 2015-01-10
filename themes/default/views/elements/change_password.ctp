<?php if ($can_change_password === true): ?>
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
				<div class="frontend_form_submit_button submit_button"><span class='content'><?php echo __('Save', true); ?></span><span class='extra'></span></div>
			</div>
		</form>
	</div>
<?php else: ?>
	<div id="login_container">
		The change password link is invalid.
	</div>
<?php endif; ?>



