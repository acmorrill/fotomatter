<div class="ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix">
	<span class="ui-dialog-title" id="ui-dialog-title-3"><?php echo __('Purchase Domain', true); ?></span>
	<a href="#" class="ui-dialog-titlebar-close ui-corner-all" ng-click='cancel()' role="button">
		<span class="ui-icon ui-icon-closethick">close</span>
	</a>
</div>
<section ng-switch on='currentStep'>
	<?php echo $this->element('admin/domains/loading'); ?>
	<?php echo $this->element('admin/domains/cc_profile', array('countries'=>$countries)); ?>
	<?php echo $this->element("admin/domains/domain_contact", array('countries'=>$countries)); ?>
	<?php echo $this->element("admin/domains/confirm"); ?>
</section>