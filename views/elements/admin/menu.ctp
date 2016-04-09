<?php
	if (empty($curr_page)) {
		$curr_page = 'theme_center';
	}
?>
<div class="menu_below_bar"></div>
<div id="top-navigation">
	<ul id="extra_buttons">
		<li>
			<a href="/admin/users/logout"><i class="icon-power"></i>Log Out</a>
		</li>
		<li class="<?php if ($curr_page == 'site_settings'): ?> active<?php endif; ?>">
			<a href="" class="submenu"><i class="icon-cogWheel"></i><?php echo __('More', true); ?></a>
			<ul>
				<?php 
					if (empty($curr_sub_page)) {
						$curr_sub_page = '';
					}
				?>
				<li class="<?php if ($curr_sub_page == 'account_details'): ?> selected<?php endif; ?>">
					<div class="subnav_bg"></div>
					<table>
						<tbody>
							<tr>
								<td class="first" onclick="">
									<i class="icon-accountDetails-01"></i>
								</td>
								<td class="second">
									<a href="/admin/accounts/account_details">
										<div>
											<p><?php echo __('Account Details', true); ?></p>
										</div>
									</a>	
								</td>
								<td class="third">
									<span class="circle"></span>
								</td>
							</tr>
						</tbody>
					</table>
				</li>
				<li class="<?php if ($curr_sub_page == 'domains'): ?> selected<?php endif; ?>">
					<div class="subnav_bg"></div>
					<table>
						<tbody>
							<tr>
								<td class="first" onclick="">
									<i class="icon-siteDomains_2-01"></i>
								</td>
								<td class="second">
									<a href="/admin/domains">
										<div>
											<p><?php echo __('Site Domains', true); ?></p>
										</div>
									</a>	
								</td>
								<td class="third">
									<span class="circle"></span>
								</td>
							</tr>
						</tbody>
					</table>
				</li>
				<li class="<?php if ($curr_sub_page == 'manage_tags'): ?> selected<?php endif; ?>">
					<div class="subnav_bg"></div>
					<table>
						<tbody>
							<tr>
								<td class="first" onclick="">
									<i class="icon-manageTags-01"></i>
								</td>
								<td class="second">
									<a href="/admin/tags/manage_tags">
										<div>
											<p><?php echo __('Manage Tags', true); ?></p>
										</div>
									</a>	
								</td>
								<td class="third">
									<span class="circle"></span>
								</td>
							</tr>
						</tbody>
					</table>
				</li>
				<li class="<?php if ($curr_sub_page == 'surveys'): ?> selected<?php endif; ?>">
					<div class="subnav_bg"></div>
					<table>
						<tbody>
							<tr>
								<td class="first" onclick="">
									<i class="icon-Clipboard-01-01"></i>
								</td>
								<td class="second">
									<a href="/admin/surveys">
										<div>
											<p><?php echo __('Surveys', true); ?></p>
										</div>
									</a>
								</td>
								<td class="third">
									<span class="circle"></span>
								</td>
							</tr>
						</tbody>
					</table>
				</li>
				<li class="<?php if ($curr_sub_page == 'fotomatter_support'): ?> selected<?php endif; ?>">
					<div class="subnav_bg"></div>
					<table>
						<tbody>
							<tr>
								<td class="first" onclick="">
									<i class="icon-emailSupport-01"></i>
								</td>
								<td class="second">
									<a href="/admin/accounts/fotomatter_support">
										<div>
											<p><?php echo __('Fotomatter Support', true); ?></p>
										</div>
									</a>	
								</td>
								<td class="third">
									<span class="circle"></span>
								</td>
							</tr>
						</tbody>
					</table>
				</li>
				<li class="<?php if ($curr_sub_page == 'fotomatter_feedback'): ?> selected<?php endif; ?>">
					<div class="subnav_bg"></div>
					<table>
						<tbody>
							<tr>
								<td class="first" onclick="">
									<i class="icon-Success-01"></i>
								</td>
								<td class="second">
									<a href="/admin/accounts/fotomatter_feedback">
										<div>
											<p><?php echo __('Send Feedback', true); ?></p>
										</div>
									</a>	
								</td>
								<td class="third">
									<span class="circle"></span>
								</td>
							</tr>
						</tbody>
					</table>
				</li>
				<?php if (!empty($user_data['User']['superadmin'])): ?>
					<li class="<?php if ($curr_sub_page == 'superadmin'): ?> selected<?php endif; ?>">
						<div class="subnav_bg"></div>
						<table>
							<tbody>
								<tr>
									<td class="first" onclick="">
										<i class="icon-priceLock-01-01"></i>
									</td>
									<td class="second">
										<a href="/admin/superadmins">
											<div>
												<p><?php echo __('Super Admin Tools', true); ?></p>
											</div>
										</a>	
									</td>
									<td class="third">
										<span class="circle"></span>
									</td>
								</tr>
							</tbody>
						</table>
					</li>
				<?php endif; ?>
			</ul>
		</li>
		<li class="<?php if ($curr_page == 'mass_upload'): ?> active<?php endif; ?>">
			<a href="/admin/photos/mass_upload"><i class="icon-pictureUpload-01"></i><?php echo __('Upload Photos', true); ?></a>
		</li>
		<li class="<?php if ($curr_page == 'add_features'): ?> active<?php endif; ?>">
			<a href="/admin/accounts/index">
				<i class="icon-manageFeatures-01"></i><?php echo __ ('Manage Features', true); ?>
				<?php if (!empty($overlord_account_info['is_free_account'])): ?>
					<span id="promo_credit_balance_notice">(<span>FREE</span>)</span>
				<?php elseif ($overlord_account_info['Account']['promo_credit_balance'] > 0): ?>
					<span id="promo_credit_balance_notice">(<span><?php echo $this->Number->currency($overlord_account_info['Account']['promo_credit_balance']); ?></span>)</span>
				<?php endif; ?>
			</a>
		</li>
	</ul>
	<ul class="menu">
		<?php /*<li class='dashboard <?php if ($curr_page == 'dashboard'): ?> active<?php endif; ?>'>
			<a href='/admin/dashboards'><span><?php __("Dashboard"); ?></span></a>
			<div class="tab_bottom"><div class="tab_bottom_triangle"></div><div class="active_color"><div class="active_color_triangle"></div></div></div>
		</li> */ ?><!--
		--><li class='theme_center <?php if ($curr_page == 'theme_center'): ?> current active<?php endif; ?> dropdown'>
			
			<!--<a href='/admin/theme_centers/choose_theme'><span><?php __("Theme Center"); ?></span></a>-->
			
			<span><span><?php __("Theme Center"); ?></span></span>
			
			<div class="tab_bottom"><div class="tab_bottom_triangle"></div><div class="active_color"><div class="active_color_triangle"></div></div></div>
			<?php
				$subnav = array();

				$subnav['title'] = array(
					'name' => __('Theme Center', true),
					'url' => "/admin/theme_centers",
				);
				$subnav['pages'][] = array(
					'name' => __('Choose Theme', true),
					'url' => "/admin/theme_centers/choose_theme/",
					'icon_css' => 'ChooseTheme_icon',
				);
				$subnav['pages'][] = array(
					'name' => __('Current Theme Settings', true),
					'url' => "/admin/theme_centers/theme_settings/",
					'icon_css' => 'settings',
					'help_step' => array(
						'url' => "/admin/theme_centers/choose_theme/",
						'step_code' => 'data-step="3" data-intro="' . __("After you’ve selected your theme, click “Current Theme Settings” to begin your customizations, or select from the top menu to add galleries, photos, and pages.", true) . '" data-position="right"',
					),
				);
				$subnav['pages'][] = array(
					'name' => __('Main Menu', true),
					'url' => "/admin/theme_centers/main_menu/",
					'icon_css' => 'menu',
				);
				$subnav['pages'][] = array(
					'name' => __('Configure Logo', true),
					'url' => "/admin/theme_centers/configure_logo/",
					'icon_css' => 'ConfigureLogo-01',
				);
				$subnav['pages'][] = array(
					'name' => __('Configure Theme Background', true),
					'url' => "/admin/theme_centers/configure_background/",
					'icon_css' => 'picture',
				);

				echo $this->Element('/admin/submenu', array( 'subnav' => $subnav ));
			?>
		</li><!--
		--><li class='e-commerce <?php if ($curr_page == 'sell'): ?> current active<?php endif; ?> dropdown'>
			
			<!--<a href='/admin/ecommerces/index'><span><?php __("E-commerce"); ?></span></a>-->
			
			<span><span><?php __("E-commerce"); ?></span></span>
			
			<div class="tab_left_cover"></div>
			<div class="angle_white"></div>
			<div class="tab_bottom"><div class="tab_bottom_triangle"></div><div class="active_color"><div class="active_color_triangle"></div></div></div>
			
			<?php
				$subnav = array(); 

				$subnav['title'] = array(
					'name' => 'Sell',
					'url' => "/admin/ecommerces",
				);
				$subnav['pages'][] = array(
					'name' => __('E-commerce Settings', true),
					'url' => "/admin/ecommerces/index/",
					'icon_css' => 'PageSettings-01',
				);
				$subnav['pages'][] = array(
					'name' => __('Manage Print Sizes', true),
					'url' => array(
						"/admin/ecommerces/manage_print_sizes/",
						"/admin/ecommerces/add_print_size/",
					),
					'icon_css' => 'ManagePrintSize-01-01',
				);
				$subnav['pages'][] = array(
					'name' => __('Manage Print Types & Default Pricing', true),
					'url' => array(
						"/admin/ecommerces/manage_print_types_and_pricing",
						"/admin/ecommerces/add_print_type_and_pricing",
					),
					'icon_css' => 'ManagePrintMaterial-01-01',
				);
				$subnav['pages'][] = array(
					'name' => __('Manage Orders', true),
					'url' => array(
						"/admin/ecommerces/order_management",
						"/admin/ecommerces/fulfill_order",
					),
					'icon_css' => 'Clipboard-01-01',
				);
				$subnav['pages'][] = array(
					'name' => __('Receive Payment', true),
					'url' => "/admin/ecommerces/get_paid/",
					'icon_css' => 'receiveMoney-01',
				);

				echo $this->Element('/admin/submenu', array( 'subnav' => $subnav, 'curr_page' => $curr_page ));
			?>
		</li><!--
		--><li class='galleries <?php if ($curr_page == 'galleries'): ?> current active<?php endif; ?> link'>
			<a href='/admin/photo_galleries/manage'><span><?php __("Galleries"); ?></span></a>
			<div class="tab_left_cover"></div>
			<div class="angle_white"></div>
			<div class="tab_bottom"><div class="tab_bottom_triangle"></div><div class="active_color"><div class="active_color_triangle"></div></div></div>
		</li><!--
		--><li class='photos <?php if ($curr_page == 'photos'): ?> current active<?php endif; ?> link'>
			<a href='/admin/photos'><span><?php __("Photos"); ?></span></a>
			<div class="tab_left_cover"></div>
			<div class="angle_white"></div>
			<div class="tab_bottom"><div class="tab_bottom_triangle"></div><div class="active_color"><div class="active_color_triangle"></div></div></div>
		</li><!--
		--><li class='pages <?php if ($curr_page == 'pages'): ?> current active<?php endif; ?> link'>
			<a href='/admin/site_pages'><span><?php __("Pages"); ?></span></a>
			<div class="tab_left_cover"></div>
			<div class="angle_white"></div>
			<div class="tab_bottom"><div class="tab_bottom_triangle"></div><div class="active_color"><div class="active_color_triangle"></div></div></div>
			<div class="last_tab">&nbsp;</div>
		</li><!--
		-->
	</ul>
</div>