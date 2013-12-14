<div ng-switch-when='confirm' class='domain_contact'>
	<h3><?php echo __('Confirm Purchase'); ?></h3>
	<div class='confirm_line'>
		<div class='label'><?php echo __('Domain To Purchase'); ?></label>
		<div class='value'>{{domain_to_purchase.name}}</div>
	</div>
	
	
</div>
	
	<div ng-switch-when='loading' class='loading'>
	<img src="/img/admin/icons/ajax-loader-light-grey.gif"/>
</div>
