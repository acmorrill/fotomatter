<section ng-switch-when='renew_confirm' class='domain_confirmation'>
	<div class="gen_confirm alert ui-dialog-content ui-widget-content" scrolltop="0" scrollleft="0" style="width: auto; min-height: 63.03999996185303px; height: auto;" >
		<p ng-show="errorMessage != undefined && errorMessage != ''" class='error flashMessage rounded-corners-tiny'><i class='icon-warning-sign'></i><span>{{errorMessage}}</span></p>
		<h3><?php echo __('Confirm Renewal'); ?></h3>
		<ul>
			<li>
				<label><?php echo __('Domain To Renew'); ?></label>
				<span><?php echo $account_domain['AccountDomain']['url']; ?></span>
			</li>
			<li>
				<label><?php echo __('Price'); ?></label>
				<span><?php echo $account_domain['AccountDomain']['renew_price']; ?></span>
			</li>
			<li>
				<label><?php echo __('New Expire Date'); ?></label>
				<span><?php echo $this->Util->get_formatted_created_date($account_domain['AccountDomain']['renew_expires']); ?></span>
				<?php // DREW TODO - reformat the renew date ?>
			</li>
		</ul>
	</div> 
	<div class="ui-dialog-buttonpane ui-widget-content ui-helper-clearfix">
		<div class="ui-dialog-buttonset">
			<button fm-button ng-click='cancel()' type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false">
				<?php echo __('Cancel', true); ?>
			</button>
			<button fm-button ng-click='setStep("cc_profile")' type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false">
				<?php echo __('Edit Payment Details', true); ?>
			</button>
			<button fm-button ng-click='submit_renew_purchase("<?php echo $account_domain['AccountDomain']['url']; ?>")' type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false">
				<?php echo __('Renew Domain', true); ?>
			</button>
		</div>
	</div>
</section>
