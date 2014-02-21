<?php $background_settings = $this->Theme->get_theme_background_config_values($theme_config); ?>
<?php //debug($background_settings); ?>



<script type="text/javascript">
	function get_number_sign($num) {
		if ($num >= 0) {
			return "+";
		}

		return "";
	}
	
	
	var brightness_cont;
	var contrast_cont;
	var desaturation_cont;
	var inverted_cont;
	function reload_size_change_background() {
		show_modal('Processing Background Image', 0, function() {}, false);
		
		
		var current_brightness = brightness_cont.slider('value');
		var current_contrast = contrast_cont.slider('value');
		var current_desaturation = desaturation_cont.slider('value');
		var current_inverted = inverted_cont.prop('checked') ? 1 : 0;
//		console.log ("============================");
//		console.log (current_brightness);
//		console.log (current_contrast);
//		console.log (current_desaturation);
//		console.log (current_inverted);
//		console.log ("============================");
		
		/////////////////////////////////////////////////////////////////////////////
		// grab the custom overlay settings
		var custom_overlay_transparency_settings = {};
		custom_overlays_cont.each(function() {
			var to_save_value = ((-3/25) * jQuery(this).slider('value')) + 16;
			var custom_overlay_key = jQuery(this).attr('data-custom-overlay-key');
			custom_overlay_transparency_settings[custom_overlay_key] = to_save_value;
		});
		
		
		var current_background_width = jQuery('#theme_background_palette .theme_background_image_cont').width();
		var current_background_height = jQuery('#theme_background_palette .theme_background_image_cont').height();
		var current_background_position = jQuery('#theme_background_palette .theme_background_image_cont').position();
		var current_background_left = current_background_position.left;
		var current_background_top = current_background_position.top;
		

		var palette_background_width = <?php echo $background_settings['palette_background_width']; ?>;
		var palette_background_height = <?php echo $background_settings['palette_background_height']; ?>;
		var orig_palette_background_width = <?php echo $background_settings['orig_palette_background_width']; ?>;
		var orig_palette_background_height = <?php echo $background_settings['orig_palette_background_height']; ?>;
		
		
		var final_background_width = (orig_palette_background_width * current_background_width) / palette_background_width;
		var final_background_height = (orig_palette_background_height * current_background_height) / palette_background_height;
		
		
		var palette_left = <?php echo $background_settings['palette_start_left']; ?>;
		var palette_top = <?php echo $background_settings['palette_start_top']; ?>;
		var orig_palette_background_width = final_background_width;
		var orig_palette_background_height = final_background_height;
		
		var small_background_left = palette_left - current_background_left;
		var small_background_top = palette_top - current_background_top;
		var final_background_left = (orig_palette_background_width * small_background_left) / current_background_width;
		var final_background_top = (orig_palette_background_height * small_background_top) / current_background_height;

		// user has or has not uploaded a custom background image
		var using_custom_background_image = <?php echo ($background_settings['use_theme_background'] == true) ? 'true' : 'false'; ?>;
		
		var theme_background_image = $("#theme_background_palette .theme_background_image");
		jQuery.ajax({
			type: 'post',
			url: '/admin/theme_centers/ajax_create_merged_bg_and_save_bg_config/',
			data: {
				'overlay_abs_path': '<?php echo $background_settings['overlay_abs_path']; ?>',
				'current_background_abs_path': '<?php echo $background_settings['current_background_abs_path']; ?>',
				'final_background_width': final_background_width,
				'final_background_height': final_background_height,
				'final_background_left': final_background_left,
				'final_background_top': final_background_top,
				'current_background_width': current_background_width,
				'current_background_height': current_background_height,
				'current_background_left': current_background_left,
				'current_background_top': current_background_top,
				'using_custom_background_image': using_custom_background_image,
				'current_brightness': current_brightness,
				'current_contrast': current_contrast,
				'current_desaturation': current_desaturation,
				'current_inverted': current_inverted,
				'custom_overlay_transparency_settings': custom_overlay_transparency_settings
			},
			success: function(the_data) {
//				console.log ("came into success");
				//console.log (data);
			},
			complete: function() {
//				console.log ("complete");
				d = new Date();
				theme_background_image.load(function() {
					remove_modal();
				});
				var start_src = theme_background_image.attr('start-src') + "?t="+d.getTime();
				theme_background_image.attr("src", start_src);
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
		brightness_cont = jQuery('#bg_brightness');
		contrast_cont = jQuery('#bg_contrast');
		desaturation_cont = jQuery('#bg_desaturation');
		custom_overlays_cont = jQuery('#custom_overlay_transparency_container .slider_container');
		inverted_cont = jQuery('#bg_inverted');
	
		jQuery('#theme_background_palette .theme_background_image_cont').resizable({
			aspectRatio: true,
			//containment: "parent",
			handles: 'ne,se,sw,nw', // DREW TODO - maybe add more but need to test all of them
			resize: function(event, ui) {
				var size = ui.size;
				
				var new_style = jQuery(this).attr('style');
				//jQuery('#theme_background_palette .theme_background_image').css('width', size.width).css('height', size.height);
				jQuery('#theme_background_palette .theme_background_image').attr('style', new_style);
			}
		}).draggable({
			//containment: "#theme_background_palette",
			//handle: '.theme_background_image',
			cursor: 'move',
			scroll: false,
			drag: function(event, ui) {
				var position = ui.position;
				
				jQuery('#theme_background_palette .theme_background_image').css('left', position.left).css('top', position.top);
				//console.log (position);
			}
		});
		
		
		
		jQuery('#change_background_dialog').dialog({
			autoOpen: false,
			title: "<?php __('Choose Background'); ?>",
			buttons: [
				{
					text: "<?php __('Use Selected'); ?>",
					click: function() {
						show_modal('Processing Background Image', 0, function() {}, false);
						jQuery('#choose_background_form').submit();
						jQuery(this).dialog('close');
					}
				},
				{
					text: "<?php __('Upload New'); ?>",
					click: function() {
						show_modal('Processing Background Image', 0, function() {}, false);
						jQuery('#hidden_background_file_chooser').click();
						jQuery(this).dialog('close');
					}
				}
			],
			minWidth: 500,
			minHeight: 200,
			modal: true,
			resizable: false
		});
			
		jQuery('#hidden_background_file_chooser').change(function() {
			jQuery('#upload_background_file_form').submit();
		});
			
		jQuery('#upload_background_button').click(function() {
			jQuery('#change_background_dialog').dialog('open');
		});
		
		
		brightness_cont.slider({
			min: -255,
			max: 255,
			value: <?php echo $background_settings['current_brightness']; ?>,
			step: 1,
			slide: function(e, ui) {
				var percent = Math.ceil((ui.value/255) * 100);
				if (ui.value === 0 || percent === 0) {
					jQuery(this).find('.slider_label span').text("Default");
				} else {
					jQuery(this).find('.slider_label span').text(get_number_sign(ui.value) + percent + "%");
				}
			}
		});
		contrast_cont.slider({
			min: -100,
			max: 100,
			value: <?php echo $background_settings['current_contrast']; ?>,
			step: 1,
			slide: function(e, ui) {
				if (ui.value === 0) {
					jQuery(this).find('.slider_label span').text("Default");
				} else {
					jQuery(this).find('.slider_label span').text(get_number_sign(ui.value) + ui.value + "%");
				}
			}
		});
		desaturation_cont.slider({
			min: 0,
			max: 100,
			value: <?php echo $background_settings['current_desaturation']; ?>,
			step: 1,
			slide: function(e, ui) {
				if (ui.value === 100) {
					jQuery(this).find('.slider_label span').text("Default");
				} else {
					jQuery(this).find('.slider_label span').text(ui.value + "%");
				}
			}
		});
		custom_overlays_cont.each(function() {
			var curr_value = jQuery(this).attr('data-custom-overlay-value');
			jQuery(this).slider({
				min: 0,
				max: 125,
				value: jQuery(this).attr('data-custom-overlay-value'),
				step: 1,
				slide: function(e, ui) {
					if (ui.value === 100) {
						jQuery(this).find('.slider_label span').text("Default");
					} else {
						jQuery(this).find('.slider_label span').text(ui.value + "%");
					}
				}
			});
		});
		
		
		jQuery('#bg_inverted').button();
		
		
		//reload_size_change_background(); // DREW TODO - turn this back on
		jQuery('#save_custom_background_button').click(function() {
			reload_size_change_background();
		});
	});
	
	
	
	
	
</script>


<style type="text/css">
	.cache_sample_image_cont {
		width: 150px;
		height: 80px;
		display: inline-block;
		vertical-align: middle;
		text-align: center;
		border: 1px solid black;
		background: #333;
		margin-left: 10px;
		margin-bottom: 10px;
		padding: 5px;
	}
	.cache_sample_image_cont img {
		max-width: 100px;
		max-height: 80px;
	}
</style>


<?php if ($background_settings['theme_has_dynamic_background'] === true): ?>
	<div id="change_background_dialog">
		<form id="choose_background_form" method="POST" action="/admin/theme_centers/set_use_theme_background/">
			<input type="radio" name="change_background_choice" value="theme_background" <?php if ($background_settings['use_theme_background'] == false): ?>checked="checked"<?php endif; ?> /><span class="cache_sample_image_cont"><img src="<?php echo $background_settings['default_bg_web_path']; ?><?php echo $background_settings['image_cache_ending']; ?>" /></span>
			<?php if ($this->Theme->has_uploaded_custom_background()): ?>
				<input type="radio" name="change_background_choice" value="custom_background" <?php if ($background_settings['use_theme_background'] == true): ?>checked="checked"<?php endif; ?> /><span class="cache_sample_image_cont"><img src="<?php echo UPLOADED_BACKGROUND_WEB_PATH; ?><?php echo $background_settings['image_cache_ending']; ?>" /></span>
			<?php endif; ?>
		</form>
		<form id="upload_background_file_form" method="POST" action="/admin/theme_centers/upload_background_file/" enctype="multipart/form-data">
			<input style="display: none;" id="hidden_background_file_chooser" name="hidden_background_file_chooser" type="file" accept="image/jpeg" />
		</form>
	</div>
<?php endif; ?>




<div id="configure_theme_background" class="content_only_page">
	<?php if ($background_settings['theme_has_dynamic_background'] === true): ?>
		<div class="custom_ui" data-step="2" data-intro="Ok, wasn't that fun?">
			<div id="upload_background_button" class="add_button" type="submit"><div class="content"><?php __('Upload Background Image'); ?></div><div class="right_arrow_lines"><div></div></div></div>
		</div>
		<?php // DREW TODO - make the below div have the default bg color of the theme ?>
		<div class="page_content_header" data-step="1" data-intro="Hey this is another thing">
			<p>
				Click and drag photo to set position.<br/>
				Click and drag lower right corner to set size
			</p>
		</div>
		<div id="theme_background_palette_container" data-step="3" data-intro="Hey this is another thing" data-position="left">
			<div class="fade_background_top"></div>
			<div class="bg_effects_controls" style="margin-bottom: 40px;">
				<div id="bg_brightness" class="slider_container">
					<?php 
						$sign = '';
						$start_brightness = round(($background_settings['current_brightness']/255) * 100);
						if ($background_settings['current_brightness'] > 0 || $start_brightness > 0) {
							$sign = '+';
						}
						$start_brightness_display = ($background_settings['current_brightness'] == 0 || $start_brightness == 0) ? __('Default', true) : $sign.$start_brightness . "%"; 
					?>
					<div class="slider_label"><label>Brightness</label> (<span><?php echo $start_brightness_display; ?></span>)</div>
				</div>

				<div id="bg_contrast" class="slider_container">
					<?php 
						$sign = '';
						if ($background_settings['current_contrast'] > 0) {
							$sign = '+';
						}
						$start_contrast = ($background_settings['current_contrast'] == 0) ? __('Default', true) : $sign.$background_settings['current_contrast'] . "%"; 
					?>
					<div class="slider_label"><label>Contrast</label> (<span><?php echo $start_contrast; ?></span>)</div>

				</div>

				<div id="bg_desaturation" class="slider_container">
					<?php 
						$start_desaturation = ($background_settings['current_desaturation'] == 100) ? __('Default', true) : $background_settings['current_desaturation'] . "%"; 
					?>
					<div class="slider_label"><label>Saturation</label> (<span><?php echo $start_desaturation; ?></span>)</div>
				</div>

				<div id="bg_inverted_container" class="slider_container custom_ui_radio">
					<div class="slider_label"><label>Flip Image Horizontally:</label></div>
					<input type="checkbox" id="bg_inverted" <?php if ($background_settings['current_inverted'] == 1): ?>checked="checked"<?php endif; ?> /><label for="bg_inverted"><?php __('Inverted'); ?></label>
				</div>

				<?php $custom_transparency_settings = $theme_config['admin_config']['theme_background_config']['overlay_image']['custom_overlay_transparency_fade']; ?>
				<?php if (!empty($custom_transparency_settings)): ?>
					<div id="custom_overlay_transparency_container" data-step="4" data-intro="Hey this is another thing">
						<?php foreach ($custom_transparency_settings as $custom_overlay_section_name => $custom_overlay_section): ?>
							<?php 
								$overlay_value = !empty($background_settings['custom_overlay_transparency_settings'][$custom_overlay_section_name]) ? $background_settings['custom_overlay_transparency_settings'][$custom_overlay_section_name] : 4; 
								$calculated_overlay_value = round(((-25/3) * $overlay_value) + (400/3));
								$overlay_value_display = ($calculated_overlay_value == 100) ? __('Default', true) : $calculated_overlay_value . "%";
							?>
							<div id="bg_overlaysetting_<?php echo $custom_overlay_section_name; ?>" data-custom-overlay-key="<?php echo $custom_overlay_section_name; ?>" data-custom-overlay-value="<?php echo $calculated_overlay_value; ?>" class="slider_container">
								<div class="slider_label"><label><?php echo $custom_overlay_section['label']; ?> Opacity</label> (<span><?php echo $overlay_value_display; ?></span>)</div>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
			
			
			<div id="theme_background_palette" style="width: <?php echo $background_settings['max_palette_width']; ?>px; height: <?php echo $background_settings['max_palette_height']; ?>px;">
				<?php
					//list($start_left, $start_top, $start_width, $start_height) = $this->Theme->get_theme_dynamic_background_starting_position();
				?>
				<img class="theme_background_image" start-src="<?php echo $background_settings['bg_edit_path']; ?>" src="<?php echo $background_settings['bg_edit_path']; ?><?php echo $background_settings['image_cache_ending']; ?>" style="display: inline-block; position: absolute; left: <?php echo $background_settings['start_left']; ?>px; top: <?php echo $background_settings['start_top']; ?>px; width: <?php echo $background_settings['start_width']; ?>px; height: <?php echo $background_settings['start_height']; ?>px;" />
				<img class="theme_overlay_image" src="<?php echo $background_settings['overlay_web_path']; ?><?php echo $background_settings['image_cache_ending']; ?>" style="display: inline-block; position: absolute; left: <?php echo $background_settings['palette_start_left']; ?>px; top: <?php echo $background_settings['palette_start_top']; ?>px; width: <?php echo $background_settings['palette_background_width']; ?>px; height: <?php echo $background_settings['palette_background_height']; ?>px;" />
				<div class="theme_background_image_cont" style="left: <?php echo $background_settings['start_left']; ?>px; top: <?php echo $background_settings['start_top']; ?>px; width: <?php echo $background_settings['start_width']; ?>px; height: <?php echo $background_settings['start_height']; ?>px;"></div>
			</div>
			<div id="save_custom_background_button" class="save_button"><div class="content"><?php echo __('Save', true); ?></div></div>
		</div>
	
		<br /><br /><br /><br /><br /><br /><br /><br />
		
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



<?php ob_start(); ?>
<ol>
	<li>This page is fairly buggy - shouldn't take too long to fix, but you may want to call me talk about this page - so you know how it works</li>
	<li>Things to remember
		<ol>
			<li>You should be on andrewmorrill theme to see this page as its the only theme of this type</li>
			<li>This page has no flash message, but needs a ajax save thing like some of the other pages (currently this is a busy cursor on save)</li>
			<li>Not all themes have this option - this is only for themes that use a dynamic background creator (like my theme) - for other themes we need a design to just say what it is and that its not available for the theme</li>
		</ol>
	</li>
</ol>
<?php
$html = ob_get_contents();
ob_end_clean();
	echo $this->Element('admin/richard_notes', array(
	'html' => $html
)); ?>