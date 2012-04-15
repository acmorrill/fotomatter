<script src="/js/jquery.endless-scroll.js"></script>

<h1><?php __('Gallery Edit'); ?></h1>

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
	
	// to add on the fly an image from the right to teh left side
	var built_gallery_image_html = '<?php echo preg_replace( "/[\n\r]/", '', $this->element('admin/photo/photo_connect_in_gallery_photo_cont', array(
			'connected_photos' => array('dummy'),
			'hide_data' => true
		)));
	?>';
		
	function add_new_in_gallery_image(photo_id, img_src) {
		var new_image = jQuery(built_gallery_image_html);
		
		new_image.attr('photo_id', photo_id);
		new_image.find('.image_content_cont img').attr('src', img_src);
		
		return new_image;
	}
	
	
	var disable_gallery_add = false;
	function setup_add_to_gallery_buttons(selector) {
		jQuery(selector).click(function() {
			if (disable_gallery_add == true) {
				console.log ("still came into here");
				return;
			}
			
			disable_gallery_add = true;
			setTimeout(function() {
				disable_gallery_add = false;
			}, 400);
			
			var to_delete = jQuery(this).closest('.connect_photo_container');
			var photo_id = to_delete.attr('photo_id');
			var img_src = jQuery('.image_content_cont img', to_delete).attr('src');


			var new_div = add_new_in_gallery_image(photo_id, img_src);
			var move_to_cont = jQuery('#connect_gallery_photos_cont .in_gallery_photos_cont');
			move_to_cont.append(new_div).scrollTop(move_to_cont.prop("scrollHeight"));
			to_delete.remove();
			
			var in_gallery_photos_cont = jQuery('#connect_gallery_photos_cont .not_in_gallery_photos_cont');
			var scrollHeight = in_gallery_photos_cont.prop("scrollHeight");
			var height = in_gallery_photos_cont.height();
			if (cease_fire == false && scrollHeight <= height) {
				do_endless_scroll_callback(1);
			}
			
			
			jQuery.post('/admin/photo_galleries/ajax_movephoto_into_gallery/'+photo_id+'/<?php echo $gallery_id; ?>/', function(data) {
				if (data.code == 1) {
					// its all good
					setup_remove_from_gallery_buttons(new_div);
				} else {
					new_div.remove();
					jQuery('#connect_gallery_photos_cont .not_in_gallery_photos_cont').prepend(to_delete);
					major_error_recover(data.message);
				}
			}, 'json');
		});
	}
	
	var disable_gallery_remove = false;
	function setup_remove_from_gallery_buttons(selector) {
		jQuery(selector).click(function() {
			if (disable_gallery_remove == true) {
				return;
			}
			
			disable_gallery_remove = true;
			setTimeout(function() {
				disable_gallery_remove = false;
			}, 400);
			
			var photo_id = jQuery(this).closest('.connect_photo_container').attr('photo_id');
			
			var to_delete = jQuery(this).closest('.connect_photo_container');
			to_delete.remove();
			jQuery.post('/admin/photo_galleries/ajax_removephoto_from_gallery/'+photo_id+'/<?php echo $gallery_id; ?>/', function(data) {
				if (data.code == 1) {
					// its all good
				} else {
					jQuery('#connect_gallery_photos_cont .in_gallery_photos_cont').prepend(to_delete);
					major_error_recover(data.message);
				}
			}, 'json');
			
			
			// TODO - go make it so the filter refresh button will highlight itself
		});
	}
	
	var cease_fire = false;
	var in_callback = false;
	function do_endless_scroll_callback(i) {
		if (in_callback == true) {
			return;
		}
		
		jQuery('#endless_scroll_loading').show();

		in_callback = true;

		var last_photo_id = jQuery('#connect_gallery_photos_cont .not_in_gallery_photos_cont .connect_photo_container:last').attr('photo_id');
		jQuery.ajax({
			 url : '/admin/photo_galleries/ajax_get_more_photos_to_connect/<?php echo $gallery_id; ?>/'+last_photo_id+'/',
			 success : function (photo_divs) {
				if (photo_divs.count > 0) {
					var last_div = jQuery('#connect_gallery_photos_cont .not_in_gallery_photos_cont .connect_photo_container:last');
					var new_photo_html = jQuery(photo_divs.html);
					setup_add_to_gallery_buttons(new_photo_html);
					last_div.after(new_photo_html);
				} else {
					cease_fire = true;
				}
				in_callback = false;
				
				jQuery('#endless_scroll_loading').show();
			},
			complete: function(jqXHR, textStatus) {
				jQuery('#endless_scroll_loading').hide();
			},
			dataType: "json"
		}); 
	}
	

$(function() {
	setup_add_to_gallery_buttons('#connect_gallery_photos_cont .not_in_gallery_photos_cont .add_to_gallery_button');
	setup_remove_from_gallery_buttons('#connect_gallery_photos_cont .in_gallery_photos_cont .remove_from_gallery_button');

	/*
	 * SETUP THE ENDLESS SCROLL FOR THE NOT YET IN GALLERY IMAGES
	 */
	$('#connect_gallery_photos_cont .not_in_gallery_photos_cont').endlessScroll({
		bottomPixels: 300,
		loader: '',
		ceaseFire: function(i) {
			return cease_fire;
		},
		callback: function (i) {
			do_endless_scroll_callback(i)
		}
	});

});
</script>

<?php //debug($this->data); ?>
<div id="connect_gallery_photos_cont">
	<div class="in_gallery_main_cont">
		<div class="table_header_darker">
			<h2><?php __('Photos in Gallery'); ?></h2>
		</div>
		<div class="in_gallery_photos_cont">
			<?php echo $this->Element('/admin/photo/photo_connect_in_gallery_photo_cont', array( 'connected_photos' => $this->data['PhotoGalleriesPhoto'] )); ?>
		</div>
	</div>
	<div class="not_in_gallery_main_cont">
		<div class="table_header_darker" style="background-color: #292929; color: #AAA;">
			<h2 style="margin-left: 10px; color: #AAA; background: url('/img/admin/icons/grey_left_arrow.png') center left no-repeat; padding-left: 42px;"><?php __('Website Photos'); ?></h2>
			<div class="/*grey_hr*/"></div>
		</div>
		<div id="endless_scroll_loading" class="rounded-corners-small"><?php __('Loading'); ?></div>
		<div id="endless_scroll_div" class="not_in_gallery_photos_cont">
			<?php echo $this->Element('/admin/photo/photo_connect_not_in_gallery_photo_cont', array( 'not_connected_photos' => $not_connected_photos )); ?>
		</div>
	</div>
	<div style="clear: both;"></div>
</div>

<?php //debug($this->data); ?>