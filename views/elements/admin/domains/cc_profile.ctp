<div ng-switch-when='cc_profile' class="profile-outer-cont domains fm_form">
	<h3><?php echo __('Add credit Card'); ?></h3>
	<div ng-show="errorMessage != undefined && errorMessage != ''" class='error flashMessage rounded-corners-tiny'><i class='icon-warning-sign'></i><span>{{errorMessage}}</span></div>
    <form id="payment_details_client" class='fm_form' action="#" onSubmit="send_form(); return false;">
        <div class="address">
            <input type='hidden' id='billing_id' ng-model="profile.id"  />
            <input type='hidden' id='billing_id' ng-model="profile.created" />
            <input type='hidden' id='billing_id' ng-model="profile.modified" />
            <div class="input">
                <label for="billing_firstname"><?php echo __('First Name'); ?></label>
                <input type="text" id="billing_firstname" ng-model="profile.billing_firstname" required />
            </div>
            <div class="input">
                <label for="billing_lastname"><?php echo __('Last Name'); ?></label>
                <input type="text" id="billing_lastname" ng-model="profile.billing_lastname" required />
            </div>
            <div class="input">
                <label for="billing_address"><?php echo __('Address'); ?></label>
                <input type="text" id="billing_address" ng-model='profile.billing_address' required />
            </div>
            <div class="input">
                <label for="billing_country"><?php echo __('Country'); ?></label>
                <select ng-model='profile.country_id' ng-change="countryChange('states_for_selected_country', profile.country_id)" id="billing_country">
                    <?php foreach ($countries as $key => $country): ?>
                    <option value="<?php echo $country['GlobalCountry']['country_code_2']; ?>"><?php echo $country['GlobalCountry']['country_name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="input">
                <label for="billing_city"><?php echo __('City'); ?></label>
                <input type="text" id="billing_city" ng-model='profile.billing_city' />
            </div>
            <div class="input">
                <label for="billing_state"><?php echo __('State'); ?></label>
                <select id="billing_state" ng-model="profile.country_state_id" ng-options="state.GlobalCountryState.state_code_3 as state.GlobalCountryState.state_name for state in states_for_selected_country">
                    <?php echo $this->element('admin/accounts/state_list', array('country_code'=>'US')); ?>
                </select>
            </div>
            <div class="input">
                <label for="billing_zip"><?php echo __('Zip'); ?></label>
                <input type="text" id="billing_zip" ng-model='profile.billing_zip' />
            </div>
        </div>
        <div class="payment">
            <div class="input">
                <label for="billing_zip"><?php echo __('Card Number'); ?></label>
                <input type="text" id="billing_cardNumber" ng-model='profile.payment_cardNumber' />
            </div>
            <div class="input exp_date">
                <label for ="card_exp"><?php echo __('Expiration Date'); ?></label>
                <select ng-model='profile.expiration.month'>
                    <option value="01"><?php echo __('January'); ?></option>
                    <option value="02"><?php echo __('February'); ?></option>
                    <option value="03"><?php echo __('March'); ?></option>
                    <option value="04"><?php echo __('April'); ?></option>
                    <option value="05"><?php echo __('May'); ?></option>
                    <option value="06"><?php echo __('June'); ?></option>
                    <option value="07"><?php echo __('July'); ?></option>
                    <option value="08"><?php echo __('August'); ?></option>
                    <option value="09"><?php echo __('September'); ?></option>
                    <option value="10"><?php echo __('October'); ?></option>
                    <option value="11"><?php echo __('Novembe'); ?>r</option>
                    <option value="12"><?php echo __('December'); ?></option>                
                </select>
                <select ng-model='profile.expiration.year'>
                    <?php for($i=0; $i < 3; $i++): ?>
                    <option><?php echo date('Y', strtotime("+".$i." years")); ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="input">
                <label for="billing_csv"><?php echo __('Csv Code'); ?></label>
                <input type="text" id="billing_csv" ng-model='profile.payment_cardCode' />
            </div>
            <div style='position:relative' class="input continue">
                <input fm-button ng-click='submitPayment()' type="button" value="<?php echo __('Next'); ?>" />
            </div>
        </div>
    </form>
	<div class='profile_info rounded-corners'>
		<p>This is the card that we will use every month to pay your fotomatter subscription. </p>
		<p>Please remember to keep it up to date so that your service or website will not be interrupted.</p>
	</div>
</div>