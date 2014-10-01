<?php if (!empty($submenu_items)): ?>
	<ul>
		<?php foreach ($submenu_items as $submenu_item): ?>
			<li class="sub_menu_item" site_two_level_menu_container_item_id="<?php echo $submenu_item['id']; ?>">
				<div class="add_button icon delete_sub_menu_item_button"><div class="content">X</div></div>
				<div class="main_menu_submenu_grabber"></div>
				<h3><?php echo $submenu_item['name']; ?> (item)</h3>
				<div style="clear: both;"></div>
			</li>
		<?php endforeach; ?>
	</ul>
<?php endif; ?>



