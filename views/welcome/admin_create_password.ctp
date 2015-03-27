<div id="welcome_page_container">
	<div class='generic_palette_container'>
		<div class='fade_background_top'></div>
		<?php echo $this->Session->flash('auth'); ?>
		<form action="/admin/welcome/create_password" id="CreateUserLoginForm" method="post" accept-charset="utf-8">
			<fieldset>
				<div style="display:none;">
					<input type="hidden" name="_method" value="POST">
				</div>
				<div class="input password" style="margin-top: 0px;">
					<label for="FirstName"><?php echo __('First Name', true); ?></label>
					<input  autocorrect="off" type="input" name="data[first_name]" class="text" id="FirstName" value="<?php echo $this->Util->get_not_empty_or($this->data, 'first_name', ''); ?>">
				</div>
				<div class="input password">
					<label for="LastName"><?php echo __('Last Name', true); ?></label>
					<input  autocorrect="off" type="input" name="data[last_name]" class="text" id="LastName" value="<?php echo $this->Util->get_not_empty_or($this->data, 'last_name', ''); ?>">
				</div>
				<div class="input password">
					<label for="CompanyOrTagline"><?php echo __('Company or Tagline', true); ?></label>
					<input  autocorrect="off" type="input" name="data[company_or_tagline]" class="text" id="CompanyOrTagline" value="<?php echo $this->Util->get_not_empty_or($this->data, 'company_or_tagline', ''); ?>">
				</div>
				<div class="input password">
					<label for="IndustryType"><?php echo __('Your Primary Focus', true); ?></label>
					<?php $previous_option = $this->Util->get_not_empty_or($this->data, 'industry_type_id', 0); ?>
					<select name="data[industry_type_id]" id="IndustryType">
						<option value=""><?php echo __('Choose an Option'); ?></option>
						<?php foreach ($industry_types as $industry_type): ?>
							<option <?php if ($previous_option == $industry_type['IndustryType']['id']): ?>selected="selected"<?php endif; ?> value="<?php echo $industry_type['IndustryType']['id']; ?>"><?php echo $industry_type['IndustryType']['name']; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="input password">
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

