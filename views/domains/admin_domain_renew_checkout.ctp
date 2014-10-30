<section ng-switch on='currentStep'>
	<?php echo $this->element('admin/domains/loading'); ?>
	<?php echo $this->element('admin/domains/cc_profile', array('countries' => $countries)); ?>
	<?php echo $this->element("admin/domains/renew_confirm"); ?>
</section>

