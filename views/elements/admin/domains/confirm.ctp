<section ng-switch-when='confirm' class='domain_confirmation'>
	<div class="gen_confirm alert ui-dialog-content ui-widget-content" scrolltop="0" scrollleft="0" style="width: auto; min-height: 63.03999996185303px; height: auto;" >
		<p ng-show="errorMessage != undefined && errorMessage != ''" class='error flashMessage rounded-corners-tiny'><i class='icon-warning-sign'></i><span>{{errorMessage}}</span></p>
		<h3><?php echo __('Confirm Purchase'); ?></h3>
		<ul>
			<li>
				<label><?php echo __('Domain To Purchase'); ?></label>
				<span>{{domain_to_purchase.name}}</span>
			</li>
			<li>
				<label><?php echo __('Price'); ?></label>
				<span>{{domain_to_purchase.price}}</span>
			</li>
			<li>
				<label><?php echo __('Renewal Date'); ?></label>
				<span><?php echo $this->Util->get_formatted_created_date(date('Y-m-d H:i:s', strtotime("+1 year"))); ?></span>
			</li>
		</ul>
	</div> 
	<div class="ui-dialog-buttonpane ui-widget-content ui-helper-clearfix">
		<div class="ui-dialog-buttonset">
			<button fm-button ng-click='cancel()' type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false">
				<?php echo __('Cancel', true); ?>
			</button>
			<button fm-button ng-click='setStep("domain_contact")' type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false">
				<?php echo __('Edit Details', true); ?>
			</button>
			<button fm-button ng-click='submitPurchase()' type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false">
				<?php echo __('Purchase Domain', true); ?>
			</button>
		</div>
	</div>
</section>

