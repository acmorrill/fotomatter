<section ng-switch-when='confirm' class='domain_confirmation'>
	<div class="fade_background_top"></div>
	<div class="ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix ui-draggable-handle">
		<span id="ui-id-1" class="ui-dialog-title"><?php echo __('Purchase Domain', true); ?></span>
		<button type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-icon-only ui-dialog-titlebar-close" role="button" title="Close" ng-click='cancel()'>
			<span class="ui-button-icon-primary ui-icon ui-icon-closethick"></span>
			<span class="ui-button-text">Close</span>
		</button>
	</div>

	<div id="error_and_content_cont" class="error_and_content_cont">
		<p ng-show="errorMessage != undefined && errorMessage != ''" class='warning flashMessage'><i class='icon-warning-01'></i><span>{{errorMessage}}</span></p>	
		<div class="ui-dialog-content ui-widget-content fotomatter_form" style="width: auto; min-height: 0px; height: auto;">
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

