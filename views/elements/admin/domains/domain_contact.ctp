<div ng-switch-when='domain_contact' class='domain_contact'>
	<h3><?php echo __('Domain Contact Information'); ?></h3>
	<div ng-show="errorMessage != undefined && errorMessage != ''" class='error flashMessage rounded-corners-tiny'><i class='icon-warning-sign'></i><span>{{errorMessage}}</span></div>
	<form class='fm_form'>
		<div class="input">
			<label for="contact_first_name"><?php echo __('First Name'); ?></label>
			<input type="text" id="contact_first_name" ng-model='contact.first_name' />
		</div>
		<div class='input'>
			<label for='contact_last_name'><?php echo __('Last Name'); ?></label>
			<input type='text' id='contact_last_name' ng-model='contact.last_name' />
		</div>
		<div class='input'>
			<label for='contact_organization'><?php echo __('Organization'); ?></label>
			<input type='text' id='contact_organization' ng-model='contact.organization' />
		</div>
		<div class='input'>
			<label for='contact_address_1'><?php echo __('Address 1'); ?></label>
			<input type='text' id='contact_address_1' ng-model='contact.address_1' />
		</div>
		<div class='input'>
			<label for='contact_address_2'><?php echo __('Address 2'); ?></label>
			<input type='text' id='contact_address_2' ng-model='contact.address_2' />
		</div>
		<div class="input">
			<label for="contact_country"><?php echo __('Country'); ?></label>
			<select ng-model='contact.country_id' ng-change="countryChange('contact_states_for_selected_country')" id="country">
				<?php foreach ($countries as $key => $country): ?>
				<option value="<?php echo $country['GlobalCountry']['country_code_2']; ?>"><?php echo $country['GlobalCountry']['country_name']; ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class='input'>
			<label for='contact_city'><?php echo __('City'); ?></label>
			<input type='text' id='contact_city' ng-model='contact.city' />
		</div>
		 <div class="input">
			<label for="contact_state"><?php echo __('State'); ?></label>
			<select id="contact_state" ng-model="contact.country_state_id" ng-options="state.GlobalCountryState.state_code_3 as state.GlobalCountryState.state_name for state in contact_states_for_selected_country">
				<?php echo $this->element('admin/accounts/state_list', array('country_code'=>'US')); ?>
			</select>
		</div>
		<div class="input">
			<label for="contact_zip"><?php echo __('Zip'); ?></label>
			<input type="text" id="contact_zip" ng-model='contact.zip' />
		</div>
		<div class='input'>
			<label for='contact_phone'><?php echo __('Phone'); ?></label>
			<input type='text' id='contact_phone' ng-model='contact.phone' />
		</div>
		<div class='input'>
			<label for='contact_fax'><?php echo __('Fax'); ?></label>
			<input type='text' id='contact_fax' ng-model='contact.fax'
		</div>
		<div style='position:relative' class="input continue">
			<button fm-button ng-click='setStep("cc_profile")'><?php echo __('Edit Payment Details'); ?></button>
			<button fm-button ng-click='submitContact()'><?php echo __('Next'); ?></button>
		</div>
	</form>
</div>