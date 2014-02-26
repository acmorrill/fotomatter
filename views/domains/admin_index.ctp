<div id="domains-outer-cont" class='domains-outer-cont' ng-controller='domains_index'>
	<input type='hidden' ng-model='domain_mark_up' value='<?php echo DOMAIN_MARKUP_DOLLAR; ?>' />
	<div class='domain_header'>
		<div class='search_box'>
			<input ng-model='query' placeholder='<?php __('Search for New Domains'); ?>' class='domain_field' ng-model='domain_field' />
			<button ng-click='search()' fm-button class='search'><?php echo __('Search'); ?></button>
		</div>
		<div class='direct_result' ng-class="{'not_avail' : domain_found == false, 'avail' : domain_found}" ng-show="domain_searched != undefined">
			<h3>{{domain_searched}} is <span ng-show='domain_found == false'><?php echo __('not available'); ?></span><span ng-show='domain_found'><?php echo __('available'); ?></span></h3>
		</div>
	</div>
	<div class='domain_result'>
		<div ng-show='domains == undefined' class='fm_info_notice'>
			<p><?php echo __('Give your site its own identity. Only fotomatter doesn\'t charge you anything extra to have a domain, just pay the cost of registration, and then its 
				yours for a year. Use the search field above to get started.'); ?></p>
		</div>
		<div ng-show='domains !== undefined && domains.length == 0' class='fm_info_notice'>
			<p><?php echo __('We could not find any domains available to match your search, please try again.'); ?></p>
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
					<td><button fm-button ng-click='buyDomain(domain)'>Buy Now</button></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="purchased_domains">
		<?php if (empty($domains) === false): ?>
		<div class="table_header">
			<label class="inline"><?php __('Page:'); ?></label> <?php echo $this->Paginator->counter(); ?>
			<div class="right">
				<?php echo $this->Paginator->prev(__('Prev', true), null, null, array('class' => 'disabled')); ?>&nbsp;
				<?php echo $this->Paginator->numbers(array(
					'modulus' => 2,
					'first' => 2,
					'last' => 2
				)); ?>&nbsp;
				<?php echo $this->Paginator->next(__('Next', true), null, null, array('class' => 'disabled')); ?> 
			</div>
		</div>
		<form method='POST'>
		<script type='text/javascript'>
			var primary_domain_id = '<?php echo $primary_domain_id; ?>';
		</script>
		<table class='list'>
			<tr>
				<th class='first'><?php echo $this->Paginator->sort(__('Domain Name'), 'AccountDomain.url'); ?></th>
				<th><?php echo __('Is Primary Domain?'); ?></th>
				<th class='first'><?php echo $this->Paginator->sort(__('Expires'), 'AccountDomain.expires'); ?>
				<th class=''><?php echo $this->Paginator->sort(__('Created'), 'AccountDomain.created'); ?></th>
			</tr>
			<?php foreach ($domains as $domain): ?>
			<tr>
				<td><?php echo $domain['AccountDomain']['url']; ?></td>
				<td><input type='radio' name='primary_domain' ng-model='primary_domain' ng-change='setDomainPrimary("<?php echo $domain['AccountDomain']['id']; ?>")' value='<?php echo $domain['AccountDomain']['id']; ?>' /></td>
				<td><?php echo $this->Util->get_formatted_created_date($domain['AccountDomain']['expires']); ?></td>
				<td><?php echo $this->Util->get_formatted_created_date($domain['AccountDomain']['created']); ?></td>
			</tr>
			<?php endforeach; ?>
		</table>
		</form>
		<?php else: ?>
		<p class='notice'><?php echo __('You currently do not have any custom domains.'); ?></p>
		<?php endif; ?>
	</div>
</div>
