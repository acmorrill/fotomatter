<div class="logo_size_cont">
	
	<?php //debug($theme_config); ?>
	<?php 
		$logo_max_width = isset($theme_config['admin_config']['logo_config']['available_space']['width']) ? $theme_config['admin_config']['logo_config']['available_space']['width'] : 400;
		$logo_max_height = isset($theme_config['admin_config']['logo_config']['available_space']['height']) ? $theme_config['admin_config']['logo_config']['available_space']['height'] : 200;
		
		$avail_space_screenshot_web_path = '';
		if (!empty($theme_config['admin_config']['logo_config']['available_space_screenshot'])) {
			$avail_space_screenshot_web_path = $theme_config['admin_config']['logo_config']['available_space_screenshot']['web_path'];
			$avail_space_screenshot_path = $theme_config['admin_config']['logo_config']['available_space_screenshot']['absolute_path'];
			$avail_space_screenshot_size = getimagesize($avail_space_screenshot_path);
			$logo_max_width = $avail_space_screenshot_size[0];
			$logo_max_height = $avail_space_screenshot_size[1];
		}
		
		
		$logo_default_width = isset($theme_config['admin_config']['logo_config']['default_space']['width']) ? $theme_config['admin_config']['logo_config']['default_space']['width'] : 300;
		$logo_default_height = isset($theme_config['admin_config']['logo_config']['default_space']['height']) ? $theme_config['admin_config']['logo_config']['default_space']['height'] : 150;
		$logo_current_width = $this->Theme->get_theme_setting('logo_current_width', $logo_default_width);
		$logo_current_height = $this->Theme->get_theme_setting('logo_current_height', $logo_default_height);
		
		$use_logo_width = min($logo_current_width, $logo_max_width);
		$use_logo_height = min($logo_current_height, $logo_max_height);
		
		
		$start_logo_path = $this->ThemeLogo->get_logo_cache_size_path($use_logo_height, $use_logo_width, true);
		$image_size = getimagesize($start_logo_path);
		$use_logo_width = $image_size[0];
		$use_logo_height = $image_size[1];
		$start_logo_web_path = $this->ThemeLogo->get_logo_cache_size_path($use_logo_height, $use_logo_width);
		
		$logo_current_top = $this->Theme->get_theme_setting('logo_current_top', 0);
		$logo_current_left = $this->Theme->get_theme_setting('logo_current_left', 0);
		
		// check to see that the logo is still in the specified spot
		if (($logo_current_left + $use_logo_width) > $logo_max_width) {
			$logo_current_left = $logo_max_width - $use_logo_width;
		}
		if (($logo_current_top + $use_logo_height) > $logo_max_height) {
			$logo_current_top = $logo_max_height - $use_logo_height;
		}
	?>
	
	<?php // DREW TODO - put the below into admin.css ?>
	<style type="text/css">
		.logo_size_change_palette {
			border: 5px solid white;
			position: relative;
			margin-bottom: 15px;
			background: #222;
		}
		.logo_size_change_palette img {}
		.palette_top_legend, .palette_right_legend {
			position: absolute;
			
			color:white;
			border:0px solid red;
			font-family: ‘Trebuchet MS’, Helvetica, sans-serif;
			font-size:24px;
			font-weight:normal;
			text-shadow: 0px 0px 1px #333;
			text-align: left;
		}
		.palette_top_legend {
			width: 100%;
			top: -30px;
		}
		.palette_right_legend {
			writing-mode:tb-rl;
			-webkit-transform:rotate(90deg);
			-moz-transform:rotate(90deg);
			-o-transform: rotate(90deg);
			white-space:nowrap;
			
			right: -36px;
			top: 0;
			height: 26px;
			width: 26px;
			z-index: 0;
		}
	</style>
	
	<script type="text/javascript">
		function reload_size_change_logo() {
			var current_logo_height = jQuery('#logo_size_change_palette').height();
			var current_logo_width = jQuery('#logo_size_change_palette').width();
			var current_logo_position = jQuery('#logo_size_change_palette').position();
			var current_logo_top = current_logo_position.top;
			var current_logo_left = current_logo_position.left;
			
			jQuery.ajax({
				type: 'post',
				url: '/admin/theme_centers/ajax_get_logo_webpath_and_save_dimension/'+current_logo_height+'/'+current_logo_width+'/'+current_logo_top+'/'+current_logo_left+'/',
				data: {},
				success: function(data) {
					if (data.code == 1) {
						//jQuery('#logo_size_change_palette').width(current_logo_width).height(current_logo_height);
						jQuery('#logo_size_change_palette .logo_size_image').attr('src', data.logo_path);
					} else {
						major_error_recover('Failed to resize the logo image in logo resize');
					}
				},
				complete: function() {
					
				},
				error: function(jqXHR, textStatus, errorThrown) {
					
				},
				dataType: 'json'
			});
		}
		
		
		
		jQuery(document).ready(function() {
			jQuery('#current_logo_width, #current_logo_height').change(function() {
				reload_size_change_logo();
			});
			
			jQuery('#logo_size_change_palette').resizable({
				aspectRatio: true,
				containment: "parent",
				handles: 'se', // DREW TODO - maybe add more but need to test all of them
				stop: function() {
					reload_size_change_logo();
				}
			}).draggable({
				containment: ".logo_size_change_palette",
				handle: '.logo_size_image',
				cursor: 'move',
				scroll: false,
				stop: function() {
					reload_size_change_logo();
				}
			});
		});
	</script>
	
	<div style="min-height: 79px; margin-top: 48px; position: relative; display: inline-block;">
		<div class="palette_top_legend">
			<?php __('Theme Max Width'); ?>
		</div>
		<div class="palette_right_legend">
			<?php __('Theme Max Height'); ?>
		</div>
		<div class="logo_size_change_palette" style="width: <?php echo $logo_max_width; ?>px; height: <?php echo $logo_max_height; ?>px; <?php if (!empty($avail_space_screenshot_web_path)): ?>background: url('<?php echo $avail_space_screenshot_web_path; ?>') top left no-repeat;<?php endif; ?>">
			<div id="logo_size_change_palette" style="width: <?php echo $use_logo_width; ?>px; height: <?php echo $use_logo_height; ?>px; top: <?php echo $logo_current_top; ?>px; left: <?php echo $logo_current_left; ?>px; outline: 1px solid #333; display: inline-block; cursor: move;">
				<img class="logo_size_image" src="<?php echo $start_logo_web_path; ?>" />
			</div>
		</div>
	</div>
	
	<?php /*
	<label for="current_logo_width"><?php __('Max Logo Width'); ?></label>
	<select id="current_logo_width">
		<?php for($i = 20; $i <= $logo_max_width; $i++): ?>
			<option <?php if($use_logo_width == $i): ?>selected="selected"<?php endif; ?>  value="<?php echo $i; ?>"><?php echo $i; ?></option>
		<?php endfor; ?>
	</select><br/>
	<label for="current_logo_height"><?php __('Max Logo Height'); ?></label>
	<select id="current_logo_height">
		<?php for($i = 20; $i <= $logo_max_height; $i++): ?>
			<option <?php if($use_logo_height == $i): ?>selected="selected"<?php endif; ?>  value="<?php echo $i; ?>"><?php echo $i; ?></option>
		<?php endfor; ?>
	</select>
	 * 
	 */ ?>
</div>





