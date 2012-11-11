<?php 
/*
 * DREW TODO - still need to finish a few things in this file
 *  1) make it so you can add a menu container
 *  2) make it so you can add an item to the top level or to a container
 *  3) make it so the reorder of container items is saved
 *  4) make it so you can delete container items
 * 
 */

?>

<script type="text/javascript">
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
			scroll: false
		});
		
		
		jQuery('.two_level_menu_items_cont .top_level_item .remove_from_two_level_menu_button').click(function() {
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
		
		
	});
</script>

<div style="margin-bottom: 100px;">
	<div class="two_level_menu_items_cont menu_items_cont">
		<?php $two_level_menu_items = $this->ThemeMenu->get_two_level_menu_items(); ?>
		<?php //debug($two_level_menu_items); ?>
		
		<?php echo $this->Element('admin/theme_center/main_menu/two_level_menu_item', array('two_level_menu_items' => $two_level_menu_items)); ?>
	</div>
	

	<div class="generic_sort_and_filters" style="position: absolute; bottom: -91px; left: 0px; right: 0px;">
		<div id="photos_not_in_a_gallery_cont" class="custom_ui_radio" style="margin-bottom: 7px;">
			<input type="checkbox" id="photos_not_in_a_gallery" /><label for="photos_not_in_a_gallery"><?php __('Photos Not In A Gallery'); ?></label>
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
