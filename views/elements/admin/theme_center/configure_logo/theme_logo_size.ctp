<div class="logo_size_cont">
	
	<?php //debug($theme_config); ?>
	<?php 
		$logo_max_width = isset($theme_config['admin_config']['logo_config']['available_space']['width']) ? $theme_config['admin_config']['logo_config']['available_space']['width'] : 400;
		$logo_max_height = isset($theme_config['admin_config']['logo_config']['available_space']['height']) ? $theme_config['admin_config']['logo_config']['available_space']['height'] : 200;
		
		$logo_default_width = isset($theme_config['admin_config']['logo_config']['default_space']['width']) ? $theme_config['admin_config']['logo_config']['default_space']['width'] : 300;
		$logo_default_height = isset($theme_config['admin_config']['logo_config']['default_space']['height']) ? $theme_config['admin_config']['logo_config']['default_space']['height'] : 150;
		

		$logo_current_width = $this->Theme->get_theme_setting('logo_current_width', $logo_default_width);
		$logo_current_height = $this->Theme->get_theme_setting('logo_current_height', $logo_default_height);
		
		$use_logo_width = min($logo_current_width, $logo_max_width);
		$use_logo_height = min($logo_current_height, $logo_max_height);
	?>
	
	<?php // DREW TODO - put the below into admin.css ?>
	<style type="text/css">
		.logo_size_change_palette {
			border: 5px solid white;
			position: relative;
			margin-top: 20px;
			margin-bottom: 15px;
			background: #222;
		}
		.logo_size_change_palette img {
			outline: 1px solid gray;
		}
		.palette_top_legend, .palette_right_legend {
			position: absolute;
			
			color:white;
			border:0px solid red;
			font-family: ‘Trebuchet MS’, Helvetica, sans-serif;
			font-size:24px;
			font-weight:normal;
			text-shadow: 0px 0px 1px #333;
			text-align: center;
		}
		.palette_top_legend {
			width: 75%;
			top: -30px;
		}
		.palette_right_legend {
			writing-mode:tb-rl;
			-webkit-transform:rotate(90deg);
			-moz-transform:rotate(90deg);
			-o-transform: rotate(90deg);
			white-space:nowrap;
			
			right: -36px;
			top: 20%;
			height: 26px;
			width: 26px;
			z-index: 0;
		}
	</style>
	
	<script type="text/javascript">
		function reload_size_change_logo() {
			var current_logo_width = jQuery('#current_logo_width').val();
			var current_logo_height = jQuery('#current_logo_height').val();
			
			jQuery.ajax({
				type: 'post',
				url: '/admin/theme_centers/ajax_get_logo_webpath_and_save_dimension/'+current_logo_height+'/'+current_logo_width+'/',
				data: {},
				success: function(data) {
					if (data.code == 1) {
						jQuery('#logo_size_change_palette').width(current_logo_width).height(current_logo_height);
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
		});
	</script>
	
	<div style="min-height: 79px;">
		<div id="logo_size_change_palette" class="logo_size_change_palette" style="width: <?php echo $use_logo_width; ?>px; height: <?php echo $use_logo_height; ?>px;">
			<div class="palette_top_legend">
				<?php __('Width'); ?>
			</div>
			<div class="palette_right_legend">
				<?php __('Height'); ?>
			</div>
			<img class="logo_size_image" src="<?php echo $this->ThemeLogo->get_logo_cache_size_path($use_logo_height, $use_logo_width); ?>" />
		</div>
	</div>
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
</div>






