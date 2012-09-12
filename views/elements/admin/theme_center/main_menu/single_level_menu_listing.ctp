<div>
	<div class="single_level_menu_items_cont menu_items_cont" style="padding: 20px;">
		<?php $single_menu_items = $this->ThemeMenu->get_single_menu_items(); ?>
		<?php //debug($single_menu_items); ?>
		
		<table class="list">
			<tbody>
				<?php foreach($single_menu_items as $menu_item_key => $single_menu_item): ?> 
					<?php $menu_item_data = $this->ThemeMenu->get_menu_item_data($single_menu_item); ?>
					<tr site_one_level_menu_id="<?php echo $single_menu_item['SiteOneLevelMenu']['id']; ?>">
						<td class="gallery_id first"><div class="reorder_gallery_grabber reorder_grabber" /> </td> 
						<td><?php echo $menu_item_data['name']; ?></td>
						<td>delete</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>

	<div class="generic_sort_and_filters" style="position: absolute; bottom: -91px; left: 0px; right: 0px; height: auto;">
		<script type="text/javascript">
			jQuery(document).ready(function() { 
				jQuery('#single_menu_page_add_button').click(function() { 
					var select_box = jQuery(this).parent().find('#single_menu_page_add_list');

					// TODO next - use site_menus controller to add pages to menu

					console.log (select_box.val());
				});
				
				
				jQuery('#single_menu_gallery_add_button').click(function() { 
					var select_box = jQuery(this).parent().find('#single_menu_gallery_add_list');

					// TODO next - use site_menus controller to add galleries to menu

					console.log (select_box.val());
				});
			});
		</script>

		<?php $all_pages = $this->Page->get_all_pages(); ?>
		<div class="custom_ui" style="margin: 5px; margin-bottom: 15px;">
			<span><?php __('Add Page To Menu:'); ?></span>
			<select id="single_menu_page_add_list">
				<?php foreach ($all_pages as $curr_page): ?>
					<option value="<?php echo $curr_page['SitePage']['id']; ?>"><?php echo $curr_page['SitePage']['title']; ?></option>
				<?php endforeach; ?>
			</select>
			<input id="single_menu_page_add_button" class="add_button" type="submit" value="<?php __('Go'); ?>" />
		</div>
		
		<?php $all_galleries = $this->Gallery->get_all_galleries(); ?>
		<div class="custom_ui" style="margin: 5px; margin-bottom: 15px;">
			<span><?php __('Add Gallery To Menu:'); ?></span>
			<select id="single_menu_gallery_add_list">
				<?php foreach ($all_galleries as $curr_gallery): ?>
					<option value="<?php echo $curr_gallery['PhotoGallery']['id']; ?>"><?php echo $curr_gallery['PhotoGallery']['display_name']; ?></option>
				<?php endforeach; ?>
			</select>
			<input id="single_menu_gallery_add_button" class="add_button" type="submit" value="<?php __('Go'); ?>" />
		</div>
		
		
		<?php /*
		<div id="photos_not_in_a_gallery_cont" class="custom_ui_radio" style="margin-bottom: 7px;">
			<input type="checkbox" id="photos_not_in_a_gallery" /><label for="photos_not_in_a_gallery"><?php __('Photos Not In A Gallery'); ?></label>
		</div>
		 */ ?>
	</div>
</div>
