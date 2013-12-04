<ul id="top-navigation">
	<?php //echo $curr; ?>
	<?php
		if (empty($curr_page)) {
			$curr_page = 'dashboard';
		}
	?>
	<li class='dashboard <?php if ($curr_page == 'dashboard'): ?> active<?php endif; ?>'>
		<a href='/admin/dashboards'><?php __("Dashboard"); ?></a>
		<div class="tab_bottom"><div class="tab_bottom_triangle"></div><div class="active_color"><div class="active_color_triangle"></div></div></div>
	</li><!--
	--><li class='theme_center <?php if ($curr_page == 'theme_center'): ?> active<?php endif; ?>'>
		<a href='/admin/theme_centers'><?php __("Theme Center"); ?></a>
		<div class="tab_left_cover"></div>
		<div class="angle_white"></div>
		<div class="tab_bottom"><div class="tab_bottom_triangle"></div><div class="active_color"><div class="active_color_triangle"></div></div></div>
	</li><!--
	--><li class='e-commerce <?php if ($curr_page == 'sell'): ?> active<?php endif; ?>'>
		<a href='/admin/ecommerces'><?php __("e-commerce"); ?></a>
		<div class="tab_left_cover"></div>
		<div class="angle_white"></div>
		<div class="tab_bottom"><div class="tab_bottom_triangle"></div><div class="active_color"><div class="active_color_triangle"></div></div></div>
	</li><!--
	--><li class='galleries <?php if ($curr_page == 'galleries'): ?> active<?php endif; ?>'>
		<a href='/admin/photo_galleries'><?php __("Galleries"); ?></a>
		<div class="tab_left_cover"></div>
		<div class="angle_white"></div>
		<div class="tab_bottom"><div class="tab_bottom_triangle"></div><div class="active_color"><div class="active_color_triangle"></div></div></div>
	</li><!--
	--><li class='photos <?php if ($curr_page == 'photos'): ?> active<?php endif; ?>'>
		<a href='/admin/photos'><?php __("Photos"); ?></a>
		<div class="tab_left_cover"></div>
		<div class="angle_white"></div>
		<div class="tab_bottom"><div class="tab_bottom_triangle"></div><div class="active_color"><div class="active_color_triangle"></div></div></div>
	</li><!--
	--><li class='pages <?php if ($curr_page == 'pages'): ?> active<?php endif; ?>'>
		<a href='/admin/site_pages'><?php __("Pages"); ?></a>
		<div class="tab_left_cover"></div>
		<div class="angle_white"></div>
		<div class="tab_bottom"><div class="tab_bottom_triangle"></div><div class="active_color"><div class="active_color_triangle"></div></div></div>
	</li><!--
	--><li class='site_settings <?php if ($curr_page == 'site_settings'): ?> active<?php endif; ?>'>
		<a href='/admin/accounts'><?php __("Site Settings"); ?></a>
		<div class="tab_left_cover"></div>
		<div class="angle_white"></div>
		<div class="tab_bottom"><div class="tab_bottom_triangle"></div><div class="active_color"><div class="active_color_triangle"></div></div></div>
	</li>
</ul>
