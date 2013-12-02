<ul id="top-navigation">
	<?php //echo $curr; ?>
	<?php
		if (empty($curr_page)) {
			$curr_page = 'dashboard';
		}
	?>
	<li class='dashboard <?php if ($curr_page == 'dashboard'): ?> curr<?php endif; ?>'><a href='/admin/dashboards'><?php __("DASHBOARD"); ?></a></li>
	<li class='theme_center <?php if ($curr_page == 'theme_center'): ?> curr<?php endif; ?>'><a href='/admin/theme_centers'><?php __("THEME CENTER"); ?></a></li>
	<li class='e-commerce <?php if ($curr_page == 'e-commerce'): ?> curr<?php endif; ?>'><a href='/admin/ecommerces'><?php __("E-COMMERCE"); ?></a></li>
	<li class='galleries <?php if ($curr_page == 'galleries'): ?> curr<?php endif; ?>'><a href='/admin/photo_galleries'><?php __("GALLERIES"); ?></a></li>
	<li class='photos <?php if ($curr_page == 'photos'): ?> curr<?php endif; ?>'><a href='/admin/photos'><?php __("PHOTOS"); ?></a></li>
	<li class='pages <?php if ($curr_page == 'pages'): ?> curr<?php endif; ?>'><a href='/admin/site_pages'><?php __("PAGES"); ?></a></li>
	<li class='site_settings <?php if ($curr_page == 'site_settings'): ?> curr<?php endif; ?>'><a href='/admin/accounts'><?php __("SITE SETTINGS"); ?></a></li>
</ul>
