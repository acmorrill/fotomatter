<section ng-switch-when='cc_profile' class="profile-outer-cont domains fm_form">
	<div class="fade_background_top"></div>
	<div class="ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix ui-draggable-handle">
		<span id="ui-id-1" class="ui-dialog-title"><?php echo __('Purchase Domain &nbsp;&nbsp;|&nbsp;&nbsp; Payment Information', true); ?></span>
		<button type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-icon-only ui-dialog-titlebar-close" role="button" title="Close" ng-click='cancel()'>
			<span class="ui-button-icon-primary ui-icon ui-icon-closethick"></span>
			<span class="ui-button-text">Close</span>
		</button>
	</div>

	<div id="error_and_content_cont" class="error_and_content_cont">
		<p ng-show="errorMessage != undefined && errorMessage != ''" class='warning flashMessage'><i class='icon-warning-01'></i><span>{{errorMessage}}</span></p>
		<div class="ui-dialog-content ui-widget-content fotomatter_form short" style="width: auto; min-height: 0px;">
			<form id="payment_details_client" action="#" onSubmit="send_form(); return false;">
				<div class="input">
					<p><?php echo __('Enter your payment information below', true); ?></p>
					<br />
					<p><?php echo __('* required', true); ?></p>
				</div>
				<div class="address">
					<input type='hidden' id='billing_id' ng-model="profile.id"  />
					<input type='hidden' id='billing_id' ng-model="profile.created" />
					<input type='hidden' id='billing_id' ng-model="profile.modified" />
					<div class="input">
						<label for="billing_firstname"><?php echo __('First Name', true); ?> *</label>
						<input type="text" id="billing_firstname" ng-model="profile.billing_firstname" required />
					</div>
					<div class="input">
						<label for="billing_lastname"><?php echo __('Last Name', true); ?> *</label>
						<input type="text" id="billing_lastname" ng-model="profile.billing_lastname" required />
					</div>
					<div class="input">
						<label for="billing_address"><?php echo __('Address', true); ?> *</label>
						<input type="text" id="billing_address" ng-model='profile.billing_address' required />
					</div>
					<div class="input">
						<label for="billing_country"><?php echo __('Country', true); ?></label>
						<select ng-model='profile.country_id' ng-change="countryChange('states_for_selected_country', profile.country_id)" id="billing_country">
							<?php foreach ($countries as $key => $country): ?>
							<option value="<?php echo $country['GlobalCountry']['country_code_2']; ?>"><?php echo $country['GlobalCountry']['country_name']; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="input">
						<label for="billing_city"><?php echo __('City', true); ?> *</label>
						<input type="text" id="billing_city" ng-model='profile.billing_city' />
					</div>
					<div class="input">
						<label for="billing_state"><?php echo __('State', true); ?></label>
						<select id="billing_state" ng-model="profile.country_state_id" ng-options="state.GlobalCountryState.state_code_3 as state.GlobalCountryState.state_name for state in states_for_selected_country">
							<?php echo $this->element('admin/accounts/state_list', array('country_code'=>'US')); ?>
						</select>
					</div>
					<div class="input">
						<label for="billing_zip"><?php echo __('Zip', true); ?> *</label>
						<input type="text" id="billing_zip" ng-model='profile.billing_zip' />
					</div>
				</div>
				<div class="payment">
					<div class="input">
						<label for="billing_zip"><?php echo __('Card Number', true); ?> *</label>
						<input type="text" id="billing_cardNumber" ng-model='profile.payment_cardNumber' />
					</div>
					<div class="input exp_date">
						<label for ="card_exp"><?php echo __('Expiration Date', true); ?> *</label>
						<select ng-model='profile.expiration.month'>
							<option value="01"><?php echo __('January', true); ?></option>
							<option value="02"><?php echo __('February', true); ?></option>
							<option value="03"><?php echo __('March', true); ?></option>
							<option value="04"><?php echo __('April', true); ?></option>
							<option value="05"><?php echo __('May', true); ?></option>
							<option value="06"><?php echo __('June', true); ?></option>
							<option value="07"><?php echo __('July', true); ?></option>
							<option value="08"><?php echo __('August', true); ?></option>
							<option value="09"><?php echo __('September', true); ?></option>
							<option value="10"><?php echo __('October', true); ?></option>
							<option value="11"><?php echo __('Novembe', true); ?>r</option>
							<option value="12"><?php echo __('December', true); ?></option>                
						</select>
						<select ng-model='profile.expiration.year'>
							<?php for($i=0; $i < 3; $i++): ?>
							<option><?php echo date('Y', strtotime("+".$i." years")); ?></option>
							<?php endfor; ?>
						</select>
					</div>
					<div class="input">
						<label for="billing_csv"><?php echo __('Csv Code', true); ?> *</label>
						<input type="text" id="billing_csv" ng-model='profile.payment_cardCode' />
					</div>
				</div>
			</form>
		</div> 
	</div>
	
	<div class="ui-dialog-buttonpane ui-widget-content ui-helper-clearfix">
		<div class="ui-dialog-buttonset">
			<button fm-button ng-click='cancel()' type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false">
				<?php echo __('Cancel', true); ?>
			</button>
			<button fm-button ng-click='submitPayment()' type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false">
				<?php echo __('Next', true); ?>
			</button>
		</div>
	</div>
	
</section>