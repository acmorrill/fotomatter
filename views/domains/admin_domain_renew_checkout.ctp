<div parent-fm-abs-center class="ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix" >
	<span class="ui-dialog-title" id="ui-dialog-title-1">Renew Domain</span>
	<a href="#" class="ui-dialog-titlebar-close ui-corner-all" ng-click='cancel()' role="button">
		<span class="ui-icon ui-icon-closethick">close</span>
	</a>
</div>
<div ng-switch on='currentStep' class="ui-dialog-content ui-widget-content" style="width:auto; min-height: 520px;" scrolltop="0" scrollleft="0">
	<?php echo $this->element('admin/domains/loading'); ?>
	<?php echo $this->element('admin/domains/cc_profile', array('countries' => $countries)); ?>
	<?php echo $this->element("admin/domains/renew_confirm"); ?>
</div>