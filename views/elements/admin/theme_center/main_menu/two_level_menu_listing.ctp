<?php 
/*
 * DREW TODO - still need to finish a few things in this file
 *  1) make it so you can add a menu container -- done
 *  2) make it so you can add an item to the top level or to a container -- done
 *  3) make it so the reorder of container items is saved -- done
 *  4) make it so you can delete container items - done
 *  5) make it so you can rename a container - done
 *  6) make it so the container name is displayed somewhere -- done
 *  7) make it so that on container add the container list in the adds is updated -- done
 *  8) make it so config clicks don't move the scroll of the menu list box
 */

?>

<script type="text/javascript">
	function reload_add_container_lists() {
		jQuery('.add_to_container_list').each(function() {
			var context = this;
			
			var data = {};
			
			if (jQuery(context).hasClass('hide_top_level')) {
				data.hide_top_level = true;
			}
			
			
			jQuery.ajax({
				type: 'post',
				url: '/admin/site_menus/ajax_get_site_two_level_menu_containers',
				data: data,
				success: function(data) {
					if (data.code == 1) {
						jQuery(context).replaceWith(data.select_html);
					} else {
						major_error_recover("Failed to reload the container select box.");
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
	
	function setup_two_level_menu_item_delete(selector) {
		jQuery(selector).click(function() {
			var top_level_item = jQuery(this).closest('.top_level_item');
			var site_two_level_menu_id_to_delete = top_level_item.attr('top_level_site_two_level_menu_id');
		
			jQuery.ajax({
				type: 'post',
				url: '/admin/site_menus/ajax_delete_two_level_menu_item/'+site_two_level_menu_id_to_delete,
				data: {},
				success: function(the_data) {
					if (the_data.code == 1) {
						// remove the div
						top_level_item.remove();
					} else {
						major_error_recover(the_data.message);
					}
				},
				complete: function() {
					reload_add_container_lists();
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
	
	function setup_two_level_menu_container_item_delete(selector) {
		jQuery(selector).click(function() {
			var sub_menu_item = jQuery(this).closest('.sub_menu_item');
			var two_level_menu_container_item_id_to_delete = sub_menu_item.attr('site_two_level_menu_container_item_id');
			
			//console.log (two_level_menu_container_item_id_to_delete);
			
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
				var site_two_level_menu_id = jQuery(ui.item).attr('top_level_site_two_level_menu_id');
				var newPosition = position_of_element_among_siblings(jQuery('.top_level_item', context), jQuery(ui.item));
				
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
		
		setup_two_level_menu_container_item_delete('.two_level_menu_items_cont .container_item .sub_menu_item .delete_sub_menu_item_button');
		
		setup_two_level_menu_item_delete('.two_level_menu_items_cont .top_level_item .remove_from_two_level_menu_button');
	});
</script>

<div style="margin-bottom: 100px;">
	<div class="two_level_menu_items_cont menu_items_cont">
		<?php $two_level_menu_items = $this->ThemeMenu->get_two_level_menu_items(); ?>
		<?php //debug($two_level_menu_items); ?>
		
		<?php echo $this->Element('admin/theme_center/main_menu/two_level_menu_item', array('two_level_menu_items' => $two_level_menu_items)); ?>
	</div>
	

	<div class="tab_tools_container">
		<script type="text/javascript">
			jQuery(document).ready(function() {
				jQuery('#two_level_menu_page_add_button').click(function() {
					var context = this;
					
					var select_box = jQuery(context).parent().find('#two_level_menu_page_add_list');
					var site_page_id = select_box.val();
					var container_select_box = jQuery(context).parent().find('.add_to_container_list');
					var container_id = container_select_box.val();
					var site_two_level_menu_id = container_select_box.find('option:selected').attr('site_two_level_menu_id');
					
					if (container_id == 'top_level') {
//						console.log ('doing the top level add');
						
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
						jQuery.ajax({
							type: 'post',
							url: '/admin/site_menus/add_two_level_menu_container_item/'+container_id+'/SitePage/'+site_page_id+'/',
							data: {},
							success: function(data) {
								if (data.code == 1) {
									var new_menu_item = jQuery(data.new_menu_item_html);
									setup_two_level_menu_container_item_delete(new_menu_item);
//									console.log (site_two_level_menu_id);
									move_to_cont = jQuery('div[top_level_site_two_level_menu_id="'+site_two_level_menu_id+'"]');
//									console.log (move_to_cont);
									move_to_cont.append(new_menu_item);

									// move scoll to new menu item
									var menu_cont = jQuery(move_to_cont).closest('.content-background');
									menu_cont.scrollTop(move_to_cont.position().top); // move_to_cont.height() -- DREW TODO - maybe refine this scrollTo
								} else {
									major_error_recover('Failed to add the page menu item to a container');
								}
							},
							complete: function() {

							},
							dataType: 'json'
						});	
					}
				});
				
				jQuery('#two_level_menu_gallery_add_button').click(function() {
//					console.log ("in the gallery add function");
				
					var context = this;
					
					var select_box = jQuery(context).parent().find('#two_level_menu_gallery_add_list');
					var photo_gallery_id = select_box.val();
					var container_select_box = jQuery(context).parent().find('.add_to_container_list');
					var container_id = container_select_box.val();
					var site_two_level_menu_id = container_select_box.find('option:selected').attr('site_two_level_menu_id');
					
					
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
									major_error_recover('Failed to add the gallery menu item');
								}
							},
							complete: function() {

							},
							dataType: 'json'
						});	
					} else {
						// do the container add
						jQuery.ajax({
							type: 'post',
							url: '/admin/site_menus/add_two_level_menu_container_item/'+container_id+'/PhotoGallery/'+photo_gallery_id+'/',
							data: {},
							success: function(data) {
								if (data.code == 1) {
									var new_menu_item = jQuery(data.new_menu_item_html);
									setup_two_level_menu_container_item_delete(new_menu_item);
//									console.log (site_two_level_menu_id);
									move_to_cont = jQuery('div[top_level_site_two_level_menu_id="'+site_two_level_menu_id+'"]');
//									console.log (move_to_cont);
									move_to_cont.append(new_menu_item);

									// move scoll to new menu item
									var menu_cont = jQuery(move_to_cont).closest('.content-background');
									menu_cont.scrollTop(move_to_cont.position().top); // move_to_cont.height() -- DREW TODO - maybe refine this scrollTo
								} else {
									major_error_recover('Failed to add the gallery menu item to a container');
								}
							},
							complete: function() {

							},
							dataType: 'json'
						});	
					}
				});
				
				
				jQuery('#two_level_menu_container_add_button').click(function() {
					var context = this;
					var new_name_cont = jQuery(context).parent().find('.new_menu_container_name');
					if (new_name_cont.hasClass('defaultTextActive')) {
						$.foto('alert', '<?php __('Choose a name for your new menu container before you can add it.'); ?>');
						return false;
					}
					var new_container_name = new_name_cont.val();
					
					jQuery.ajax({
						type: 'post',
						url: '/admin/site_menus/add_two_level_menu_container',
						data: {
							new_container_name: new_container_name
						},
						success: function(the_data) {
							if (the_data.code == 1) {
								// its all good - now need to add the new container html
								var new_menu_item = jQuery(the_data.new_menu_item_html);
								setup_two_level_menu_item_delete(new_menu_item);
								move_to_cont = jQuery('.two_level_menu_items_cont');
								move_to_cont.append(new_menu_item);

								// move scoll to new menu item
								var menu_cont = jQuery(move_to_cont).closest('.content-background');
								menu_cont.scrollTop(menu_cont.prop("scrollHeight"));
								
								// clear the add menu container input
								new_name_cont.val('');
								new_name_cont.focus();
								new_name_cont.blur();
							} else {
								major_error_recover(the_data.message);
							}
						},
						complete: function() {
							reload_add_container_lists();
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
				
				jQuery('#two_level_menu_container_rename_button').click(function() {
					var new_container_name = jQuery('.rename_menu_container_name').val();
					var container_to_rename_id = jQuery(this).parent().find('.add_to_container_list').val();
					
//					console.log (new_container_name);
//					console.log (container_to_rename_id);
					
					jQuery.ajax({
						type: 'post',
						url: '/admin/site_menus/ajax_rename_site_two_level_menu_container',
						data: {
							new_container_name: new_container_name,
							container_to_rename_id: container_to_rename_id
						},
						success: function(data) {
							if (data.code == 1) {
								// change the name in the sortable list
								var actual_container_to_rename = jQuery('[site_two_level_menu_container_id="'+container_to_rename_id+'"]');
								jQuery(actual_container_to_rename).find('.container_name').html(new_container_name+' (container)');
								
								// also need to put the default value back for the rename input
								jQuery('.rename_menu_container_name').val('');
								jQuery('.rename_menu_container_name').focus();
								jQuery('.rename_menu_container_name').blur();
							} else {
								major_error_recover(data.message);
							}
						},
						complete: function() {
							reload_add_container_lists();
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
				
			});
			
		</script>

		<?php $all_containers = $this->ThemeMenu->get_two_level_menu_containers(); ?>
		
		<div class="custom_ui">
			<span><?php __('Add Menu Container:'); ?></span>
			<input class="new_menu_container_name defaultText" title="container name" type="text" style="width: 136px;" />
			<input id="two_level_menu_container_add_button" class="add_button" type="submit" value="<?php __('Go'); ?>" />
		</div>
		
		<div class="custom_ui">
			<span><?php __('Rename Container:'); ?></span>
			<?php echo $this->Element('admin/theme_center/main_menu/container_select_box', array('all_containers' => $all_containers, 'hide_top_level' => true)); ?>
			<input class="rename_menu_container_name defaultText" title="new container name" type="text" style="width: 136px;" />
			<input id="two_level_menu_container_rename_button" class="add_button" type="submit" value="<?php __('Go'); ?>" />
		</div>
		
		
		<?php $all_pages = $this->Page->get_all_pages(); ?>
		<div class="custom_ui">
			<span><?php __('Add Page:'); ?></span>
			<select id="two_level_menu_page_add_list">
				<?php foreach ($all_pages as $curr_page): ?>
					<option value="<?php echo $curr_page['SitePage']['id']; ?>"><?php echo $curr_page['SitePage']['title']; ?></option>
				<?php endforeach; ?>
			</select>
			<span><?php __('to'); ?></span>
			<?php echo $this->Element('admin/theme_center/main_menu/container_select_box', array('all_containers' => $all_containers)); ?>
			<input id="two_level_menu_page_add_button" class="add_button" type="submit" value="<?php __('Go'); ?>" />
		</div>
		
		<?php $all_galleries = $this->Gallery->get_all_galleries(); ?>
		<div class="custom_ui">
			<span><?php __('Add Gallery:'); ?></span>
			<select id="two_level_menu_gallery_add_list">
				<?php foreach ($all_galleries as $curr_gallery): ?>
					<option value="<?php echo $curr_gallery['PhotoGallery']['id']; ?>"><?php echo $curr_gallery['PhotoGallery']['display_name']; ?></option>
				<?php endforeach; ?>
			</select>
			<span><?php __('to'); ?></span>
			<?php echo $this->Element('admin/theme_center/main_menu/container_select_box', array('all_containers' => $all_containers)); ?>
			<input id="two_level_menu_gallery_add_button" class="add_button" type="submit" value="<?php __('Go'); ?>" />
		</div>
	</div>
</div>
