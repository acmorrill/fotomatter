<div id="top-navigation">
	<div id="extra_buttons">
		these are the extra buttons
	</div>
	<ul>
		<?php //echo $curr; ?>
		<?php
			if (empty($curr_page)) {
				$curr_page = 'dashboard';
			}
		?>
		<li class='dashboard <?php if ($curr_page == 'dashboard'): ?> active<?php endif; ?>'>
			<a href='/admin/dashboards'><span><?php __("Dashboard"); ?></span></a>
			<div class="tab_bottom"><div class="tab_bottom_triangle"></div><div class="active_color"><div class="active_color_triangle"></div></div></div>
		</li><!--
		--><li class='theme_center <?php if ($curr_page == 'theme_center'): ?> active<?php endif; ?>'>
			<a href='/admin/theme_centers'><span><?php __("Theme Center"); ?></span></a>
			<div class="tab_left_cover"></div>
			<div class="angle_white"></div>
			<div class="tab_bottom"><div class="tab_bottom_triangle"></div><div class="active_color"><div class="active_color_triangle"></div></div></div>
		</li><!--
		--><li class='e-commerce <?php if ($curr_page == 'sell'): ?> active<?php endif; ?>'>
			<a href='/admin/ecommerces'><span><?php __("E-commerce"); ?></span></a>
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