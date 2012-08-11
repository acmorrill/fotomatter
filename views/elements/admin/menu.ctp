<ul id="top-navigation">
	<?php //echo $curr; ?>
	<?php
		if (empty($curr_page)) {
			$curr_page = 'dashboard';
		}
	?>
	<li class='<?php if ($curr_page == 'dashboard'): ?> curr<?php endif; ?>'><a href='/admin/dashboards'><?php __("DASHBOARD"); ?></a></li>
	<li class='<?php if ($curr_page == 'theme_center'): ?> curr<?php endif; ?>'><a href='/admin/theme_centers'><?php __("THEME CENTER"); ?></a></li>
	<li class='<?php if ($curr_page == 'galleries'): ?> curr<?php endif; ?>'><a href='/admin/photo_galleries'><?php __("GALLERIES"); ?></a></li>
	<?php /*<li class='<?php if ($curr_page == 'photo_groups'): ?> curr<?php endif; ?>'><a href='/admin/photo_groups'><?php __("PHOTO GROUPS"); ?></a></li>*/ ?>
	<li class='<?php if ($curr_page == 'photos'): ?> curr<?php endif; ?>'><a href='/admin/photos'><?php __("PHOTOS"); ?></a></li>
	<li class='<?php if ($curr_page == 'pages'): ?> curr<?php endif; ?>'><a href='/admin/site_pages'><?php __("PAGES"); ?></a></li>
	<li class='<?php if ($curr_page == 'users'): ?> curr<?php endif; ?>'><a href='/admin/users'><?php __("USERS"); ?></a></li>
</ul>
