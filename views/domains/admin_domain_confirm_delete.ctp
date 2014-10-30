<section ng-switch on='currentStep'>
	
	
	<?php echo $this->element('admin/domains/loading'); ?>
	<section ng-switch-when='confirm_delete'>
		<div class="fade_background_top"></div>
		<div class="ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix ui-draggable-handle">
			<span id="ui-id-1" class="ui-dialog-title"><?php echo __('Delete Domain', true); ?></span>
			<button type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-icon-only ui-dialog-titlebar-close" role="button" title="Close" ng-click='cancel()'>
				<span class="ui-button-icon-primary ui-icon ui-icon-closethick"></span>
				<span class="ui-button-text">Close</span>
			</button>
		</div>
		<div class="gen_confirm alert ui-dialog-content ui-widget-content" scrolltop="0" scrollleft="0" style="width: auto; min-height: 63.03999996185303px; height: auto;" >
			<p ng-show="errorMessage != undefined && errorMessage != ''" class='error flashMessage rounded-corners-tiny'><i class='icon-warning-sign'></i><span>{{errorMessage}}</span></p>
			<p>
				<?php /*<span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span> */ ?>
				<?php echo __('Are you sure you want to permenently delete {{domain_url}}?', true); ?>
			</p>
		</div> 
		<div class="ui-dialog-buttonpane ui-widget-content ui-helper-clearfix">
			<div class="ui-dialog-buttonset">
				<button fm-button ng-click='cancel()' type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false">
					<?php echo __('Cancel', true); ?>
				</button>
				<button fm-button ng-click='delete_domain()' type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false">
					<?php echo __('Delete Domain', true); ?>
				</button>
			</div>
		</div>
	</section>
</section>