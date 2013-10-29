<div id="domains-outer-cont" class='domains-outer-cont' ng-controller='domains_index'>
	<div class='search_box'>
		<input ng-model='query' placeholder='<?php __('Search for New Domains'); ?>' class='domain_field' ng-model='domain_field' />
		<button ng-click='search()' fm-button class='search'><?php echo __('Search'); ?></button>
	</div>
	<div class='direct_result not_avail'>
		<h3>allure.com is <span>not available</span></h3>
	</div>
	<div class='domain_result'>
		<table class='list'>
			<tbody>
				<tr>
					<th>Name</th>
					<th>Cost</th>
					<th>Type</th>
					<th></th>
				</tr>
			</tbody>
		</table>
	</div>
	
</div>
