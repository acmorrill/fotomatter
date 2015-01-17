<?php
	if (empty($curr_page)) {
		$curr_page = 'theme_center';
	}
?>
<div class="menu_below_bar"></div>
<div id="top-navigation">
	<script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery('#extra_buttons .submenu').click(function(e) {
				e.preventDefault();
				jQuery(this).parent().toggleClass('open');
			});
			jQuery('#extra_buttons > li').click(function(e) {
				e.stopPropagation();
			});
			jQuery(document).click(function() {
				jQuery('#extra_buttons > li').removeClass('open');
			});
		});
	</script>
	<ul id="extra_buttons">
		<li>
			<a href="/admin/users/logout"><i class="icon-power"></i>Log Out</a>
		</li>
		<li class="<?php if ($curr_page == 'site_settings'): ?> active<?php endif; ?>">
			<a href="" class="submenu"><i class="icon-cogWheel"></i><?php echo __('Site Settings', true); ?></a>
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
			</ul>
		</li>
		<li class="<?php if ($curr_page == 'mass_upload'): ?> active<?php endif; ?>">
			<a href="/admin/photos/mass_upload"><i class="icon-pictureUpload-01"></i><?php echo __('Upload Photos', true); ?></a>
		</li>
		<li class="<?php if ($curr_page == 'add_features'): ?> active<?php endif; ?>">
			<a href="/admin/accounts/index"><i class="icon-manageFeatures-01"></i><?php echo __ ('Manage Features', true); ?></a>
		</li>
	</ul>
	<ul class="menu">
		<?php /*<li class='dashboard <?php if ($curr_page == 'dashboard'): ?> active<?php endif; ?>'>
			<a href='/admin/dashboards'><span><?php __("Dashboard"); ?></span></a>
			<div class="tab_bottom"><div class="tab_bottom_triangle"></div><div class="active_color"><div class="active_color_triangle"></div></div></div>
		</li> */ ?><!--
		--><li class='theme_center <?php if ($curr_page == 'theme_center'): ?> active<?php endif; ?>'>
			<a href='/admin/theme_centers/choose_theme'><span><?php __("Theme Center"); ?></span></a>
			<?php /*<div class="tab_left_cover"></div>
			<div class="angle_white"></div> */ ?>
			<div class="tab_bottom"><div class="tab_bottom_triangle"></div><div class="active_color"><div class="active_color_triangle"></div></div></div>
		</li><!--
		--><li class='e-commerce <?php if ($curr_page == 'sell'): ?> active<?php endif; ?>'>
			<a href='/admin/ecommerces/index'><span><?php __("E-commerce"); ?></span></a>
			<div class="tab_left_cover"></div>
			<div class="angle_white"></div>
			<div class="tab_bottom"><div class="tab_bottom_triangle"></div><div class="active_color"><div class="active_color_triangle"></div></div></div>
		</li><!--
		--><li class='galleries <?php if ($curr_page == 'galleries'): ?> active<?php endif; ?>'>
			<a href='/admin/photo_galleries'><span><?php __("Galleries"); ?></span></a>
			<div class="tab_left_cover"></div>
			<div class="angle_white"></div>
			<div class="tab_bottom"><div class="tab_bottom_triangle"></div><div class="active_color"><div class="active_color_triangle"></div></div></div>
		</li><!--
		--><li class='photos <?php if ($curr_page == 'photos'): ?> active<?php endif; ?>'>
			<a href='/admin/photos'><span><?php __("Photos"); ?></span></a>
			<div class="tab_left_cover"></div>
			<div class="angle_white"></div>
			<div class="tab_bottom"><div class="tab_bottom_triangle"></div><div class="active_color"><div class="active_color_triangle"></div></div></div>
		</li><!--
		--><li class='pages <?php if ($curr_page == 'pages'): ?> active<?php endif; ?>'>
			<a href='/admin/site_pages'><span><?php __("Pages"); ?></span></a>
			<div class="tab_left_cover"></div>
			<div class="angle_white"></div>
			<div class="tab_bottom"><div class="tab_bottom_triangle"></div><div class="active_color"><div class="active_color_triangle"></div></div></div>
			<div class="last_tab">&nbsp;</div>
		</li><!--
		-->
	<!--	<li class='site_settings <?php if ($curr_page == 'site_settings'): ?> active<?php endif; ?>'>
			<a href='/admin/accounts'><?php __("Site Settings"); ?></a>
			<div class="tab_left_cover"></div>
			<div class="angle_white"></div>
			<div class="tab_bottom"><div class="tab_bottom_triangle"></div><div class="active_color"><div class="active_color_triangle"></div></div></div>
		</li>-->
	</ul>
</div>