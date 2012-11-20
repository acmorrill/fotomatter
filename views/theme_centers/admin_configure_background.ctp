<?php 
	// DREW TODO - get rid of rounding errors in the javascript logic

	$background_config = $theme_config['admin_config']['theme_background_config'];
	$theme_has_dynamic_background = $background_config['theme_has_dynamic_background'];
	
	//debug($background_config);
	$overlay_web_path = $background_config['overlay_image']['web_path'];
	$overlay_abs_path = $background_config['overlay_image']['absolute_path'];
	$default_bg_web_path = $background_config['default_bg_image']['web_path'];
	$default_bg_abs_path = $background_config['default_bg_image']['absolute_path'];
	$uploaded_bg_abs_path = $this->Theme->get_theme_uploaded_background_abs_path();
	$uploaded_bg_web_path = $this->Theme->get_theme_uploaded_background_web_path();
	
//	debug($overlay_web_path);
//	debug($overlay_abs_path);
//	debug($default_bg_web_path);
//	debug($default_bg_abs_path);
//	debug($uploaded_bg_abs_path);
//	debug($uploaded_bg_web_path);
	
	
	$using_custom_background_image = $this->Theme->get_theme_hidden_setting('using_custom_background_image', false);
	if ($uploaded_bg_abs_path === false) {
		$using_custom_background_image = false;
	}
	if ($using_custom_background_image) {
		$current_background_web_path = $uploaded_bg_web_path;
		$current_background_abs_path = $uploaded_bg_abs_path;
	} else {
		$current_background_web_path = $default_bg_web_path;
		$current_background_abs_path = $default_bg_abs_path;
	}

	$current_background_size = getimagesize($current_background_abs_path);
	list($orig_background_width, $orig_background_height, $current_background_size_type, $current_background_size_attr) = $current_background_size;
	
	$palette_background_size = getimagesize($overlay_abs_path);
	list($orig_palette_background_width, $orig_palette_background_height, $palette_background_size_type, $palette_background_size_attr) = $palette_background_size;
	
	
//	debug($current_background_width);
//	debug($current_background_height);
//	debug($current_background_web_path);
	debug('came here 1');
	
	
	$max_background_image_width = 1600;
	$max_background_image_height = 1200;
	$max_palette_width = $max_background_image_width/2;
	$max_palette_height = $max_background_image_height/2;
	
	$current_background_width = $orig_background_width/2;
	$current_background_height = $orig_background_height/2;
	$palette_background_width = $orig_palette_background_width/4;
	$palette_background_height = $orig_palette_background_height/4;
	
	$palette_start_left = ($max_palette_width/2)-($palette_background_width/2);
	$palette_start_top = ($max_palette_height/2)-($palette_background_height/2);
?>

