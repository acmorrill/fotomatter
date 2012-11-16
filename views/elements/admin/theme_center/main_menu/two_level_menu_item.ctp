<?php foreach($two_level_menu_items as $menu_item_key => $two_level_menu_item): ?> 
	<?php $menu_item_data = $this->ThemeMenu->get_menu_item_data($two_level_menu_item['SiteTwoLevelMenu'], $two_level_menu_item); ?>
	<?php
		$class = 'single_item';
		if ($menu_item_data['type'] == 'SiteTwoLevelMenuContainer') {
			$class = 'container_item';
		}
	?>
	<div class="<?php echo $class; ?> top_level_item" top_level_site_two_level_menu_id="<?php echo $menu_item_data['id']; ?>">
		<img class="abs_image_tl order_in_two_level_menu_button" src="/img/admin/icons/white_arrange.png" />
		<img class="abs_image_tr remove_from_two_level_menu_button" src="/img/admin/icons/bw_simple_close_icon.png" />
		<?php if ($class == 'container_item'): ?>
			<?php /*<h2><?php echo $menu_item_data['name']; ?> (container)</h2>*/ ?>
			<?php 
				echo $this->Element('admin/theme_center/main_menu/two_level_menu_container_item', array(
					'submenu_items' => $menu_item_data['submenu_items']
				)); 
			?>
		<?php else: ?>
			<h2><?php echo $menu_item_data['name']; ?> (item)</h2>
		<?php endif; ?>
	</div>


	<?php //debug($menu_item_data); ?>
<?php endforeach; ?>