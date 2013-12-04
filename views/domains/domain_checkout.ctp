<div parent-fm-abs-center class="ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix" >
	<span class="ui-dialog-title" id="ui-dialog-title-1">Purchase Domain</span>
	<a href="#" class="ui-dialog-titlebar-close ui-corner-all" role="button">
		<span class="ui-icon ui-icon-closethick">close</span>
	</a>
</div>
<div ng-switch on='currentStep' class="ui-dialog-content ui-widget-content" style="width:auto; min-height: 0px; height: 515.03125px;" scrolltop="0" scrollleft="0">
	<?php echo $this->element('admin/domains/loading'); ?>
	<?php echo $this->element('admin/domains/cc_profile', array('countries'=>$countries)); ?>
	<?php echo $this->element("admin/domains/domain_contact"); ?>
</div>