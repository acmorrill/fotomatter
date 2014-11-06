<section ng-switch-when='loading'>
	<div class="fade_background_top"></div>
	<div class="ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix ui-draggable-handle">
		<span id="ui-id-1" class="ui-dialog-title">&nbsp;&nbsp;</span>
		<button type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-icon-only ui-dialog-titlebar-close" role="button" title="Close" ng-click='cancel()'>
			<span class="ui-button-icon-primary ui-icon ui-icon-closethick"></span>
			<span class="ui-button-text">Close</span>
		</button>
	</div>
	<div id="error_and_content_cont" class="error_and_content_cont">
		<p ng-show="errorMessage != undefined && errorMessage != ''" class='warning flashMessage'><i class='icon-warning-01'></i><span>{{errorMessage}}</span></p>
		<div class="ui-dialog-content ui-widget-content" style="width: auto; min-height: 0px;">
			<?php echo __('Loading ...', true); ?>
		</div>
	</div>
	<div class="ui-dialog-buttonpane ui-widget-content ui-helper-clearfix">
		<div class="ui-dialog-buttonset">
		</div>
	</div>
</section>