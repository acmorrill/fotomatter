<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<?php echo $this->Element('theme_global_includes'); ?>
	
	<link rel="stylesheet" type="text/css" href="/css/style.css" />
	<link href='http://fonts.googleapis.com/css?family=PT+Sans:400italic,400' rel='stylesheet' type='text/css' />
	
</head>

<body>
	<?php if (count($photos) > 0): ?>
		<div id="white_slider_listing_actual_container">
			<?php foreach($photos as $photo): ?><!--
				--><?php $img_src = $this->Photo->get_photo_path($photo['Photo']['id'], 500, 2000, .4, true); ?><!--
				--><img src="<?php echo $img_src['url']; ?>" <?php echo $img_src['tag_attributes']; ?> /><!--
			--><?php endforeach; ?>
		</div>
		<div id="white_slider_scroll_hide"></div>
	<?php else: ?>
		<h4 style="font-weight: bold; font-style: italic; margin: 10px;"><?php __('This gallery does not have any images yet'); ?></h4><?php // DREW TODO - make this seccion look good ?>
	<?php endif; ?>
		
	<div class="container">
		<?php echo $this->Element('nameTitle'); ?>

		<?php //echo $this->Element('temp_menu'); ?>
		<?php echo $this->Element('menu/two_level_navbar'); ?>


		<style type="text/css">

		</style>
		
		
		<div style="clear: both"></div>
		<div id="white_slider_listing_container"></div>
		
		<script type="text/javascript">
			function calculate_scroll_control_div_width() {
				var window_width = jQuery(window).width();
				var control_width = window_width/10;
				
				jQuery('#white_slider_scroll_control_inner .scroll_control_div').width(control_width);
			}
			
			function calculate_slider_scroll_position() {
				var control = jQuery('#white_slider_scroll_control_inner .scroll_control_div');
				
//				console.log (left);
//				console.log (width);
//				console.log (control.position().left);
//				console.log (control.width());

				var left = control.position().left;
//				var width = control.width();
				var parent_width = control.parent().width();
				var percentage = left/parent_width;
				
				var slider = jQuery('#white_slider_listing_actual_container');
				var slider_width = slider[0].scrollWidth;
				
				console.log (slider_width);
				
				var slider_scroll = Math.round(slider_width*percentage);
				
				slider.scrollLeft(slider_scroll);
			}
			
			jQuery(document).ready(function() {
				jQuery('#white_slider_scroll_control_inner .scroll_control_div').draggable({
					axis: "x",
					containment: 'parent',
					drag: function( event, ui ) {
						var left = ui.position.left;
						var width = ui.helper.width();
						
						calculate_slider_scroll_position();
					}
				});
				
				
//				var scroll_control_div = jQuery('#white_slider_scroll_control_inner .scroll_control_div');
			});
			
			jQuery(window).load(function() {
				calculate_scroll_control_div_width();
			});
		</script>
		
		<div id="white_slider_scroll_control">
			<div id="white_slider_scroll_control_inner">
				<div class="scroll_control_div"></div>
				<?php foreach($photos as $photo): ?><!--
					--><?php $img_src = $this->Photo->get_photo_path($photo['Photo']['id'], 50, 300, .4, true); ?><!--
					--><img src="<?php echo $img_src['url']; ?>" <?php echo $img_src['tag_attributes']; ?> /><!--
				--><?php endforeach; ?>
			</div>
		</div>
		
		
		
	</div>

</body>
</html>