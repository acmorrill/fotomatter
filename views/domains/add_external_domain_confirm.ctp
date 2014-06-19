<div parent-fm-abs-center class="ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix" >
	<span class="ui-dialog-title" id="ui-dialog-title-1">Really Connect Domain</span>
	<a href="#" class="ui-dialog-titlebar-close ui-corner-all" ng-click='cancel()' role="button">
		<span class="ui-icon ui-icon-closethick">close</span>
	</a>
</div>
<div class="ui-dialog-content ui-widget-content" style="width:auto; min-height: 520px;" scrolltop="0" scrollleft="0">
	<div>
		<div ng-show="errorMessage != undefined && errorMessage != ''" class='error flashMessage rounded-corners-tiny'>
			<i class='icon-warning-sign'></i><span>{{errorMessage}}</span>
		</div>
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
				<div class='value'><?php echo $this->Util->get_formatted_created_date(date('Y-m-d H:i:s', strtotime("+1 year"))); ?></div>
			</div>
		</div>
		<div style='position:relative' class="input continue">
			<button fm-button ng-click='setStep("domain_contact")'><?php echo __('Edit Payment Details'); ?></button>
			<button fm-button ng-click='submitPurchase()'><?php echo __('Purchase Domain'); ?></button>
		</div>
	</div>
</div>