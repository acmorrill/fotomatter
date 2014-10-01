<?php foreach($two_level_menu_items as $menu_item_key => $two_level_menu_item): ?> 
	<?php $menu_item_data = $this->ThemeMenu->get_menu_item_data($two_level_menu_item['SiteTwoLevelMenu'], $two_level_menu_item); ?>
	<?php
		$class = 'single_item';
		if ($menu_item_data['type'] == 'SiteTwoLevelMenuContainer') {
			$class = 'container_item';
		}
	?>
	<tr class="<?php echo $class; ?> top_level_item custom_ui" top_level_site_two_level_menu_id="<?php echo $menu_item_data['id']; ?>" <?php if ($menu_item_data['type'] == 'SiteTwoLevelMenuContainer'): ?>site_two_level_menu_container_id="<?php echo $two_level_menu_item['SiteTwoLevelMenu']['external_id']; ?>"<?php endif; ?>>
<!--		<img class="abs_image_tl order_in_two_level_menu_button" src="/img/admin/icons/white_arrange.png" />
		<img class="abs_image_tr remove_from_two_level_menu_button" src="/img/admin/icons/bw_simple_close_icon.png" />-->
		<?php if ($class == 'container_item'): ?>
			<!--<h2 class="rounded-corners no-bottom-rounded container_name" style="position: absolute; right: 20px; top: -38px; color: white; background-color: #636363; padding: 10px; padding-top: 7px;"><?php echo $menu_item_data['name']; ?> (container)</h2>-->
			<td class="last" colspan="3">
				<div class="background">
					<div class="order_in_two_level_menu_button reorder_grabber"></div>
				</div>
				<span><?php echo $menu_item_data['name']; ?></span>
				<div class="remove_from_two_level_menu_button add_button icon"><div class="content">X</div></div>
				<?php 
					echo $this->Element('admin/theme_center/main_menu/two_level_menu_container_item', array(
						'submenu_items' => $menu_item_data['submenu_items']
					)); 
				?>
			</td>
		<?php else: ?>
			<td class="first table_width_reorder_icon">
				<div class="background">
					<div class="order_in_two_level_menu_button reorder_grabber"></div>
				</div>
			</td>
			<td class="menu_type">
				<div class="rightborder"></div><span><?php echo $menu_item_data['name']; ?></span>
			</td>
			<td class="last table_actions">
				<div class="remove_from_two_level_menu_button add_button icon"><div class="content">X</div></div>
			</td>
		<?php endif; ?>
	</tr>


	<?php //debug($menu_item_data); ?>
<?php endforeach; ?>