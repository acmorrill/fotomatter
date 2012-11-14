<?php foreach($two_level_menu_items as $menu_item_key => $two_level_menu_item): ?> 
	<?php $menu_item_data = $this->ThemeMenu->get_menu_item_data($two_level_menu_item['SiteTwoLevelMenu'], $two_level_menu_item); ?>
	<?php
		$class = 'single_item';
		if ($menu_item_data['type'] == 'SiteTwoLevelMenuContainer') {
			$class = 'container_item';
		}
	?>
	<div class="<?php echo $class; ?> top_level_item" site_two_level_menu_id="<?php echo $menu_item_data['id']; ?>">
		<img class="abs_image_tl order_in_two_level_menu_button" src="/img/admin/icons/white_arrange.png" />
		<img class="abs_image_tr remove_from_two_level_menu_button" src="/img/admin/icons/bw_simple_close_icon.png" />
		<?php if ($class == 'container_item'): ?>
			<?php /*<h2><?php echo $menu_item_data['name']; ?> (container)</h2>*/ ?>
			<?php foreach ($menu_item_data['submenu_items'] as $submenu_item): ?>
				<div class="sub_menu_item" site_two_level_menu_container_item_id="<?php echo $submenu_item['id']; ?>">
					<span class="delete_sub_menu_item_button" style="float: right; cursor: pointer;">delete</span>
					<h3><?php echo $submenu_item['name']; ?> (item)</h3>
				</div>
			<?php endforeach; ?>
		<?php else: ?>
			<h2><?php echo $menu_item_data['name']; ?> (item)</h2>
		<?php endif; ?>
	</div>


	<?php //debug($menu_item_data); ?>
<?php endforeach; ?>