<div ng-switch-when='domain_contact' class='domain_contact fm_form'>
	<h3><?php echo __('Domain Contact Information'); ?></h3>
	<div class="input">
		<label for="first_name"><?php echo __('First Name'); ?></label>
		<input type="text" id="first_name" ng-model='contact.first_name' />
	</div>
	
	<button ng-click='setStep("cc_profile")'>Credit Card Profile</button>
	
</div>