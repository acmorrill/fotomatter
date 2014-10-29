<?php /*
<div class="ui-dialog ui-widget ui-widget-content ui-corner-all ui-front ui-dialog-buttons ui-draggable" tabindex="-1" role="dialog" aria-describedby="change_logo_dialog" aria-labelledby="ui-id-1" style="height: auto; width: 600px; top: 158.5px; left: 652.5px; display: block; z-index: 101;">
	<div class="ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix ui-draggable-handle">
		<span id="ui-id-1" class="ui-dialog-title">Choose Logo</span>
		<button type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-icon-only ui-dialog-titlebar-close" role="button" title="Close">
			<span class="ui-button-icon-primary ui-icon ui-icon-closethick"></span>
			<span class="ui-button-text">Close</span>
		</button>
	</div>
	<div class="ui-dialog-content ui-widget-content" style="width: auto; min-height: 0px; max-height: none; height: auto;">

	</div>
	<div class="ui-dialog-buttonpane ui-widget-content ui-helper-clearfix">
		<div class="ui-dialog-buttonset">
			<button type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button">
				<span class="ui-button-text">Use Selected</span>
			</button>
			<button type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button">
				<span class="ui-button-text">Upload New</span>
			</button>
		</div>
	</div>
</div>
 */ ?>


<section ng-switch on='currentStep'>
	<?php echo $this->element('admin/domains/loading'); ?>
	<?php echo $this->element('admin/domains/cc_profile', array('countries' => $countries)); ?>
	<?php echo $this->element("admin/domains/domain_contact", array('countries' => $countries)); ?>
	<?php echo $this->element("admin/domains/confirm"); ?>
</section>