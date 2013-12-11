<?php foreach ($submenu_items as $submenu_item): ?>
	<div class="sub_menu_item" site_two_level_menu_container_item_id="<?php echo $submenu_item['id']; ?>">
		<span class="delete_sub_menu_item_button" style="float: right; cursor: pointer;">delete</span>
		<h3><?php echo $submenu_item['name']; ?> (item)</h3>
	</div>
<?php endforeach; ?>