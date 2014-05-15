<?php 
	$speed = $this->Util->get_not_empty_theme_setting_or($theme_custom_settings, 'landing_page_slideshow_transition_time');
	$interval = $this->Util->get_not_empty_theme_setting_or($theme_custom_settings, 'landing_page_slideshow_interval_time');
	$max_num_images = $this->Util->get_not_empty_theme_setting_or($theme_custom_settings, 'landing_page_slideshow_max_images');
	$gallery_to_use_id = $this->Util->get_not_empty_theme_setting_or($theme_custom_settings, 'landing_page_gallery', null);
?>

<script type="text/javascript">
	jQuery(document).ready(function() {
		function imageLoaded() {
			// function to invoke for loaded image
			// decrement the counter
			counter--; 
			if( counter === 0 ) {
				images.show();
				jQuery('#landing_slide_show_container').fadeSlideShow({
					width: <?php echo $width; ?>, // default width of the slideshow
					height: <?php echo $height; ?>, // default height of the slideshow
					speed: <?php echo $speed; ?>, // default animation transition speed
					interval: <?php echo $interval; ?>, // default interval between image change
					PlayPauseElement: false, // default css id for the play / pause element
					PlayText: 'Play', // default play text
					PauseText: 'Pause', // default pause text
					NextElement: false, // default id for next button
					NextElementText: 'Next >', // default text for next button
					PrevElement: false, // default id for prev button
					PrevElementText: '< Prev', // default text for prev button
					ListElement: false, // default id for image / content controll list
					ListLi: false, // default class for li's in the image / content controll 
					ListLiActive: 'fssActive', // default class for active state in the controll list
					addListToId: false, // add the controll list to special id in your code - default false
					allowKeyboardCtrl: true, // allow keyboard controlls left / right / space
					autoplay: true // autoplay the slideshow
				});
			}
		}
		var images = jQuery('#landing_slide_show_container img');
		var counter = images.length;  // initialize the counter

		images.each(function() {
			if( this.complete ) {
				imageLoaded.call( this );
			} else {
				$(this).one('load', imageLoaded);
			}
		});
	});
</script>

<?php 
	$slide_show_photo_ids = $this->Theme->get_landing_page_slideshow_images($max_num_images, $gallery_to_use_id);
	
	$first_image_src = null;
	if (!empty($slide_show_photo_ids[0])) {
		$first_image_src_raw = $this->Photo->get_photo_path($slide_show_photo_ids[0], 453, 556, .4, true, true);
		$first_image_src = $first_image_src_raw['url'];
	}
	
	$slide_show_photo_ids = array_reverse($slide_show_photo_ids); // have to reverse it for the slideshow plugin
?>
<style type="text/css">
	#landing_slide_show_container {
		width: <?php echo $width; ?>px;
		height: <?php echo $height; ?>px;
		position: relative;
		overflow: hidden;
		<?php if (!empty($first_image_src)): ?>
			background: <?php echo $background_color; ?> url(<?php echo $first_image_src; ?>) top left no-repeat;
		<?php endif; ?>
	}
	#landing_slide_show_container .slide_show_image {
		position: absolute;
		display: none;
	}
</style>
<div id="landing_slide_show_container">
	<?php foreach ($slide_show_photo_ids as $slide_show_photo_id): ?>
		<?php $img_src = $this->Photo->get_photo_path($slide_show_photo_id, 453, 556, .4, true, true); ?>
		<img class="slide_show_image" src="<?php echo $img_src['url']; ?>" <?php echo $img_src['tag_attributes']; ?> />
	
	<?php endforeach; ?>
</div>