<script type="text/javascript">
	function reload_size_change_background() {
		var current_background_width = jQuery('#theme_background_palette .theme_background_image_cont').width();
		var current_background_height = jQuery('#theme_background_palette .theme_background_image_cont').height();
		var current_background_position = jQuery('#theme_background_palette .theme_background_image_cont').position();
		var current_background_left = current_background_position.left;
		var current_background_top = current_background_position.top;
		

		var palette_background_width = <?php echo $palette_background_width; ?>;
		var palette_background_height = <?php echo $palette_background_height; ?>;
		var orig_palette_background_width = <?php echo $orig_palette_background_width; ?>;
		var orig_palette_background_height = <?php echo $orig_palette_background_height; ?>;
		
		
		var final_background_width = (orig_palette_background_width * current_background_width) / palette_background_width;
		var final_background_height = (orig_palette_background_height * current_background_height) / palette_background_height;
		
		
		var palette_left = <?php echo $palette_start_left; ?>;
		var palette_top = <?php echo $palette_start_top; ?>;
		var orig_palette_background_width = final_background_width;
		var orig_palette_background_height = final_background_height;
		
		var small_background_left = palette_left - current_background_left;
		var small_background_top = palette_top - current_background_top;
		var final_background_left = (orig_palette_background_width * small_background_left) / current_background_width;
		var final_background_top = (orig_palette_background_height * small_background_top) / current_background_height;

		var using_custom_background_image = <?php echo ($using_custom_background_image == true) ? 'true' : 'false'; ?>;
		
		jQuery.ajax({
			type: 'post',
			url: '/admin/theme_centers/ajax_create_merged_bg_and_save_bg_config/',
			data: {
				'overlay_abs_path': '<?php echo $overlay_abs_path; ?>',
				'current_background_abs_path': '<?php echo $current_background_abs_path; ?>',
				'final_background_width': final_background_width,
				'final_background_height': final_background_height,
				'final_background_left': final_background_left,
				'final_background_top': final_background_top,
				'using_custom_background_image': using_custom_background_image
			},
			success: function(the_data) {
				console.log ("came into success");
				//console.log (data);
			},
			complete: function() {
//				console.log ("complete");
			},
			error: function(jqXHR, textStatus, errorThrown) {
//				console.log ("error");
//				console.log (textStatus);
//				console.log (errorThrown);
			},
			dataType: 'json'
		});
		
//		console.log (final_background_left);
//		console.log (final_background_top);
//		console.log (final_background_width);
//		console.log (final_background_height);
	}
	
	jQuery(document).ready(function() {
		jQuery('#theme_background_palette .theme_background_image_cont').resizable({
			aspectRatio: true,
			containment: "parent",
			handles: 'se', // DREW TODO - maybe add more but need to test all of them
			stop: function() {
				reload_size_change_background();
			},
			resize: function(event, ui) {
				var size = ui.size;
				
				jQuery('#theme_background_palette .theme_background_image').css('width', size.width).css('height', size.height);
			}
		}).draggable({
			containment: "#theme_background_palette",
			//handle: '.theme_background_image',
			cursor: 'move',
			scroll: false,
			stop: function() {
				reload_size_change_background();
			},
			drag: function(event, ui) {
				var position = ui.position;
				
				jQuery('#theme_background_palette .theme_background_image').css('left', position.left).css('top', position.top);
				//console.log (position);
			}
		});
	});
</script>
<div id="configure_theme_background" class="content_only_page">
	<?php if ($theme_has_dynamic_background === true): ?>
		<?php // DREW TODO - make the below div have the default bg color of the theme ?>
		<div id="theme_background_palette" style="background-color: white; position: relative; outline: 1px solid green; width: <?php echo $max_palette_width; ?>px; height: <?php echo $max_palette_height; ?>px;">
			<img class="theme_background_image" src="<?php echo $current_background_web_path; ?>" style="display: inline-block; position: absolute; left: <?php echo ($max_palette_width/2)-($current_background_width/2); ?>px; top: <?php echo ($max_palette_height/2)-($current_background_height/2); ?>px; width: <?php echo $current_background_width; ?>px; height: <?php echo $current_background_height; ?>px;" />
			<img class="theme_overlay_image" src="<?php echo $overlay_web_path; ?>" style="display: inline-block; position: absolute; left: <?php echo $palette_start_left; ?>px; top: <?php echo $palette_start_top; ?>px; width: <?php echo $palette_background_width; ?>px; height: <?php echo $palette_background_height; ?>px;" />
			<div class="theme_background_image_cont" style="cursor: move; outline: 1px solid blue; display: inline-block; position: absolute; left: <?php echo ($max_palette_width/2)-($current_background_width/2); ?>px; top: <?php echo ($max_palette_height/2)-($current_background_height/2); ?>px; width: <?php echo $current_background_width; ?>px; height: <?php echo $current_background_height; ?>px;"></div>
		</div>
		
	<?php // DREW TODO - put a note on this page that to see the background image change the user must hard refresh the browser (or use a no cache header for that image) ?>
	
	
		
	
	
		<?php /*<div class="theme_bg_context_image_cont">
			<div class="theme_bg_context_bg_darken"></div>
			<div class="theme_bg_size_change_palette">
				<div id="theme_bg_size_change_palette">
					<img class="theme_bg_size_image"/>
				</div>
			</div>
		</div> */ ?>
	<?php else: ?>
		<p><?php __('The current theme does not have a dynamic background.'); ?></p>
	<?php endif; ?>
</div>