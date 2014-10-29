<section ng-switch-when='domain_contact' class='domain_contact'>
	<div class="fade_background_top"></div>
	<div class="ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix ui-draggable-handle">
		<span id="ui-id-1" class="ui-dialog-title"><?php echo __('Purchase Domain &nbsp;&nbsp;|&nbsp;&nbsp; Contact Information', true); ?></span>
		<button type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-icon-only ui-dialog-titlebar-close" role="button" title="Close" ng-click='cancel()'>
			<span class="ui-button-icon-primary ui-icon ui-icon-closethick"></span>
			<span class="ui-button-text">Close</span>
		</button>
	</div>
	<div id="error_and_content_cont" class="error_and_content_cont">
		<p ng-show="errorMessage != undefined && errorMessage != ''" class='warning flashMessage'><i class='icon-warning-01'></i><span>{{errorMessage}}</span></p>
		<div class="ui-dialog-content ui-widget-content fotomatter_form short" style="width: auto; min-height: 0px;">
			<form>
				<div class="input">
					<p><?php echo __('The contact information that will be associated with your new domain.', true); ?></p>
					<p><?php echo __('* required.', true); ?></p>
				</div>
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
					<select ng-model='contact.country_id' ng-change="countryChange('contact_states_for_selected_country', contact.country_id)" id="country">
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
					<input type='text' id='contact_fax' ng-model='contact.fax' />
				</div>
			</form>
		</div> 
		
	</div>
	<div class="ui-dialog-buttonpane ui-widget-content ui-helper-clearfix">
		<div class="ui-dialog-buttonset">
			<button fm-button ng-click='cancel()' type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false">
				<?php echo __('Cancel', true); ?>
			</button>
			<button fm-button ng-click='setStep("cc_profile")' type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false">
				<?php echo __('Edit Payment Details', true); ?>
			</button>
			<button fm-button ng-click='submitContact()' type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false">
				<?php echo __('Next', true); ?>
			</button>
		</div>
	</div>
</section>