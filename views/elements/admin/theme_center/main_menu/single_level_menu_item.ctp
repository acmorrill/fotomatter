<?php foreach($single_menu_items as $menu_item_key => $single_menu_item): ?> 
	<?php $menu_item_data = $this->ThemeMenu->get_menu_item_data($single_menu_item); ?>
	<tr site_one_level_menu_id="<?php echo $single_menu_item['SiteOneLevelMenu']['id']; ?>">
		<td class="single_level_menu_id first"><div class="reorder_single_level_menu_grabber reorder_grabber" /> </td> 
		<td><?php echo $menu_item_data['name']; ?></td>
		<td class="delete_one_level_menu_item" style="cursor: pointer;">delete</td>
	</tr>
<?php endforeach; ?>