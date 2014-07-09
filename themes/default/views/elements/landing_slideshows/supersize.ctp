<link rel="stylesheet" href="/jquery/supersized/slideshow/css/supersized.css" type="text/css" media="screen" />
<link rel="stylesheet" href="/jquery/supersized/slideshow/theme/supersized.shutter.css" type="text/css" media="screen" />
<script type="text/javascript" src="/jquery/supersized/slideshow/js/supersized.3.2.7.js"></script>
<script type="text/javascript" src="/jquery/supersized/slideshow/theme/supersized.shutter.min.js"></script>
<script type="text/javascript" src="/jquery/supersized/slideshow/js/jquery.easing.min.js"></script>
<script type="text/javascript">
	jQuery(function($) {
		<?php 
			$speed = $this->Util->get_not_empty_theme_setting_or($theme_custom_settings, 'landing_page_slideshow_transition_time');
			$interval = $this->Util->get_not_empty_theme_setting_or($theme_custom_settings, 'landing_page_slideshow_interval_time');
			$max_num_images = $this->Util->get_not_empty_theme_setting_or($theme_custom_settings, 'landing_page_slideshow_max_images');
			$gallery_to_use_id = $this->Util->get_not_empty_theme_setting_or($theme_custom_settings, 'landing_page_gallery', null);
			$transition = $this->Util->get_not_empty_theme_setting_or($theme_custom_settings, 'landing_page_transition_type');
			$random = $this->Util->get_not_empty_theme_setting_or($theme_custom_settings, 'random');
		?>
		<?php $slide_show_photo_ids = $this->Theme->get_landing_page_slideshow_images($max_num_images, $gallery_to_use_id); ?>
		<?php $total_images = count($slide_show_photo_ids); ?>

		$.supersized({
			// Functionality
			slide_interval: <?php echo $interval; ?>, // Length between transitions
			transition: <?php echo $transition; ?>, // 0-None, 1-Fade, 2-Slide Top, 3-Slide Right, 4-Slide Bottom, 5-Slide Left, 6-Carousel Right, 7-Carousel Left
			transition_speed: <?php echo $speed; ?>, // Speed of transition
			random: <?php echo $random; ?>, // random slides. No order.

			// Components							
			slide_links: 'blank', // Individual links for each slide (Options: false, 'num', 'name', 'blank')
			slides: [// Slideshow Images
				<?php $count = 1; foreach ($slide_show_photo_ids as $slide_show_photo_id): ?>
					<?php $img_src = $this->Photo->get_photo_path($slide_show_photo_id, $height, $width, .4, true, $crop); ?>
					{image: '<?php echo $img_src['url']; ?>', title: '', thumb: '', url: '/'}<?php if ($count != $total_images): ?>,<?php endif; ?>
				<?php $count++; endforeach; ?>
			]

		});
	});
</script>

