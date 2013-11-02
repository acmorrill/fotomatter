<div id="domains-outer-cont" class='domains-outer-cont' ng-controller='domains_index'>
	<div class='domain_header'>
		<div class='search_box'>
			<input ng-model='query' placeholder='<?php __('Search for New Domains'); ?>' class='domain_field' ng-model='domain_field' />
			<button ng-click='search()' fm-button class='search'><?php echo __('Search'); ?></button>
		</div>
		<div class='direct_result' ng-class="{'not_avail' : domain_found == false, 'avail' : domain_found}" ng-show="domain_searched != undefined">
			<h3>{{domain_searched}} is <span ng-show='domain_found == false'><?php echo __('not available'); ?></span><span ng-show='domain_found'><?php echo __('available'); ?></span></h3>
		</div>
	</div>
		<div class='fm_info_notice'>
			<p><?php echo __('Give your site its own identity. Only fotomatter doesn\'t charge you anything extra to have a domain, just pay the cost of registration, and then its 
				yours for a year. Use the search field above to get started.'); ?></p>
		</div>
		<table ng-show='domains.length > 0' class='list'>
			<tbody>
				<tr>
					<th>Name</th>
					<th>Cost</th>
					<th>Type</th>
					<th></th>
				</tr>
				<tr ng-repeat='(domain_name, domain) in domains'>
					<td>{{domain.name}}</td>
					<td>{{domain.price}}</td>
					<td>{{domain.tld}}</td>
					<td><button fm-button>Buy Now</button></td>
				</tr>
			</tbody>
		</table>
	</div>
	
</div>
