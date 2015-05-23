<!--<div class='login_padding_div'></div>-->
<div class='generic_palette_container'>
	<div class='fade_background_top'></div>
	<?php echo $this->Session->flash('auth'); ?>
	<?php echo $this->Session->flash(); ?>
	<?php if ($can_change_password === true): ?>
		<form id="UserLoginForm" action="/users/change_admin_password/<?php echo $user_id; ?>/<?php echo $passed_modified_hash; ?>" method="post">
			<fieldset>
				<div style="display:none;">
					<input type="hidden" name="_method" value="POST">
				</div>
				<div class="input">
					<label><?php echo __('Enter Password', true); ?></label>
					<input autocorrect="off" autocapitalize="off" class="text" type="password" name="data[User][new_password]" value="" />
				</div>
				<div class="input password">
					<label><?php echo __('Repeat Password', true); ?></label>
					<input autocorrect="off" autocapitalize="off" class="text" type="password" name="data[User][new_password_repeat]" value="" />
				</div>
				<div class="submit custom_ui">
					<div class="add_button javascript_submit">
						<div class="content"><?php echo __('Change Password', true); ?></div>
						<div class="right_arrow_lines icon-arrow-01"><div class=""></div></div>
					</div>
				</div>
				<div class="hide_submit">
					<input type="submit" value="<?php echo __('Save', true); ?>" />
				</div>
			</fieldset>
		</form>
	<?php endif; ?>
</div>



