<div ng-switch-when='confirm' class='domain_confirmation'>
	<div ng-show="errorMessage != undefined && errorMessage != ''" class='error flashMessage rounded-corners-tiny'><i class='icon-warning-sign'></i><span>{{errorMessage}}</span></div>
	<h3><?php echo __('Confirm Purchase'); ?></h3>
	<div class="domain_confirm_details">
		<div class='confirm_line'>
			<div class='label'><?php echo __('Domain To Purchase'); ?></div>
			<div class='value'>{{domain_to_purchase.name}}</div>
		</div>
		<div class='confirm_line'>
			<div class='label'><?php echo __('Price'); ?></div>
			<div class='value'>{{domain_to_purchase.price}}</div>
		</div>
		<div class='confirm_line'>
			<div class='label'><?php echo __('Renewal Date'); ?></div>
			<div class='value'><?php printf(__('Will renew on %1$s.', true), date('d/m/y', strtotime("+1 year"))); ?></div>
		</div>
	</div>
	<div style='position:relative' class="input continue">
		<button fm-button ng-click='setStep("domain_contact")'><?php echo __('Edit Payment Details'); ?></button>
		<button fm-button ng-click='submitPurchase()'><?php echo __('Purchase Domain'); ?></button>
	</div>
</div>
