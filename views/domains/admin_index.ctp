<div id="domains-outer-cont" class='domains-outer-cont' ng-controller='domains_index'>
	<div class='domain_header'>
		<div class='search_box'>
			<input ng-model='query' placeholder='<?php __('Search for New Domains'); ?>' class='domain_field' ng-model='domain_field' />
			<button ng-click='search()' fm-button class='search'><?php echo __('Search'); ?></button>
		</div>
		<div class='direct_result avail' ng-class="{domain_found == false: 'not_avail'}" ng-show="domain_searched != undefined">
			<h3>{{domain_searched}} is <span>not available</span></h3>
		</div>
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
