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
	var sync_ajax_out = 3;
	
	// to add on the fly an image from the right to teh left side
	var built_gallery_image_html = '<?php echo preg_replace( "/[\n\r]/", '', $this->element('admin/photo/photo_connect_in_gallery_photo_cont', array(
			'connected_photos' => array('dummy'),
			'hide_data' => true
		)));
	?>';
		
	function refresh_not_in_gallery_photos() {
		jQuery('#connect_gallery_photos_cont .not_in_gallery_photos_cont').empty();
		cease_fire = false;
		do_endless_scroll_callback(0);
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
			if (disable_gallery_add == true || gallery_add_limit > sync_ajax_out) {
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
			

			
			jQuery.post('/admin/photo_galleries/ajax_movephoto_into_gallery/'+photo_id+'/<?php echo $gallery_id; ?>/', function(data) {
				if (data.code == 1) {
					// its all good
					setup_remove_from_gallery_buttons(new_div);
					
					// check to see if the website photos needs a help message
					if (element_is_empty('endless_scroll_div')) {
						console.log ("showing help content");
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
				
				gallery_add_limit--;
			}, 'json');
		});
	}
	
	var disable_gallery_remove = false;
	var pulsing_refresh_button = false;
	var gallery_remove_limit = 0;
	function setup_remove_from_gallery_buttons(selector) {
		jQuery(selector).click(function() {
			if (disable_gallery_remove == true || gallery_remove_limit > sync_ajax_out) {
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
			

			jQuery.post('/admin/photo_galleries/ajax_removephotos_from_gallery/<?php echo $gallery_id; ?>/'+photo_id+'/', function(data) {
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
				
				gallery_remove_limit--;
			}, 'json');
		});
	}
	
	function remove_all_images_from_gallery() {
		if (disable_gallery_remove == true || gallery_remove_limit > 0) {
			return;
		}
		
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
		
		jQuery('#endless_scroll_loading').show();

		in_callback = true;
		if (last_photo_id == undefined) {
			last_photo_id = jQuery('#connect_gallery_photos_cont .not_in_gallery_photos_cont .connect_photo_container:last').attr('photo_id');
		} 
		if (last_photo_id == undefined) { 
			last_photo_id = 0;
		}
		jQuery.ajax({
			 url : '/admin/photo_galleries/edit_gallery_connect_photos/<?php echo $gallery_id; ?>/'+last_photo_id+'/',
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
				
				in_callback = false;
			},
			dataType: "json"
		}); 
	}
	

$(function() {
	setup_add_to_gallery_buttons('#connect_gallery_photos_cont .not_in_gallery_photos_cont .add_to_gallery_button');
	setup_remove_from_gallery_buttons('#connect_gallery_photos_cont .in_gallery_photos_cont .remove_from_gallery_button');
	
	jQuery('#connect_gallery_photos_cont .not_in_gallery_photos_cont').disableSelection();
	jQuery('#connect_gallery_photos_cont .in_gallery_photos_cont').disableSelection();
	
	
	jQuery('#refresh_not_in_gallery_photos_button').click(function() {
		refresh_not_in_gallery_photos();
	});
	
	jQuery('#remove_all_gallery_photos').click(function() {
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
});
</script>

<div id="confirm_empty_gallery" class="dialog_confirm custom_dialog" title="<?php __('Empty Gallery'); ?>">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span><?php __('Remove all photos from gallery?'); ?></p>
</div>

<?php //debug($this->data); ?>
<div id="connect_gallery_photos_cont">
	<div class="in_gallery_main_cont">
		<div class="table_header_darker">
			<div class="actions" style="float: right;"><img id="remove_all_gallery_photos" src="/img/admin/icons/grey_delete_all_icon.png" /></div>
			<h2 style="background: url('/img/admin/icons/FOLDER - DOWNLOADS.png') center left no-repeat; padding-left: 35px;"><?php __('Photos in Gallery'); ?></h2>
		</div>
		<div class="empty_help_content" style="<?php if (empty($this->data['PhotoGalleriesPhoto'])): ?>display: block;<?php endif; ?>"><?php __('Add images to this gallery using the box at right'); ?>&nbsp;►</div>
		<div id="in_gallery_photos_cont" class="in_gallery_photos_cont">
			<?php echo $this->Element('/admin/photo/photo_connect_in_gallery_photo_cont', array( 'connected_photos' => $this->data['PhotoGalleriesPhoto'] )); ?>
		</div>
	</div>
	<div class="not_in_gallery_main_cont">
		<div class="table_header_darker" style="background-color: #292929; color: #AAA;">
			<div class="actions" style="float: right;"><img id="refresh_not_in_gallery_photos_button" src="/img/admin/icons/grey_refresh.png" /></div>
			<h2 style="margin-left: 10px; color: #AAA; background: url('/img/admin/icons/grey_left_arrow.png') center left no-repeat; padding-left: 42px;"><?php __('Website Photos'); ?></h2>
		</div>
		<div id="endless_scroll_loading" class="rounded-corners-small"><span class="default"><?php __('Loading'); ?></span></div>
		<div class="empty_help_content" style="<?php if (empty($not_connected_photos)): ?>display: block;<?php endif; ?>">
			<?php __('No photos found <br/> Add photos <a href="/admin/photos">on the photo page</a> or refresh this box'); ?>
		</div>
		<div id="endless_scroll_div" class="not_in_gallery_photos_cont">
			<?php echo $this->Element('/admin/photo/photo_connect_not_in_gallery_photo_cont', array( 'not_connected_photos' => $not_connected_photos )); ?>
		</div>
	</div>
	<div style="clear: both;"></div>
</div>

<?php //debug($this->data); ?>