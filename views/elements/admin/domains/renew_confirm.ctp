<div ng-switch-when='renew_confirm' class='domain_confirmation'>
	<?php //debug($account_domain); ?>
	<div ng-show="errorMessage != undefined && errorMessage != ''" class='error flashMessage rounded-corners-tiny'><i class='icon-warning-sign'></i><span>{{errorMessage}}</span></div>
	<h3><?php echo __('Confirm Renewal'); ?></h3>
	<div class="domain_confirm_details">
		<div class='confirm_line'>
			<div class='label'><?php echo __('Domain To Renew'); ?></div>
			<div class='value'><?php echo $account_domain['AccountDomain']['url']; ?></div>
		</div>
		<div class='confirm_line'>
			<div class='label'><?php echo __('Price'); ?></div>
			<div class='value'><?php echo $account_domain['AccountDomain']['renew_price']; ?></div>
		</div>
		<div class='confirm_line'>
			<div class='label'><?php echo __('New Expire Date'); ?></div>
			<div class='value'><?php echo $this->Util->get_formatted_created_date($account_domain['AccountDomain']['renew_expires']); ?></div>
			<?php // DREW TODO - reformat the renew date ?>
		</div>
	</div>
	<div style='position:relative' class="input continue">
		<button fm-button ng-click='setStep("domain_contact")'><?php echo __('Edit Payment Details'); ?></button>
		<button fm-button ng-click='submit_renew_purchase("<?php echo $account_domain['AccountDomain']['url']; ?>")'><?php echo __('Renew Domain'); ?></button>
	</div>
</div>
