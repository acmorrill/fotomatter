<div parent-fm-abs-center class="ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix" >
	<span class="ui-dialog-title" id="ui-dialog-title-1">Delete Domain?</span>
	<a href="#" class="ui-dialog-titlebar-close ui-corner-all" ng-click='cancel()' role="button">
		<span class="ui-icon ui-icon-closethick">close</span>
	</a>
</div>
<div class="ui-dialog-content ui-widget-content" style="width:auto; min-height: 520px;" scrolltop="0" scrollleft="0">
	<div class='domain_delete_confirmation'>
		<h3><?php echo __('Really Delete?'); ?></h3>
		<div ng-show="errorMessage != undefined && errorMessage != ''" class='error flashMessage rounded-corners-tiny'><i class='icon-warning-sign'></i><span>{{errorMessage}}</span></div>
		<div style='position:relative' class="input continue">
			<button fm-button ng-click='cancel()'><?php echo __('Cancel'); ?></button>
			<button fm-button ng-click='delete_domain()'><?php echo __('Delete'); ?></button>
		</div>
	</div>
</div>