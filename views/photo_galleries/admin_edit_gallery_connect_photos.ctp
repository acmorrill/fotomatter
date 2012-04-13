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


<style type="text/css">
	#connect_gallery_photos_cont {
		min-height: 200px;
		clear: both;
		background: #222222;
	}
	
	#connect_gallery_photos_cont .in_gallery_photos_cont, #connect_gallery_photos_cont .not_in_gallery_photos_cont {
		height: 500px;
		overflow-x: hidden;
		overflow-y: auto;
	}
	
	#connect_gallery_photos_cont .in_gallery_photos_cont {
		/*border-right: 4px solid #303030;*/
		float: left;
		width: 39%;
	}
	
	#connect_gallery_photos_cont .not_in_gallery_photos_cont {
		float: right;
		overflow-y: scroll;
		text-align: right;
		width: 59%;
	}
	
	#connect_gallery_photos_cont .connect_photo_container {
		width: 100px;
		height: 100px;
		display: inline-block;
		margin: 20px;
		margin-bottom:0px;
		background: #303030;
		position: relative;
	}
	
	/*#connect_gallery_photos_cont .not_in_gallery_photos_cont .connect_photo_container {
		margin-right: 35px;
		margin-left: 10px;
		margin-top: 25px;
	}
	#connect_gallery_photos_cont .in_gallery_photos_cont .connect_photo_container {
		margin-left: 35px;
		margin-right: 10px;
		margin-top: 25px;
	}*/
	
	#connect_gallery_photos_cont .connect_photo_container .abs_image_br , #connect_gallery_photos_cont .connect_photo_container .abs_image_tr {
		position: absolute;
		right: -15px;
		bottom: -15px;
		cursor: pointer;
	}
	
	#connect_gallery_photos_cont .connect_photo_container .abs_image_tr {
		right: -15px;
		top: -15px;
		bottom: inherit;
	}
	
</style>

<script type="text/javascript" charset="utf-8">
var cease_fire = false;
var in_callback = false;
$(function() {
	/*
	 *	SETEP THE ADD TO GALLERY BUTTON
	 */
	jQuery('#connect_gallery_photos_cont .not_in_gallery_photos_cont .add_to_gallery_button').click(function() {
		var photo_id = jQuery(this).closest('.connect_photo_container').attr('photo_id');
		var to_delete = jQuery(this).closest('.connect_photo_container');
		
		jQuery.post('/admin/photo_galleries/ajax_movephoto_into_gallery/'+photo_id+'/<?php echo $gallery_id; ?>/', function(data) {
			if (data.code == 1) {
				to_delete.remove();
				var move_to_cont = jQuery('#connect_gallery_photos_cont .in_gallery_photos_cont');
				move_to_cont.append(data.html).animate({ 
					scrollTop: move_to_cont.prop("scrollHeight") 
				}, 1000);
			} else {
				major_error_recover(data.message);
			}
		}, 'json');
	});

	
	/*
	 * SETUP THE ENDLESS SCROLL FOR THE NOT YET IN GALLERY IMAGES
	 */
	$('#connect_gallery_photos_cont .not_in_gallery_photos_cont').endlessScroll({
		ceaseFire: function(i) {
			return cease_fire;
		},
		callback: function (i) {
			if (in_callback == true) {
				return;
			}
			
			in_callback = true;
			
			var last_photo_id = jQuery('#connect_gallery_photos_cont .not_in_gallery_photos_cont .connect_photo_container:last').attr('photo_id');
			jQuery.ajax({
				 url : '/admin/photo_galleries/ajax_get_more_photos_to_connect/<?php echo $gallery_id; ?>/'+last_photo_id+'/',
				 success : function (photo_divs) {
					if (photo_divs.count > 0) {
						var last_div = jQuery('#connect_gallery_photos_cont .not_in_gallery_photos_cont .connect_photo_container:last');
						last_div.after(photo_divs.html);
					} else {
						cease_fire = true;
					}
					in_callback = false;
				},
				dataType: "json"
			}); 
		}
	});

});
</script>

<div class="clear">
	<div class="table_header_darker left" style="width: 39%; ">
		<h2><?php __('Remove Photos From Gallery'); ?></h2>
	</div>
	<div class="table_header_darker right" style="width: 59%;">
		
	</div>
</div>
<?php //debug($this->data['Photo']); ?>
<div id="connect_gallery_photos_cont">
	<div class="in_gallery_photos_cont">
		<?php echo $this->Element('/admin/photo/photo_connect_in_gallery_photo_cont', array( 'connected_photos' => $this->data['Photo'] )); ?>
	</div>
	<div id="endless_scroll_div" class="not_in_gallery_photos_cont">
		<?php echo $this->Element('/admin/photo/photo_connect_not_in_gallery_photo_cont', array( 'not_connected_photos' => $not_connected_photos )); ?>
	</div>
	<div style="clear: both;"></div>
</div>

<?php //debug($this->data); ?>