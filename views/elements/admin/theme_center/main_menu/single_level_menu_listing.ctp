<?php if (isset($theme_config['admin_config']['main_menu']['levels']) && $theme_config['admin_config']['main_menu']['levels'] != 1): ?>
	<div class="large_container">
		<div class="tab_tools_container">
			<h2><?php echo __('The current theme does not support a single level menu.', true); ?></h2>
		</div>
	</div>
<?php else: ?>
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

					show_universal_save();
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
							hide_universal_save();
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

				show_universal_save();
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
						hide_universal_save();
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
		<div class="table_border single_level_menu_items_cont" data-step="1" data-intro="<?php echo  __("Here are the menu items on your site.", true); ?>" data-position="top">
			<?php $single_menu_items = $this->ThemeMenu->get_single_menu_items(); ?>
			<?php //$do_not_sort_items = array($single_menu_items[0]['SiteOneLevelMenu']['id']); ?>
			<table class="list">
				<tbody>
					<?php echo $this->Element('admin/theme_center/main_menu/single_level_menu_item', array('single_menu_items' => $single_menu_items)); ?>
				</tbody>
			</table>
		</div>
		<div class="tab_tools_container">
			<script type="text/javascript">
				jQuery(document).ready(function() {
					jQuery('#single_menu_page_add_button').click(function() { 
						<?php if (empty($current_on_off_features['page_builder'])): ?>
							return false;
						<?php endif; ?>

						var select_box = jQuery(this).parent().find('#single_menu_page_add_list');
						var site_page_id = select_box.val();

						show_universal_save();
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
								hide_universal_save();
							},
							dataType: 'json'
						});	
					});


					jQuery('#single_menu_gallery_add_button').click(function() { 
						var select_box = jQuery(this).parent().find('#single_menu_gallery_add_list');
						var photo_gallery_id = select_box.val();

						show_universal_save();
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
								hide_universal_save();
							},
							dataType: 'json'
						});	
					});
					
					
					var in_show_home_link_call = false;
					jQuery('#show_home_link_input').button();
					jQuery('#show_home_link_input').click(function() {
						if (in_show_home_link_call == true) {
							return;
						}
						in_show_home_link_call = true;
						
						var show_home_link = jQuery(this).is(':checked');
						jQuery.ajax({
							type: 'post',
							url: '/admin/site_menus/set_show_home_link/' + show_home_link,
							data: {},
							success: function(data) {
//								console.log(data);
							},
							complete: function() {
								in_show_home_link_call = false;
							},
							error: function(jqXHR, textStatus, errorThrown) {

							},
							dataType: 'json'
						});
					});
				});
			</script>

			<?php $all_pages = $this->Page->get_all_pages(); ?>
			<div class="custom_ui">
				<h2><?php echo __('Add Page To Menu', true); ?></h2>
				<div class="drop_down_sub_title" data-step="2" data-intro="<?php echo __('Add the pages you’ve created (using the &ldquo;Pages&rdquo; tab above) to the menu.', true); ?>" data-position="top">	
					<?php 
						$pages_disabled_class = '';
						if (empty($current_on_off_features['page_builder'])) {
							$pages_disabled_class = ' disabled ';
						}
					?>
					<?php if (!empty($all_pages)): ?>
						<select id="single_menu_page_add_list" class="<?php echo $pages_disabled_class; ?>">
							<?php foreach ($all_pages as $curr_page): ?>
								<option value="<?php echo $curr_page['SitePage']['id']; ?>"><?php echo $curr_page['SitePage']['title']; ?> <?php echo __('Page', true); ?></option>
							<?php endforeach; ?>
						</select>
						<div id="single_menu_page_add_button" class="custom_ui tools_button <?php echo $pages_disabled_class; ?>">
							<div class="add_button">
								<div class="content"><?php echo __('Add', true); ?></div>
								<div class="plus_icon_lines icon-_button-01"><div class="one"></div><div class="two"></div></div>
							</div>
						</div>
					<?php else: ?>
						<div id="add_some_pages_button" class="custom_ui tools_button <?php echo $pages_disabled_class; ?>">
							<a href="/admin/site_pages">
								<div class="add_button">
									<div class="content"><?php echo __('Go To Page Builder', true); ?></div>
									<div class="right_arrow_lines icon-arrow-01"><div></div></div>
								</div>
							</a>
						</div>
					<?php endif; ?>
					<?php if (empty($current_on_off_features['page_builder'])): ?>
						<div style="margin-left: 10px;" id="add_feature_button" class="add_button highlight add_feature_button" type="submit" ref_feature_name="page_builder">
							<div class="content"><?php echo __("Add Page Builder", true); ?></div><div class="right_arrow_lines icon-arrow-01"><div></div></div>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<div class="hr_element"></div>
			<?php /*
			<?php $all_galleries = $this->Gallery->get_all_galleries(); ?>
			<div class="custom_ui">
				<h2><?php echo __('Add Gallery Page To Main Menu', true); ?></h2>
				<div class="drop_down_sub_title">
					<?php if (!empty($all_galleries)): ?>
						<select id="single_menu_gallery_add_list">
							<?php foreach ($all_galleries as $curr_gallery): ?>
								<option value="<?php echo $curr_gallery['PhotoGallery']['id']; ?>"><?php echo $curr_gallery['PhotoGallery']['display_name']; ?></option>
							<?php endforeach; ?>
						</select>
						<div id="single_menu_gallery_add_button" class="custom_ui tools_button">
							<div class="add_button">
								<div class="content"><?php echo __('Add', true); ?></div>
								<div class="plus_icon_lines icon-_button-01"><div class="one"></div><div class="two"></div></div>
							</div>
						</div>
					<?php else: ?>
						<div id="add_some_galleries_button" class="custom_ui tools_button">
							<a href="/admin/photo_galleries">
								<div class="add_button">
									<div class="content"><?php echo __('Add Galleries on Gallery Page', true); ?></div>
									<div class="right_arrow_lines icon-arrow-01"><div></div></div>
								</div>
							</a>
						</div>
					<?php endif; ?>
				</div>	
			</div>
			<div class="hr_element"></div>*/ ?>
			
			
			<div class="custom_ui" data-step="3" data-intro="<?php echo __('The default is set to display “Home” as the first item on your menu. If you do not want “Home” to appear, click this button. The Show Home Link letters will turn gray to show it is not selected.', true); ?>" data-position="top">
				<h2><?php echo __('Show "Home" Link in Menu?', true); ?></h2>
				<div class="drop_down_sub_title">
					<?php $show_in_link = $this->Theme->get_theme_global_setting('show_home_link_in_menu', true); ?>
					<input type="checkbox" id="show_home_link_input" <?php if ($show_in_link == true): ?>checked="checked"<?php endif; ?> />
					<label class='add_button' for="show_home_link_input" id="show_home_link_label"><div class='content'><?php echo __('Show Home Link', true); ?></div></label>
				</div>	
			</div>


			<?php /*
			<div id="photos_not_in_a_gallery_cont" class="custom_ui_radio" style="margin-bottom: 7px;">
				<input type="checkbox" id="photos_not_in_a_gallery" /><label for="photos_not_in_a_gallery"><?php __('Photos Not In A Gallery'); ?></label>
			</div>
			 */ ?>
		</div>
</div>
<?php endif; ?>