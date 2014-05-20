<div id="domains-outer-cont" class='domains-outer-cont' ng-controller='domains_index'>
	<h1>
		<?php echo __('Manage Domains', true); ?>
		<div id="help_tour_button" class="custom_ui"><?php echo $this->Element('/admin/get_help_button'); ?></div>
	</h1>
	<p>
		<?php echo __('Give your site its own identity. Only fotomatter doesn\'t charge you anything extra to have a custom domain, just pay the cost of registration, and then its yours for a year. Use the search field below to get started.', true); ?>
	</p>
	<div class="purchased_domains">
		<?php if (empty($domains) === false): ?>
			<form method='POST'>
				<script type='text/javascript'>
					var primary_domain_id = '<?php echo $primary_domain_id; ?>';
				</script>
				<div class="table_container">
					<div class="fade_background_top"></div>
					<div class="table_top"></div>
					<?php $sort_dir = $this->Paginator->sortDir('AccountDomain'); ?>
					<table class="list">
						<thead>
							<tr> 
								<?php /* <?php if ($this->Paginator->sortKey('Photo') == 'Photo.id'): ?> curr <?php echo $sort_dir; ?><?php endif; ?> */ ?>
								<?php /* <?php echo $this->Paginator->sort(__('Photo ID', true), 'Photo.id'); ?> */ ?>
								<th class="first <?php if ($this->Paginator->sortKey('AccountDomain') == 'AccountDomain.url'): ?> curr <?php echo $sort_dir; ?><?php endif; ?>">
									<div class="content one_line">
										<div class="direction_arrow"></div>
										<?php echo $this->Paginator->sort(__('Domain Name', true), 'AccountDomain.url'); ?>
									</div>
								</th>
								<th>
									<div class="content one_line">
										<?php echo __('Is Primary Domain?', true); ?>
									</div>
								</th>
								<th class="<?php if ($this->Paginator->sortKey('AccountDomain') == 'AccountDomain.expires'): ?> curr <?php echo $sort_dir; ?><?php endif; ?>">
									<div class="content one_line">
										<div class="direction_arrow"></div>
										<?php echo $this->Paginator->sort(__('Expires', true), 'AccountDomain.expires'); ?>
									</div>
								</th>
								<th class="<?php if ($this->Paginator->sortKey('AccountDomain') == 'AccountDomain.created'): ?> curr <?php echo $sort_dir; ?><?php endif; ?>">
									<div class="content one_line">
										<div class="direction_arrow"></div>
										<?php echo $this->Paginator->sort(__('Created', true), 'AccountDomain.created'); ?>
									</div>
								</th>
								<th class="last actions_call"></th>
							</tr>
						</thead>
						<tbody>
							<tr class="spacer"><td colspan="3"></td></tr>
							<?php foreach ($domains as $domain): ?>
								<tr>
									<td class="first <?php if ($this->Paginator->sortKey('AccountDomain') == 'AccountDomain.url'): ?> curr<?php endif; ?>">
										<div class="rightborder"></div>
										<span><?php echo $domain['AccountDomain']['url']; ?></span>
									</td>
									<td>
										<div class="rightborder"></div>
										<input type='radio' name='primary_domain' ng-model='primary_domain' ng-change='setDomainPrimary("<?php echo $domain['AccountDomain']['id']; ?>")' value='<?php echo $domain['AccountDomain']['id']; ?>' />
									</td>
									<td class="<?php if ($this->Paginator->sortKey('AccountDomain') == 'AccountDomain.expires'): ?> curr<?php endif; ?>">
										<div class="rightborder"></div>
										<span><?php echo $this->Util->get_formatted_created_date($domain['AccountDomain']['expires']); ?></span>
									</td>
									<td class="<?php if ($this->Paginator->sortKey('AccountDomain') == 'AccountDomain.created'): ?> curr<?php endif; ?>">
										<div class="rightborder"></div>
										<span><?php echo $this->Util->get_formatted_created_date($domain['AccountDomain']['created']); ?></span>
									</td>
									<td class="last table_actions">
										<span class="custom_ui">
											<div ng-click='renewDomain("<?php echo $domain['AccountDomain']['id']; ?>")' class="add_button">
												<div class="content"><?php echo __('Add 1 Year', true);?></div>
												<div class="plus_icon_lines"><div class="one"></div><div class="two"></div></div>
											</div>
										</span>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</form>
		<?php endif; ?>
	</div>
	
	<h1 style="margin-top: 50px;"><?php echo __('Search Domains', true); ?></h1>
	<input type='hidden' ng-model='domain_mark_up' value='<?php echo DOMAIN_MARKUP_DOLLAR; ?>' />
	<div class='domain_header' style="margin-top: 30px;">
		<div class='search_box custom_ui'>
			<form ng-submit="search()">
				<input ng-model='query' placeholder='<?php echo __('{your_domain}.com', true); ?>' class='domain_field' ng-model='domain_field' />
				<div class="add_button search" ng-click='search()' ng-show="domain_searched == undefined">
					<div class="content"><?php echo __('Search',true);?></div><div class="right_arrow_lines"><div></div></div>
				</div>
				<div class="add_button search" ng-show="domain_searched != undefined">
					<div class="content">Searching ...</div>
				</div>
			</form>
		</div>
	</div>
	<div class='domain_result'>
		<div class="table_container">
			<div class="fade_background_top"></div>
			<div class="table_top"></div>
			<table ng-show='domains !== undefined' class='list'>
				<thead>
					<tr> 
						<th class="first">
							<div class="content one_line">
								<div class="direction_arrow"></div>
								<span><?php echo __('Name', true); ?></span>
							</div>
						</th>
						<th>
							<div class="content one_line">
								<div class="direction_arrow"></div>
								<span><?php echo __('Cost', true); ?></span>
							</div>
						</th>
						<th>
							<div class="content one_line">
								<div class="direction_arrow"></div>
								<span><?php echo __('Type', true); ?></span>
							</div>
						</th>
						<th class="last"></th>
					</tr>
				</thead>
				<tbody>
					<tr class="spacer"><td colspan="4"></td></tr>
					<tr  ng-show='domains.length == 0'>
						<td colspan="4">
							<h2 style="padding: 20px;"><?php echo __('We could not find any domains that matched your search, please try again.', true); ?></h2>
						</td>
					</tr>
					<tr ng-repeat='(domain_name, domain) in domains'>
						<td>
							<div class="rightborder"></div>
							<span>{{domain.name}}</span>
						</td>
						<td>
							<div class="rightborder"></div>
							<span>{{domain.price}}</span>
						</td>
						<td>
							<div class="rightborder"></div>
							<span>{{domain.tld}}</span>
						</td>
						<td class="last table_actions">
							<div class="rightborder"></div>
							<span class="custom_ui">
								<div ng-click='buyDomain(domain)' class="add_button" ng-show="domain.avail == 1">
									<div class="content"><?php echo __('Buy Now', true);?></div>
									<div class="plus_icon_lines"><div class="one"></div><div class="two"></div></div>
								</div>
								<div ng-show="domain.avail != 1">
									unavailable
								</div>
							</span>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
