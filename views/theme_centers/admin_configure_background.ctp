<?php $background_settings = $this->Theme->get_theme_background_config_values($theme_config); ?>

<script type="text/javascript">
	var brightness_cont;
	var contrast_cont;
	var desaturation_cont;
	var inverted_cont;
	function reload_size_change_background() {
		var current_brightness = brightness_cont.val();
		var current_contrast = contrast_cont.val();
		var current_desaturation = desaturation_cont.val();
		var current_inverted = inverted_cont.prop('checked') ? 1 : 0;
//		console.log ("============================");
//		console.log (current_brightness);
//		console.log (current_contrast);
//		console.log (current_desaturation);
//		console.log (current_inverted);
//		console.log ("============================");
		
		
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
				'current_inverted': current_inverted
			},
			success: function(the_data) {
//				console.log ("came into success");
				//console.log (data);
			},
			complete: function() {
//				console.log ("complete");
				d = new Date();
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
		inverted_cont = jQuery('#bg_inverted');
	
		jQuery('#theme_background_palette .theme_background_image_cont').resizable({
			aspectRatio: true,
			//containment: "parent",
			handles: 'se', // DREW TODO - maybe add more but need to test all of them
			stop: function() {
				reload_size_change_background();
			},
			resize: function(event, ui) {
				var size = ui.size;
				
				jQuery('#theme_background_palette .theme_background_image').css('width', size.width).css('height', size.height);
			}
		}).draggable({
			//containment: "#theme_background_palette",
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
		
		
		
		jQuery('#change_background_dialog').dialog({
			autoOpen: false,
			title: "<?php __('Choose Background'); ?>",
			buttons: [
				{
					text: "<?php __('Use Selected'); ?>",
					click: function() {
						jQuery('#choose_background_form').submit();
						jQuery(this).dialog('close');
					}
				},
				{
					text: "<?php __('Upload New'); ?>",
					click: function() {
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
		
		
		jQuery('#bg_brightness, #bg_contrast, #bg_desaturation, #bg_inverted').change(function() {
			console.log ("cam eghere sucka");
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
		<div class="custom_ui">
			<div id="upload_background_button" class="add_button" type="submit"><div class="content"><?php __('Upload Background Image'); ?></div><div class="right_arrow_lines"><div></div></div></div>
		</div>
		<div class="bg_effects_controls" style="margin-bottom: 40px;">
			<label>Brightness:</label>
			<select id="bg_brightness">
				<?php for ($i = -255; $i <= 255; $i++): ?>
					<option value="<?php echo $i; ?>" <?php if ($background_settings['current_brightness'] == $i): ?>selected="selected"<?php endif; ?>>
						<?php if ($i < 0): ?>
							<?php echo $i; ?>
						<?php elseif ($i === 0): ?>
							Unchanged
						<?php elseif ($i > 0): ?>
							+<?php echo $i; ?>
						<?php endif; ?>
					</option>
				<?php endfor; ?>
			</select><br />
			<label>Contrast:</label>
			<select id="bg_contrast">
				<?php for ($i = -100; $i <= 100; $i++): ?>
					<option value="<?php echo $i; ?>" <?php if ($background_settings['current_contrast'] == $i): ?>selected="selected"<?php endif; ?>>
						<?php if ($i < 0): ?>
							<?php echo $i; ?>
						<?php elseif ($i === 0): ?>
							Unchanged
						<?php elseif ($i > 0): ?>
							+<?php echo $i; ?>
						<?php endif; ?>
					</option>
				<?php endfor; ?>
			</select><br />
			<label>Desaturation:</label>
			<select id="bg_desaturation">
				<?php for ($i = 0; $i <= 100; $i++): ?>
					<option value="<?php echo $i; ?>" <?php if ($background_settings['current_desaturation'] == $i): ?>selected="selected"<?php endif; ?>>
						<?php if ($i < 0): ?>
							<?php echo $i; ?>
						<?php elseif ($i === 0): ?>
							Unchanged
						<?php elseif ($i > 0): ?>
							+<?php echo $i; ?>
						<?php endif; ?>
					</option>
				<?php endfor; ?>
			</select><br />
			<label>Inverted:</label>
			<input type="checkbox" id="bg_inverted" <?php if ($background_settings['current_inverted'] == 1): ?>checked="checked"<?php endif; ?> /><br />
		</div>
		<?php // DREW TODO - make the below div have the default bg color of the theme ?>
		<div id="theme_background_palette" style="overflow: hidden; background-color: white; position: relative; outline: 1px solid green; width: <?php echo $background_settings['max_palette_width']; ?>px; height: <?php echo $background_settings['max_palette_height']; ?>px;">
			<?php
				//list($start_left, $start_top, $start_width, $start_height) = $this->Theme->get_theme_dynamic_background_starting_position();
			?>
			<img class="theme_background_image" start-src="<?php echo $background_settings['bg_edit_path']; ?>" src="<?php echo $background_settings['bg_edit_path']; ?><?php echo $background_settings['image_cache_ending']; ?>" style="display: inline-block; position: absolute; left: <?php echo $background_settings['start_left']; ?>px; top: <?php echo $background_settings['start_top']; ?>px; width: <?php echo $background_settings['start_width']; ?>px; height: <?php echo $background_settings['start_height']; ?>px;" />
			<img class="theme_overlay_image" src="<?php echo $background_settings['overlay_web_path']; ?><?php echo $background_settings['image_cache_ending']; ?>" style="display: inline-block; position: absolute; left: <?php echo $background_settings['palette_start_left']; ?>px; top: <?php echo $background_settings['palette_start_top']; ?>px; width: <?php echo $background_settings['palette_background_width']; ?>px; height: <?php echo $background_settings['palette_background_height']; ?>px;" />
			<div class="theme_background_image_cont" style="cursor: move; outline: 1px solid blue; display: inline-block; position: absolute; left: <?php echo $background_settings['start_left']; ?>px; top: <?php echo $background_settings['start_top']; ?>px; width: <?php echo $background_settings['start_width']; ?>px; height: <?php echo $background_settings['start_height']; ?>px;"></div>
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