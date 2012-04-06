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
		background: #191919;
		padding: 20px;
	}
	
	#connect_gallery_photos_cont .in_gallery_photos_cont, #connect_gallery_photos_cont .not_in_gallery_photos_cont {
		width: 49%;
		margin: 3px;
		height: 500px;
		overflow-x: hidden;
		overflow-y: auto;
	}
	
	#connect_gallery_photos_cont .in_gallery_photos_cont {
		border-right: 4px dotted grey;
		float: left;
	}
	
	#connect_gallery_photos_cont .not_in_gallery_photos_cont {
		float: right;
		overflow-y: scroll;
	}
	
	#connect_gallery_photos_cont .connect_photo_container {
		width: 100px;
		height: 100px;
		display: inline-block;
		margin-right: 35px;
		margin-bottom: 35px;
		background: #303030;
		position: relative;
	}
	
	#connect_gallery_photos_cont .connect_photo_container .abs_image_br {
		position: absolute;
		right: -10px;
		bottom: -10px;
		cursor: pointer;
	}
	
</style>

<script type="text/javascript" charset="utf-8">
var cease_fire = false;
var in_callback = false;
$(function() {
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

<div id="connect_gallery_photos_cont">
	<div class="in_gallery_photos_cont">
		<?php foreach ($this->data['Photo'] as $the_photo): ?>
			<?php echo $this->Element('/admin/photo/photo_connect_in_gallery_photo_cont', array( 'the_photo' => $the_photo )); ?>
		<?php endforeach; ?>
	</div>
	<div id="endless_scroll_div" class="not_in_gallery_photos_cont">
		<?php echo $this->Element('/admin/photo/photo_connect_not_in_gallery_photo_cont', array( 'not_connected_photos' => $not_connected_photos )); ?>
	</div>
	<div style="clear: both;"></div>
</div>

<?php //debug($this->data); ?>