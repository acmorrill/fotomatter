<?php foreach ($submenu_items as $submenu_item): ?>
	<li class="sub_menu_item" site_two_level_menu_container_item_id="<?php echo $submenu_item['id']; ?>">
		<div class="add_button icon delete_sub_menu_item_button icon_close"><div class="content icon-close-01"></div></div>
		<div class="main_menu_submenu_grabber"></div>
		<?php
			$type_text = '';
			if ($submenu_item['type'] == 'PhotoGallery') {
				$type_text = 'Gallery';
			} else if ($submenu_item['type'] == 'SitePage') {
				$type_text = 'Page';
			}
		?>
		<h3><?php echo $submenu_item['name']; ?> <?php echo $type_text; ?></h3>
		<div style="clear: both;"></div>
	</li>
<?php endforeach; ?>



