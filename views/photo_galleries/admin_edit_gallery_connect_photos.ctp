<script src="/js/jquery.endless-scroll.js"></script>

<?php 
	$subnav = array(); 

	$subnav['title'] = array(
		'name' => $this->data['PhotoGallery']['display_name'],
		'url' => "/admin/photo_galleries/edit_gallery/{$this->data['PhotoGallery']['id']}/"
	);
	$subnav['pages'][] = array(
		'name' => __('Gallery Settings', true),
		'url' => "/admin/photo_galleries/edit_gallery/{$this->data['PhotoGallery']['id']}/"
	);
	$subnav['pages'][] = array(
		'name' => __('Connect Photos', true),
		'url' => "/admin/photo_galleries/edit_gallery_connect_photos/{$this->data['PhotoGallery']['id']}/",
		'selected' => true
	);
	$subnav['pages'][] = array(
		'name' => __('Arrange Photos', true),
		'url' => "/admin/photo_galleries/edit_gallery_arrange_photos/{$this->data['PhotoGallery']['id']}/"
	);
		
	echo $this->Element('/admin/submenu', array( 'subnav' => $subnav ));
?>



<script type="text/javascript" charset="utf-8">
	var sync_ajax_out = 1;
	
	// to add on the fly an image from the right to teh left side
	var built_gallery_image_html = '<?php echo preg_replace( "/[\n\r]/", '', $this->element('admin/photo/photo_connect_in_gallery_photo_cont', array(
			'connected_photos' => array('dummy'),
			'hide_data' => true,
			'not_in_gallery_icon_size' => 'medium'
		)));
	?>';
		
	function stop_all_functions () {
		if (disable_gallery_add == true) {
			return true;
		}
		
		if (gallery_add_limit > 0) {
			return true;
		}
		
		if (disable_gallery_remove == true) {
			return true;
		}
		
		if (gallery_remove_limit > 0) {
			return true;
		}
		
		if (in_callback == true) { // in the do endless scroll callback
			return true;
		}
		
		if (refreshing_in_gallery_photos == true) {
			return true;
		}
		
		if (removing_all_images_from_gallery == true) {
			return true;
		}
		
		return false;
	}
		
	function refresh_not_in_gallery_photos() {
		jQuery('#connect_gallery_photos_cont .not_in_gallery_photos_cont').empty();
		cease_fire = false;
		do_endless_scroll_callback(0);
	}
	
	var refreshing_in_gallery_photos = false;
	function refresh_in_gallery_photos() {
		jQuery('#in_gallery_photos_cont').empty();
		refreshing_in_gallery_photos = true;
		
		var not_in_gallery_icon_size = jQuery('#not_in_gallery_icon_size .selected').attr('size');
		
		
		jQuery.ajax({
			type: 'post',
			url: '/admin/photo_galleries/ajax_get_photos_in_gallery/'+<?php echo $gallery_id; ?>+'/',
			data: {
				'not_in_gallery_icon_size' : not_in_gallery_icon_size
			},
			success: function(data) {
				if (data.count > 0) {
					jQuery('.in_gallery_main_cont .empty_help_content').hide();
					var new_images = jQuery(data.html);
					setup_remove_from_gallery_buttons(new_images);
					jQuery('#in_gallery_photos_cont').html(new_images);
				} else {
					jQuery('.in_gallery_main_cont .empty_help_content').show();
				}
				built_gallery_image_html = data.image_template_html;
			},
			complete: function() {
				refreshing_in_gallery_photos = false;
			},
			dataType: 'json'
		});
	}
		
	function add_new_in_gallery_image(photo_id, img_src) {
		var new_image = jQuery(built_gallery_image_html);
		
		new_image.attr('photo_id', photo_id);
		new_image.find('.image_content_cont img').attr('src', img_src);
		
		return new_image;
	}
	
	
	var disable_gallery_add = false;
	var gallery_add_limit = 0;
	function setup_add_to_gallery_buttons(selector) {
		jQuery(selector).click(function() {
			if (disable_gallery_add == true || gallery_add_limit >= sync_ajax_out) {
				return;
			}
			
			gallery_add_limit++;
			disable_gallery_add = true;
			setTimeout(function() {
				disable_gallery_add = false;
			}, 100);
			
			var to_delete = jQuery(this).closest('.connect_photo_container');
			var photo_id = to_delete.attr('photo_id');
			var img_src = jQuery('.image_content_cont img', to_delete).attr('src');


			var new_div = add_new_in_gallery_image(photo_id, img_src);
			var move_to_cont = jQuery('#connect_gallery_photos_cont .in_gallery_photos_cont');
			move_to_cont.append(new_div).scrollTop(move_to_cont.prop("scrollHeight"));
			to_delete.remove();
			
			
			// hide the help message for in gallery photos
			jQuery('#connect_gallery_photos_cont .in_gallery_main_cont .empty_help_content').hide();
			
			
			jQuery.ajax({
				type: 'post',
				url: '/admin/photo_galleries/ajax_movephoto_into_gallery/'+photo_id+'/<?php echo $gallery_id; ?>/',
				data: {},
				success: function(data) {
					if (data.code == 1) {
						// its all good
						setup_remove_from_gallery_buttons(new_div);

						// check to see if the website photos needs a help message
						if (element_is_empty('endless_scroll_div')) {
							jQuery('#connect_gallery_photos_cont .not_in_gallery_main_cont .empty_help_content').show();
						}

						// check to see if need an endless scroll fire because of lack of images
						var in_gallery_photos_cont = jQuery('#connect_gallery_photos_cont .not_in_gallery_photos_cont');
						var scrollHeight = in_gallery_photos_cont.prop("scrollHeight");
						var height = in_gallery_photos_cont.height();
						if (cease_fire == false && scrollHeight <= height) {
							do_endless_scroll_callback();
						}
					} else {
						new_div.remove();
						jQuery('#connect_gallery_photos_cont .not_in_gallery_photos_cont').prepend(to_delete);
						// check to see if the help message should now be shown
						if (element_is_empty('in_gallery_photos_cont')) {
							jQuery('#connect_gallery_photos_cont .in_gallery_main_cont .empty_help_content').show();
						}
						major_error_recover(data.message);
					}
				},
				complete: function() {
					gallery_add_limit--;
				},
				dataType: 'json'
			});
		});
	}
	
	var disable_gallery_remove = false;
	var pulsing_refresh_button = false;
	var gallery_remove_limit = 0;
	function setup_remove_from_gallery_buttons(selector) {
		jQuery(selector).click(function() {
			if (disable_gallery_remove == true || gallery_remove_limit >= sync_ajax_out) {
				return;
			}
			
			gallery_remove_limit++;
			disable_gallery_remove = true;
			setTimeout(function() {
				disable_gallery_remove = false;
			}, 100);
			
			var photo_id = jQuery(this).closest('.connect_photo_container').attr('photo_id');
			
			var to_delete = jQuery(this).closest('.connect_photo_container');
			to_delete.remove();
			
			jQuery.ajax({
				type: 'post',
				url: '/admin/photo_galleries/ajax_removephotos_from_gallery/<?php echo $gallery_id; ?>/'+photo_id+'/',
				data: {},
				success: function(data) {
					if (data.code == 1) {
						if (pulsing_refresh_button == false) {
							pulsing_refresh_button = true;
							jQuery('#refresh_not_in_gallery_photos_button').pulse({
								opacity: 1
							}, 1200, 6, 0, function () {
								pulsing_refresh_button = false;
							});
						}

						// check to see if the help message should now be shown
						if (element_is_empty('in_gallery_photos_cont')) {
							jQuery('#connect_gallery_photos_cont .in_gallery_main_cont .empty_help_content').show();
						}
					} else {
						jQuery('#connect_gallery_photos_cont .in_gallery_photos_cont').prepend(to_delete);
						major_error_recover(data.message);
					}
				},
				complete: function() {
					gallery_remove_limit--;
				},
				dataType: 'json'
			});
		});
	}
	
	var removing_all_images_from_gallery = false;
	function remove_all_images_from_gallery() {
		if (stop_all_functions()) {
			return;
		}
		removing_all_images_from_gallery = true;
	
		disable_gallery_remove = true;
		setTimeout(function() {
			disable_gallery_remove = false;
		}, 400);
	
		var photos_to_remove = jQuery('#connect_gallery_photos_cont .in_gallery_photos_cont');
		photos_to_remove.empty();
		
		// remove what will be refreshed shortly -- so they can't add prematurely
		jQuery('#connect_gallery_photos_cont .not_in_gallery_photos_cont').empty();
		
		jQuery.ajax({
			 url : '/admin/photo_galleries/ajax_removephotos_from_gallery/<?php echo $gallery_id; ?>/',
			 success : function (data) {
				if (data.code == 1) {
					// its all good
					
					jQuery('#connect_gallery_photos_cont .in_gallery_main_cont .empty_help_content').show();
				} else {
					jQuery('#connect_gallery_photos_cont .in_gallery_photos_cont').prepend(photos_to_remove);
					major_error_recover(data.message);
				}
			},
			complete: function(jqXHR, textStatus) {
				removing_all_images_from_gallery = false;
				refresh_not_in_gallery_photos();
			},
			dataType: "json"
		}); 
	}
	
	var cease_fire = false;
	var in_callback = false;
	function do_endless_scroll_callback(last_photo_id) {
		if (in_callback == true) {
			return;
		}
		
		jQuery("#filter_photo_by_format, #sort_photo_radio").buttonset('disable');
		jQuery("#photos_not_in_a_gallery").button('disable');
		
		jQuery('#endless_scroll_loading').show();

		// figure named params
		var checked_value = jQuery('input[name=sort_photo_radio]:checked');
		var order = checked_value.attr('order');
		var sort_dir = checked_value.attr('sort_dir');
		$named = '';
		if (order != undefined) {
			$named += 'order:'+order+'/';
		}
		if (sort_dir != undefined) {
			$named += 'sort_dir:'+sort_dir+'/';
		}
		
		
		// figure out filters to pass
		var photo_formats = new Array();
		jQuery('#filter_photo_by_format input:checked').each(function() {
			photo_formats.push(jQuery(this).val());
		});
		var photos_not_in_a_gallery = jQuery('#photos_not_in_a_gallery').is(':checked');
		var not_in_gallery_icon_size = jQuery('#not_in_gallery_icon_size .selected').attr('size');

		in_callback = true;
		if (last_photo_id == undefined) {
			last_photo_id = jQuery('#connect_gallery_photos_cont .not_in_gallery_photos_cont .connect_photo_container:last').attr('photo_id');
		} 
		if (last_photo_id == undefined) { 
			last_photo_id = 0;
		}
		jQuery.ajax({
			type : 'post',
			url : '/admin/photo_galleries/edit_gallery_connect_photos/<?php echo $gallery_id; ?>/'+last_photo_id+'/'+$named,
			data : { 
				'photo_formats': photo_formats,
				'photos_not_in_a_gallery': photos_not_in_a_gallery,
				'not_in_gallery_icon_size': not_in_gallery_icon_size
			},
			success : function (photo_divs) {
			if (photo_divs.count > 0) {
				var new_photo_html = jQuery(photo_divs.html);
				setup_add_to_gallery_buttons(new_photo_html);
				var last_div = jQuery('#connect_gallery_photos_cont .not_in_gallery_photos_cont .connect_photo_container:last');
				if (last_div.length > 0) {
					last_div.after(new_photo_html);
				} else {
					jQuery('#connect_gallery_photos_cont .not_in_gallery_photos_cont').prepend(new_photo_html);
				}

				jQuery('#connect_gallery_photos_cont .not_in_gallery_main_cont .empty_help_content').hide();
			} else {
				cease_fire = true;
			}
			},
			complete: function(jqXHR, textStatus) {
				jQuery('#endless_scroll_loading').hide();
				
				// check to see if the website photos needs a help message
				if (element_is_empty('endless_scroll_div')) {
					jQuery('#connect_gallery_photos_cont .not_in_gallery_main_cont .empty_help_content').show();
				}
				
				jQuery("#filter_photo_by_format, #sort_photo_radio").buttonset('enable');
				jQuery("#photos_not_in_a_gallery").button('enable');
				in_callback = false;
			},
			dataType: "json"
		}); 
	}
	

