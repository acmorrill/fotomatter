<script type="text/javascript">
	function setup_one_level_menu_sortable(selector) {
		jQuery(selector).sortable(jQuery.extend(verticle_sortable_defaults, {
			items : 'tr.sortable_menu_item',
			handle : '.reorder_single_level_menu_grabber',
			update : function(event, ui) {
				var context = this;
				jQuery(context).sortable('disable');
				
				// figure the the new position of the dragged element
				var siteOneLevelMenuId = jQuery(ui.item).attr('site_one_level_menu_id');
				var newPosition = position_of_element_among_siblings(jQuery('.single_level_menu_items_cont .list tbody tr'), jQuery(ui.item));
				
				jQuery.ajax({
					type: 'post',
					url: '/admin/site_menus/ajax_set_site_single_level_order/'+siteOneLevelMenuId+'/'+newPosition+'/',
					data: {},
					success: function(data) {
						if (data.code != 1) {
							// DREW TODO - maybe revert the draggable back to its start position here
						}
					},
					complete: function() {
						jQuery(context).sortable('enable');
					},
					dataType: 'json'
				});
			}
		})).disableSelection();
	}
	
	function setup_one_level_menu_item_delete(selector) {
		jQuery(selector).click(function() {
			var tr_to_remove = jQuery(this).closest('tr');
			var site_one_level_menu_id_to_delete = tr_to_remove.attr('site_one_level_menu_id');
			
			jQuery.ajax({
				type: 'post',
				url: '/admin/site_menus/ajax_delete_one_level_menu_item/'+site_one_level_menu_id_to_delete+'/',
				data: {},
				success: function(data) {
					if (data.code == 1) {
						tr_to_remove.remove();
					} else {
						major_error_recover('Failed to delete the menu item');
					}
				},
				complete: function() {
					
				},
				dataType: 'json'
			});	
		});
	}
	
	
	jQuery(document).ready(function() {
		setup_one_level_menu_sortable('.list tbody');

		setup_one_level_menu_item_delete('.single_level_menu_items_cont .delete_one_level_menu_item');

	});
</script>

<div class="large_container">
	<div class="table_border">
		<?php $single_menu_items = $this->ThemeMenu->get_single_menu_items(); ?>
		<?php $do_not_sort_items = array($single_menu_items[0]['SiteOneLevelMenu']['id']); ?>
		<table class="list">
			<tbody>
				<?php echo $this->Element('admin/theme_center/main_menu/single_level_menu_item', array('single_menu_items' => $single_menu_items, 'do_not_sort_items' => $do_not_sort_items)); ?>
			</tbody>
		</table>
	</div>
	<div>
		<script type="text/javascript">
			jQuery(document).ready(function() { 
				jQuery('#single_menu_page_add_button').click(function() { 
					var select_box = jQuery(this).parent().find('#single_menu_page_add_list');
					var site_page_id = select_box.val();

					jQuery.ajax({
						type: 'post',
						url: '/admin/site_menus/add_one_level_menu_item/SitePage/'+site_page_id+'/',
						data: {},
						success: function(data) {
							if (data.code == 1) {
								var new_menu_item = jQuery(data.new_menu_item_html);
								setup_one_level_menu_item_delete(new_menu_item);
								move_to_cont = jQuery('.single_level_menu_items_cont .list tbody');
								move_to_cont.append(new_menu_item);

								// move scoll to new menu item
								var menu_cont = jQuery(move_to_cont).closest('.content-background');
								menu_cont.scrollTop(menu_cont.prop("scrollHeight"));
							} else {
								major_error_recover('Failed to add the page menu item');
							}
						},
						complete: function() {

						},
						dataType: 'json'
					});	
				});


				jQuery('#single_menu_gallery_add_button').click(function() { 
					var select_box = jQuery(this).parent().find('#single_menu_gallery_add_list');
					var photo_gallery_id = select_box.val();

					jQuery.ajax({
						type: 'post',
						url: '/admin/site_menus/add_one_level_menu_item/PhotoGallery/'+photo_gallery_id+'/',
						data: {},
						success: function(data) {
							if (data.code == 1) {
								var new_menu_item = jQuery(data.new_menu_item_html);
								setup_one_level_menu_item_delete(new_menu_item);
								var move_to_cont = jQuery('.single_level_menu_items_cont .list tbody');
								move_to_cont.append(new_menu_item);

								// move scoll to new menu item
								var menu_cont = jQuery(move_to_cont).closest('.content-background');
								menu_cont.scrollTop(menu_cont.prop("scrollHeight"));
							} else {
								major_error_recover('Failed to add the gallery menu item');
							}
						},
						complete: function() {

						},
						dataType: 'json'
					});	
				});
			});
		</script>

		<?php $all_pages = $this->Page->get_all_pages(); ?>
		<div class="custom_ui add_page_menu">
			<span class="add_pages_galleries"><?php __('Add Information Page To Main Menu'); ?></span>
			<div class="drop_down_sub_title">	
				<select id="single_menu_page_add_list">
					<?php foreach ($all_pages as $curr_page): ?>
						<option value="<?php echo $curr_page['SitePage']['id']; ?>"><?php echo $curr_page['SitePage']['title']; ?></option>
					<?php endforeach; ?>
				</select>
				<div id="single_menu_page_add_button" class="add_button_main_menu"><span class="text_add_button"><?php __('Add'); ?></span> <span class="plus_symbol"></span> </div>
			</div>
		</div>
		<hr class="hr_element">
		<?php $all_galleries = $this->Gallery->get_all_galleries(); ?>
		<div class="custom_ui add_page_gallery">
			<span class="add_pages_galleries"><?php __('Add Gallery Page To Main Menu'); ?></span>
			<div class="drop_down_sub_title">
				<select id="single_menu_gallery_add_list">
					<?php foreach ($all_galleries as $curr_gallery): ?>
						<option value="<?php echo $curr_gallery['PhotoGallery']['id']; ?>"><?php echo $curr_gallery['PhotoGallery']['display_name']; ?></option>
					<?php endforeach; ?>
				</select>

				<div id="single_menu_gallery_add_button" class="add_button_main_menu"><span class="text_add_button"><?php __('Add'); ?></span><span class="plus_symbol"></span></div>
			</div>	
		</div>


		<?php /*
		<div id="photos_not_in_a_gallery_cont" class="custom_ui_radio" style="margin-bottom: 7px;">
			<input type="checkbox" id="photos_not_in_a_gallery" /><label for="photos_not_in_a_gallery"><?php __('Photos Not In A Gallery'); ?></label>
		</div>
		 */ ?>
	</div>
</div>
