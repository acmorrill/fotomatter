<script src="/js/jquery.endless-scroll.js"></script>
<script type="text/javascript" charset="utf-8">
	function Timeout(fn, interval) {
		var context = this;
		this.cleared = false;
		var id = setTimeout(function() {
			context.cleared = true;
			fn();
		}, interval);
		this.clear = function () {
			context.cleared = true;
			clearTimeout(id);
		};
		this.run_now = function() {
			if (context.cleared === false) {
				clearTimeout(id);
				context.cleared = true;
				fn();
			}
		};
	}
	
	var sync_ajax_out = 1;
	
	// to add on the fly an image from the right to teh left side
	var built_gallery_image_html = '<?php echo preg_replace( "/[\n\r]/", '', $this->element('admin/photo/photo_connect_in_gallery_photo_cont', array(
			'connected_photos' => array('dummy'),
			'hide_data' => true,
			'not_in_gallery_icon_size' => 'medium',
			'hide_debug' => true
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
		
		show_universal_save();
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
				hide_universal_save();
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
			
			
			show_universal_save();
			gallery_add_limit++;
			disable_gallery_add = true;
			var when_finished_timeout = new Timeout(function() {
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
					hide_universal_save();
					when_finished_timeout.run_now();
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
			
			show_universal_save();
			
			gallery_remove_limit++;
			disable_gallery_remove = true;
			var when_finished_timeout = new Timeout(function() {
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
					hide_universal_save();
					when_finished_timeout.run_now();
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
		
		show_universal_save();
		
		removing_all_images_from_gallery = true;
		disable_gallery_remove = true;
		var when_finished_timeout = new Timeout(function() {
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
				when_finished_timeout.run_now();
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
		
		show_universal_load();

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
				hide_universal_load();
				
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
		
		jQuery.foto('confirm', {
			'title' : '<?php __('Empty Gallery'); ?>',
			'button_title' : '<?php __('Empty Gallery'); ?>',
			'onConfirm' : function() {
				remove_all_images_from_gallery();
			},
			'type' : 'alert',
			'message': '<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span><?php __('Remove all photos from gallery?'); ?></p>'
		});
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
	
	jQuery('#not_in_gallery_icon_size > div').click(function() {
		if (stop_all_functions()) {
			return;
		}
		
		jQuery('#not_in_gallery_icon_size > div').removeClass('selected');
		jQuery(this).addClass('selected');
		refresh_not_in_gallery_photos();
		refresh_in_gallery_photos();
	});
	
	jQuery('#connect_gallery_photos_cont').disableSelection();
});
</script>

<div id="connect_gallery_photos_cont">
	<?php echo $this->Element('/admin/get_help_button'); ?>
	<div style="clear: both;"></div> 
	
	<div class="page_content_header custom_ui">
		<div id='add_gallery_filters_cont'>
			<div id="photos_not_in_a_gallery_cont" class="custom_ui_radio">
				<input type="checkbox" id="photos_not_in_a_gallery" />
				<label class='add_button' for="photos_not_in_a_gallery"><div class='content'><?php echo __('Photos Not In A Gallery', true); ?></div></label>
			</div>
		</div><?php /*
		*/ ?><div class="generic_sort_and_filters"><?php /*
			*/ ?><div id="filter_photo_by_format"><?php /*
				*/ ?><input type="checkbox" value="vertical_panoramic" id="check5" /><?php /*
				*/ ?><label class='add_button' for="check5"><div class='content'><?php echo __('Vertical Panoramic', true); ?></div></label><?php /*
				*/ ?><input type="checkbox" value="panoramic" id="check4" /><?php /*
				*/ ?><label class='add_button' for="check4"><div class='content'><?php echo __('Panoramic', true); ?></div></label><?php /*
				*/ ?><input type="checkbox" value="square" id="check3" /><?php /*
				*/ ?><label class='add_button' for="check3"><div class='content'><?php echo __('Square', true); ?></div></label><?php /*
				*/ ?><input type="checkbox" value="portrait" id="check2" /><?php /*
				*/ ?><label class='add_button' for="check2"><div class='content'><?php echo __('Portrait', true); ?></div></label><?php /*
				*/ ?><input type="checkbox" value="landscape" id="check1" /><?php /*
				*/ ?><label class='add_button' for="check1"><div class='content'><?php echo __('Landscape', true); ?></div></label><?php /*
				*/ ?><div style="clear: both;"></div><?php /*
			*/ ?></div><?php /*
		*/ ?></div><?php /*
		*/ ?><div id="not_in_gallery_icon_size" class="box_icon_size custom_ui"><?php /*
			*/ ?><div id="small_icon" size="small" class="add_button <?php if($not_in_gallery_icon_size == 'small'): ?> selected <?php endif; ?>"><?php /*
				*/ ?><div class="content">S</div><?php /*
			*/ ?></div><?php /*
			*/ ?><div id="medium_icon" size="medium" class="add_button <?php if($not_in_gallery_icon_size == 'medium'): ?> selected <?php endif; ?>"><?php /*
				*/ ?><div class="content">M</div><?php /*
			*/ ?></div><?php /*
			*/ ?><div id="large_icon" size="large" class="add_button <?php if($not_in_gallery_icon_size == 'large'): ?> selected <?php endif; ?>"><?php /*
				*/ ?><div class="content">L</div><?php /*
			*/ ?></div><?php /*
		*/ ?></div>
		<div style="clear: both;"></div>
	</div>
	
	<div class='table_container custom_ui'>
		<div class="fade_background_top"></div>
		<div class="in_gallery_main_cont">
			<div class="image_container_header">
				<h2><?php echo __('Photos in Gallery', true); ?></h2>
				<div class="actions"><img id="remove_all_gallery_photos" src="/img/admin/icons/grey_delete_all_icon.png" /></div>
			</div>
			
			<div class="empty_help_content" style="<?php if (empty($this->data['PhotoGalleriesPhoto'])): ?>display: block;<?php endif; ?>"><?php echo __('Add images to this gallery using the box at right', true); ?>&nbsp;►</div>
			<div id="in_gallery_photos_cont" class="in_gallery_photos_cont">
				<?php echo $this->Element('/admin/photo/photo_connect_in_gallery_photo_cont', array( 'connected_photos' => $this->data['PhotoGalleriesPhoto'], 'not_in_gallery_icon_size' => $not_in_gallery_icon_size )); ?>
			</div>
		</div>
		<div class="not_in_gallery_main_cont">
			<div class="image_container_header">
				<div class="actions" style="float: right;"><img id="refresh_not_in_gallery_photos_button" src="/img/admin/icons/grey_refresh.png" /></div>
				<div id="sort_photo_radio"><?php /*
					*/ ?><input type="radio" id="radio1" name="sort_photo_radio" order="modified" sort_dir="desc" <?php if ($order == 'modified' && $sort_dir == 'desc'): ?>checked="checked"<?php endif; ?> /><?php /*
					*/ ?><label class='add_button' for="radio1"><div class='content'><?php echo __('Newest', true); ?></div></label><?php /*
					*/ ?><input type="radio" id="radio2" name="sort_photo_radio" order="modified" sort_dir="asc" <?php if ($order == 'modified' && $sort_dir == 'asc'): ?>checked="checked"<?php endif; ?> /><?php /*
					*/ ?><label class='add_button' for="radio2"><div class='content'><?php echo __('Oldest', true); ?></div></label><?php /*
				*/ ?></div>
				<h2><?php echo __('Website Photos', true); ?></h2>
			</div>
			
			
			<div class="empty_help_content" style="<?php if (empty($not_connected_photos)): ?>display: block;<?php endif; ?>">
				<?php echo __('No photos found <br/> Add photos <a href="/admin/photos">on the photo page</a>', true); ?>
			</div>
			<div id="endless_scroll_div" class="not_in_gallery_photos_cont">
				<?php echo $this->Element('/admin/photo/photo_connect_not_in_gallery_photo_cont', array( 'not_connected_photos' => $not_connected_photos, 'not_in_gallery_icon_size' => $not_in_gallery_icon_size )); ?>
			</div>
			<div style="clear: both;"></div>
			<?php /*<div class="generic_sort_and_filters">*/?>
				<?php /*<div id="not_in_gallery_icon_size" class="box_icon_size">
					<div id="small_icon" size="small" <?php if($not_in_gallery_icon_size == 'small'): ?>class="selected"<?php endif; ?> >S</div>
					<div id="medium_icon" size="medium" <?php if($not_in_gallery_icon_size == 'medium'): ?>class="selected"<?php endif; ?> >M</div>
					<div id="large_icon" size="large" <?php if($not_in_gallery_icon_size == 'large'): ?>class="selected"<?php endif; ?> >L</div>
				</div> */ ?>

				<?php /*<div id="photos_not_in_a_gallery_cont" class="custom_ui_radio" style="margin-bottom: 7px;">
					<input type="checkbox" id="photos_not_in_a_gallery" /><label for="photos_not_in_a_gallery"><?php echo __('Photos Not In A Gallery', true); ?></label>
				</div> */ ?>

				<?php /*<div class="custom_ui_radio">
					<div id="filter_photo_by_format">
						<input type="checkbox" value="landscape" id="check1" /><label for="check1"><?php echo __('Landscape', true); ?></label>
						<input type="checkbox" value="portrait" id="check2" /><label for="check2"><?php echo __('Portrait', true); ?></label>
						<input type="checkbox" value="square" id="check3" /><label for="check3"><?php echo __('Square', true); ?></label>
						<input type="checkbox" value="panoramic" id="check4" /><label for="check4"><?php echo __('Panoramic', true); ?></label>
						<input type="checkbox" value="vertical_panoramic" id="check5" /><label for="check5"><?php echo __('Vertical Panoramic', true); ?></label>
					</div>
				</div>
			</div>*/ ?>
		</div>
	</div>
	</div>
	<div style="clear: both;"></div>
</div>

<?php //debug($this->data); ?>

<?php ob_start(); ?>
<ol>
	<li>This page is where you can connect photos to standard galleries</li>
	<li>Things to remember
		<ol>
			<li>Don't forget the trash icon and popup</li>
			<li>Don't forget the refresh icon</li>
			<li>Don't forget the SML buttons</li>
			<li>The buttons (newest first - photos not in gallery etc - are done by jquery ui and it would be good to not change them too much)</li>
			<li>There is an infinite scroll on the Website Photos section - when new photos are loading there is a loading div - we need style for that</li>
			<li>The whole page is ajax - so we may need something to show the saving state (currently the icon changes to busy)</li>
		</ol>
	</li>
</ol>
<?php
$html = ob_get_contents();
ob_end_clean();
	echo $this->Element('admin/richard_notes', array(
	'html' => $html
)); ?>