$(function() {
	setup_add_to_gallery_buttons('#connect_gallery_photos_cont .not_in_gallery_photos_cont .add_to_gallery_button');
	setup_remove_from_gallery_buttons('#connect_gallery_photos_cont .in_gallery_photos_cont .remove_from_gallery_button');
	
	jQuery('#refresh_not_in_gallery_photos_button').click(function() {
		if (stop_all_functions()) {
			return;
		}
		
		refresh_not_in_gallery_photos();
	});
	
	jQuery('#remove_all_gallery_photos').click(function() {
		if (stop_all_functions()) {
			return;
		}
		
		$( "#confirm_empty_gallery" ).dialog('open');
	});

	/*
	 * SETUP THE ENDLESS SCROLL FOR THE NOT YET IN GALLERY IMAGES
	 */
	$('#connect_gallery_photos_cont .not_in_gallery_photos_cont').endlessScroll({
		bottomPixels: 300,
		loader: '',
		callback: function (i) {
			if (cease_fire == false) {
				do_endless_scroll_callback();
			}
		}
	});

	$( "#confirm_empty_gallery" ).dialog({
		autoOpen: false,
		resizable: false,
		height: 180,
		modal: true,
		buttons: [
			{
				text: "<?php __('Empty Gallery'); ?>",
				click: function() {
					remove_all_images_from_gallery();
					$( this ).dialog( "close" );
				}
			},
			{
				text: "<?php __('Cancel'); ?>",
				click: function() {
					$( this ).dialog( "close" );
				}
			}
		]
	});
	
	
	jQuery("#filter_photo_by_format, #sort_photo_radio").buttonset();
	jQuery("#photos_not_in_a_gallery").button();
	// sort radio -- filter checkboxes -- photo_not_in_gallery checkbox
	jQuery('input[name=sort_photo_radio], #filter_photo_by_format input, #photos_not_in_a_gallery').change(function(e) {
		if (stop_all_functions()) {
			return;
		}
		
		jQuery("#filter_photo_by_format .ui-state-hover, #sort_photo_radio .ui-state-hover, #photos_not_in_a_gallery_cont .ui-state-hover").removeClass('ui-state-hover');
		
		refresh_not_in_gallery_photos();
	});
	
	jQuery('#not_in_gallery_icon_size div').click(function() {
		if (stop_all_functions()) {
			return;
		}
		
		jQuery('#not_in_gallery_icon_size div').removeClass('selected');
		jQuery(this).addClass('selected');
		refresh_not_in_gallery_photos();
		refresh_in_gallery_photos();
	});
	
	jQuery('#connect_gallery_photos_cont').disableSelection();
});
</script>

