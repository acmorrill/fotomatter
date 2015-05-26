<select class="add_to_container_list <?php if (isset($hide_top_level)): ?>hide_top_level<?php endif; ?>">
	<?php if (!isset($hide_top_level)): ?>
		<option value="top_level">Top Level</option>
	<?php endif; ?>
	<?php foreach ($all_containers as $all_container): ?>
		<option value="<?php echo $all_container['SiteTwoLevelMenuContainer']['id']; ?>" site_two_level_menu_id="<?php echo $all_container['SiteTwoLevelMenu']['id']; ?>"><?php echo $all_container['SiteTwoLevelMenuContainer']['display_name']; ?> <?php echo __('Container', true); ?></option>
	<?php endforeach; ?>
</select>