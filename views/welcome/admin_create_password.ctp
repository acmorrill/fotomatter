<div id="welcome_page_container">
	<div class='generic_palette_container'>
		<div class='fade_background_top'></div>
		<?php echo $this->Session->flash('auth'); ?>
		<form action="/admin/welcome/create_password" id="CreateUserLoginForm" method="post" accept-charset="utf-8">
			<fieldset>
				<div style="display:none;">
					<input type="hidden" name="_method" value="POST">
				</div>
				<?php // $account_email ?>
				<div class="input password" style="margin-top: 0px;">
					<label for="UserPassword"><?php echo __('Create Password', true); ?></label>
					<input  autocorrect="off" autocapitalize="off" type="password" name="data[password]" class="text" id="UserPassword">
				</div>
				<div class="input password">
					<label for="ConfirmUserPassword"><?php echo __('Confirm Password', true); ?></label>
					<input  autocorrect="off" autocapitalize="off" type="password" name="data[confirm_password]" class="text" id="ConfirmUserPassword">
				</div>
				<div class="submit custom_ui">
					<div class="add_button javascript_submit">
						<div class="content"><?php echo __('Continue', true); ?></div>
						<div class="right_arrow_lines icon-arrow-01"><div class=""></div></div>
					</div>
				</div>
				<div class="hide_submit">
					<input type="submit" value="login user" />
				</div>
			</fieldset>
		</form>
	</div>
</div>

