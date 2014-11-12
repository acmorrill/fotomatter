<?php
	if (empty($do_not_sort_items)) {
		$do_not_sort_items = array();
	}
?>
<?php foreach($single_menu_items as $menu_item_key => $single_menu_item): ?> 
	<?php $menu_item_data = $this->ThemeMenu->get_menu_item_data($single_menu_item['SiteOneLevelMenu'], $single_menu_item); ?>
	<tr class="<?php if (!in_array($single_menu_item['SiteOneLevelMenu']['id'], $do_not_sort_items)): ?>sortable_menu_item <?php else: ?> not_sortable_menu_item<?php endif; ?>" data-step="4" data-intro="<?php echo __("All themes have system pages that are required to have and can't be moved from thier current position", true); ?>" data-position="top" site_one_level_menu_id="<?php echo $single_menu_item['SiteOneLevelMenu']['id']; ?>">
		<td class="single_level_menu_id first table_width_reorder_icon">
			<div class="background"><div class="reorder_single_level_menu_grabber reorder_grabber icon-position-01"></div></div>
		</td>
		<td class="menu_type">
			<div class="rightborder"></div><span><?php echo $menu_item_data['display_type']; ?></span>
		</td>
		<td>
			<span><?php echo $menu_item_data['name']; ?></span>
		</td>
		<td class="table_actions custom_ui">
			<?php if ($menu_item_data['display_type'] != 'System'): ?>
				<div class="delete_one_level_menu_item add_button icon icon_close"><div class="content icon-close-01"></div></div>
			<?php endif; ?>
		</td>
	</tr>
<?php endforeach; ?>