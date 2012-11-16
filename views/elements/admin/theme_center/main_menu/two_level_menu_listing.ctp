<?php 
/*
 * DREW TODO - still need to finish a few things in this file
 *  1) make it so you can add a menu container
 *  2) make it so you can add an item to the top level or to a container
 *  3) make it so the reorder of container items is saved -- done
 *  4) make it so you can delete container items
 * 
 */

?>

<script type="text/javascript">
	function setup_two_level_menu_item_delete(selector) {
		jQuery(selector).click(function() {
			var top_level_item = jQuery(this).closest('.top_level_item');
			var site_two_level_menu_id_to_delete = top_level_item.attr('site_two_level_menu_id');
		
			jQuery.ajax({
				type: 'post',
				url: '/admin/site_menus/ajax_delete_two_level_menu_item/'+site_two_level_menu_id_to_delete,
				data: {},
				success: function(the_data) {
					if (the_data.code == 1) {
						// remove the div
						top_level_item.remove();
					} else {
						major_error_recover(data.message);
					}
				},
				complete: function() {
					//				console.log ("complete");
				},
				error: function(jqXHR, textStatus, errorThrown) {
					//				console.log ("error");
					//				console.log (textStatus);
					//				console.log (errorThrown);
				},
				dataType: 'json'
			});	
		});
	}
	
	jQuery(document).ready(function() {
		jQuery('.two_level_menu_items_cont').sortable({
			handle: '.order_in_two_level_menu_button',
			axis: 'y',
			containment: 'parent',
			items: '.top_level_item',
			forcePlaceholderSize: true,
			tolerance: 'pointer',
			scrollSensitivity: 120,
			scrollSpeed: 5,
			update : function(event, ui) {
				var context = this;
				jQuery(context).sortable('disable');
				
				// figure the new position of the dragged element
				var site_two_level_menu_id = jQuery(ui.item).attr('site_two_level_menu_id');
				var new_index = ui.item.index();
				var newPosition = position_of_element_among_siblings(jQuery('.top_level_item', context), jQuery(ui.item));
				//var newPosition = new_index + 1; // DREW TODO - change this to use - var newPosition = position_of_element_among_siblings(jQuery('.page_element_cont', this), jQuery(ui.item));
				
				jQuery.post('/admin/site_menus/ajax_set_site_two_level_order/'+site_two_level_menu_id+'/'+newPosition+'/', function(data) {
					if (data.code == 1) {
						// its all good
					} else {
						major_error_recover(data.message);
					}
					jQuery(context).sortable('enable');
				}, 'json');
		
			}
		});
		
		
		jQuery('.two_level_menu_items_cont .container_item').sortable({
			axis: 'y',
			containment: 'parent',
			items: '.sub_menu_item',
			forcePlaceholderSize: true,
			tolerance: 'pointer',
			scrollSensitivity: 60,
			scrollSpeed: 3,
			cursor: 'move',
			scroll: false,
			update : function(event, ui) {
				var context = this;
				jQuery(context).sortable('disable');
				
				// figure the new position of the dragged element
				var site_two_level_menu_container_item_id = jQuery(ui.item).attr('site_two_level_menu_container_item_id');
				var newPosition = position_of_element_among_siblings(jQuery('.sub_menu_item', context), jQuery(ui.item));
				
				jQuery.post('/admin/site_menus/ajax_set_menu_item_order_in_container/'+site_two_level_menu_container_item_id+'/'+newPosition+'/', function(data) {
					if (data.code == 1) {
						// its all good
					} else {
						major_error_recover(data.message);
					}
					jQuery(context).sortable('enable');
				}, 'json');
		
			}
		});
		
		jQuery('.two_level_menu_items_cont .container_item .sub_menu_item .delete_sub_menu_item_button').click(function() {
			var sub_menu_item = jQuery(this).closest('.sub_menu_item');
			var two_level_menu_container_item_id_to_delete = sub_menu_item.attr('site_two_level_menu_container_item_id');
			
			console.log (two_level_menu_container_item_id_to_delete);
			
			jQuery.ajax({
				type: 'post',
				url: '/admin/site_menus/ajax_delete_sub_menu_item/'+two_level_menu_container_item_id_to_delete+'/',
				data: {},
				success: function(data) {
					if (data.code == 1) {
						// remove the item
						sub_menu_item.remove();
					} else {
						major_error_recover(data.message);
					}
				},
				complete: function() {

				},
				error: function(jqXHR, textStatus, errorThrown) {

				},
				dataType: 'json'
			});
		});
		
		
		setup_two_level_menu_item_delete('.two_level_menu_items_cont .top_level_item .remove_from_two_level_menu_button');
		
		
	});
</script>