<div id="confirm_empty_gallery" class="dialog_confirm custom_dialog" title="<?php __('Empty Gallery'); ?>">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span><?php __('Remove all photos from gallery?'); ?></p>
</div>

<div id="connect_gallery_photos_cont">
	<div class="in_gallery_main_cont">
		<div class="table_header_darker">
			<div class="actions" style="float: right;"><img id="remove_all_gallery_photos" src="/img/admin/icons/grey_delete_all_icon.png" /></div>
			<h2 style="background: url('/img/admin/icons/FOLDER - DOWNLOADS.png') center left no-repeat; padding-left: 35px;"><?php __('Photos in Gallery'); ?></h2>
		</div>
		<div class="empty_help_content" style="<?php if (empty($this->data['PhotoGalleriesPhoto'])): ?>display: block;<?php endif; ?>"><?php __('Add images to this gallery using the box at right'); ?>&nbsp;►</div>
		<div id="in_gallery_photos_cont" class="in_gallery_photos_cont content-background">
			<?php echo $this->Element('/admin/photo/photo_connect_in_gallery_photo_cont', array( 'connected_photos' => $this->data['PhotoGalleriesPhoto'], 'not_in_gallery_icon_size' => $not_in_gallery_icon_size )); ?>
		</div>
	</div>
	<div class="not_in_gallery_main_cont">
		<div class="table_header_lighter">
			<div class="actions" style="float: right;"><img id="refresh_not_in_gallery_photos_button" src="/img/admin/icons/grey_refresh.png" /></div>
			<div class="custom_ui_radio" style="float: right; margin-top: 13px; margin-right: 10px;">
				<div id="sort_photo_radio">
					<input type="radio" id="radio1" name="sort_photo_radio" order="modified" sort_dir="desc" <?php if ($order == 'modified' && $sort_dir == 'desc'): ?>checked="checked"<?php endif; ?> /><label for="radio1"><?php __('Newest First'); ?></label>
					<input type="radio" id="radio2" name="sort_photo_radio" order="modified" sort_dir="asc" <?php if ($order == 'modified' && $sort_dir == 'asc'): ?>checked="checked"<?php endif; ?> /><label for="radio2"><?php __('Oldest First'); ?></label>
				</div>
			</div>
			<h2 style="margin-left: 10px; background: url('/img/admin/icons/grey_left_arrow.png') center left no-repeat; padding-left: 42px;"><?php __('Website Photos'); ?></h2>
		</div>
		<div id="endless_scroll_loading" class="rounded-corners-small"><span class="default"><?php __('Loading'); ?></span></div>
		<div class="empty_help_content" style="<?php if (empty($not_connected_photos)): ?>display: block;<?php endif; ?>">
			<?php __('No photos found <br/> Add photos <a href="/admin/photos">on the photo page</a>'); ?>
		</div>
		<div id="endless_scroll_div" class="not_in_gallery_photos_cont content-background">
			<?php echo $this->Element('/admin/photo/photo_connect_not_in_gallery_photo_cont', array( 'not_connected_photos' => $not_connected_photos, 'not_in_gallery_icon_size' => $not_in_gallery_icon_size )); ?>
		</div>
		<div class="sort_and_filters">
			<div id="not_in_gallery_icon_size" class="box_icon_size">
				<div id="small_icon" size="small" <?php if($not_in_gallery_icon_size == 'small'): ?>class="selected"<?php endif; ?> >S</div>
				<div id="medium_icon" size="medium" <?php if($not_in_gallery_icon_size == 'medium'): ?>class="selected"<?php endif; ?> >M</div>
				<div id="large_icon" size="large" <?php if($not_in_gallery_icon_size == 'large'): ?>class="selected"<?php endif; ?> >L</div>
			</div>
			
			<div id="photos_not_in_a_gallery_cont" class="custom_ui_radio" style="margin-bottom: 7px;">
				<input type="checkbox" id="photos_not_in_a_gallery" /><label for="photos_not_in_a_gallery"><?php __('Photos Not In A Gallery'); ?></label>
			</div>
				
			<div class="custom_ui_radio">
				<div id="filter_photo_by_format">
					<input type="checkbox" value="landscape" id="check1" /><label for="check1"><?php __('Landscape'); ?></label>
					<input type="checkbox" value="portrait" id="check2" /><label for="check2"><?php __('Portrait'); ?></label>
					<input type="checkbox" value="square" id="check3" /><label for="check3"><?php __('Square'); ?></label>
					<input type="checkbox" value="panoramic" id="check4" /><label for="check4"><?php __('Panoramic'); ?></label>
					<input type="checkbox" value="vertical_panoramic" id="check5" /><label for="check5"><?php __('Vertical Panoramic'); ?></label>
				</div>
			</div>
		</div>
	</div>
	<div style="clear: both;"></div>
</div>

<?php //debug($this->data); ?>