<div style="margin-bottom: 100px;">
	<div class="two_level_menu_items_cont menu_items_cont">
		<?php $two_level_menu_items = $this->ThemeMenu->get_two_level_menu_items(); ?>
		<?php //debug($two_level_menu_items); ?>
		
		<?php echo $this->Element('admin/theme_center/main_menu/two_level_menu_item', array('two_level_menu_items' => $two_level_menu_items)); ?>
	</div>
	

	<div class="generic_sort_and_filters" style="position: absolute; bottom: -91px; left: 0px; right: 0px;">
		<script type="text/javascript">
			jQuery(document).ready(function() {
				jQuery('#two_level_menu_page_add_button').click(function() {
					var context = this;
					
					var select_box = jQuery(context).parent().find('#two_level_menu_page_add_list');
					var site_page_id = select_box.val();
					var container_select_box = jQuery(context).parent().find('.add_to_container_list');
					var container_id = container_select_box.val();
					
					if (container_id == 'top_level') {
						console.log ('doing the top level add');
						
						// do the top level add
						jQuery.ajax({
							type: 'post',
							url: '/admin/site_menus/add_two_level_menu_item/SitePage/'+site_page_id+'/',
							data: {},
							success: function(data) {
								if (data.code == 1) {
									var new_menu_item = jQuery(data.new_menu_item_html);
									setup_two_level_menu_item_delete(new_menu_item);
									move_to_cont = jQuery('.two_level_menu_items_cont');
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
					} else {
						// do the container add
						console.log ("container add");
					}
				});
				
				jQuery('#two_level_menu_gallery_add_button').click(function() {
					console.log ("in the gallery add function");
				
					var context = this;
					
					var select_box = jQuery(context).parent().find('#two_level_menu_gallery_add_list');
					var photo_gallery_id = select_box.val();
					var container_select_box = jQuery(context).parent().find('.add_to_container_list');
					var container_id = container_select_box.val();
					
					if (container_id == 'top_level') {
						console.log ('doing the top level add');
						
						// do the top level add
						jQuery.ajax({
							type: 'post',
							url: '/admin/site_menus/add_two_level_menu_item/PhotoGallery/'+photo_gallery_id+'/',
							data: {},
							success: function(data) {
								if (data.code == 1) {
									var new_menu_item = jQuery(data.new_menu_item_html);
									setup_two_level_menu_item_delete(new_menu_item);
									move_to_cont = jQuery('.two_level_menu_items_cont');
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
					} else {
						// do the container add
						console.log ("container add");
					}
				});
			});
		</script>
		
		<?php $all_containers = $this->ThemeMenu->get_two_level_menu_containers(); ?>
		
		<?php $all_pages = $this->Page->get_all_pages(); ?>
		<div class="custom_ui" style="margin: 5px; margin-bottom: 15px;">
			<span><?php __('Add Page:'); ?></span>
			<select id="two_level_menu_page_add_list">
				<?php foreach ($all_pages as $curr_page): ?>
					<option value="<?php echo $curr_page['SitePage']['id']; ?>"><?php echo $curr_page['SitePage']['title']; ?></option>
				<?php endforeach; ?>
			</select>
			<span><?php __('to'); ?></span>
			<select class="add_to_container_list">
				<option value="top_level">Top Level</option>
				<?php foreach ($all_containers as $all_container): ?>
					<option value="<?php echo $all_container['SiteTwoLevelMenuContainer']['id']; ?>"><?php echo $all_container['SiteTwoLevelMenuContainer']['display_name']; ?></option>
				<?php endforeach; ?>
			</select>
			<input id="two_level_menu_page_add_button" class="add_button" type="submit" value="<?php __('Go'); ?>" />
		</div>
		
		<?php $all_galleries = $this->Gallery->get_all_galleries(); ?>
		<div class="custom_ui" style="margin: 5px; margin-bottom: 15px;">
			<span><?php __('Add Gallery:'); ?></span>
			<select id="two_level_menu_gallery_add_list">
				<?php foreach ($all_galleries as $curr_gallery): ?>
					<option value="<?php echo $curr_gallery['PhotoGallery']['id']; ?>"><?php echo $curr_gallery['PhotoGallery']['display_name']; ?></option>
				<?php endforeach; ?>
			</select>
			<span><?php __('to'); ?></span>
			<select class="add_to_container_list">
				<option value="top_level">Top Level</option>
				<?php foreach ($all_containers as $all_container): ?>
					<option value="<?php echo $all_container['SiteTwoLevelMenuContainer']['id']; ?>"><?php echo $all_container['SiteTwoLevelMenuContainer']['display_name']; ?></option>
				<?php endforeach; ?>
			</select>
			<input id="two_level_menu_gallery_add_button" class="add_button" type="submit" value="<?php __('Go'); ?>" />
		</div>
	</div>
</div>

<style type="text/css">
	.two_level_menu_items_cont {
		position: relative;
	}
	.two_level_menu_items_cont .top_level_item {
		min-height: 60px;
		margin: 20px;
		position: relative;
		border: 1px solid transparent;
	}
	.two_level_menu_items_cont .top_level_item h2 {
		margin: 10px;
	}
	.two_level_menu_items_cont .single_item {
		background-color: #454545;
	} 
	.two_level_menu_items_cont .container_item {
		background-color: #636363;
	} 
	.two_level_menu_items_cont .top_level_item .sub_menu_item {
		background-color: #454545;
		margin: 10px;
		min-height: 40px;
		cursor: move;
	}
</style>
