  <?php 
	// DREW TODO - get rid of rounding errors in the javascript logic

	$background_config = $theme_config['admin_config']['theme_background_config'];
	$theme_has_dynamic_background = $background_config['theme_has_dynamic_background'];
	
	if ($theme_has_dynamic_background === true) {
		///////////////////////////////////////////////////////////////////////
		// get the paths 
		// overlay: the paths to the overlay png
		// default: the starting background image to use if user has not uploaded one
		// uploaded: the path to the user uploaded background image
		// merged: the version that is used on the frontend
		$overlay_web_path = $background_config['overlay_image']['web_path'];
		$overlay_abs_path = $background_config['overlay_image']['absolute_path'];
		$default_bg_web_path = $background_config['default_bg_image']['web_path'];
		$default_bg_abs_path = $background_config['default_bg_image']['absolute_path'];
//		$uploaded_bg_abs_path = $this->Theme->get_theme_uploaded_background_abs_path();
//		$uploaded_bg_web_path = $this->Theme->get_theme_uploaded_background_web_path();
		$merged_bg_abs_path = $this->Theme->get_theme_merged_background_abs_path();
		$merged_bg_web_path = $this->Theme->get_theme_merged_background_web_path();


		/////////////////////////////////////////////////////////////////////////////////////////////////////////
		// use_theme_background: means the user has uploaded a custom image for the background
		// populate the current_background starting image
		if ($use_theme_background == true) {
			$current_background_web_path = UPLOADED_BACKGROUND_WEB_PATH;
			$current_background_abs_path = UPLOADED_BACKGROUND_PATH;
		} else {
			$current_background_web_path = $default_bg_web_path;
			$current_background_abs_path = $default_bg_abs_path;
		}

		
		// get sizes for background image (starting image)
		$current_background_size = getimagesize($current_background_abs_path);
		list($orig_background_width, $orig_background_height, $current_background_size_type, $current_background_size_attr) = $current_background_size;

		// get size for starting png pallete image
		$palette_background_size = getimagesize($overlay_abs_path);
		list($orig_palette_background_width, $orig_palette_background_height, $palette_background_size_type, $palette_background_size_attr) = $palette_background_size;


		// set some constants
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
	}
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

		// user has or has not uploaded a custom background image
		var using_custom_background_image = <?php echo ($use_theme_background == true) ? 'true' : 'false'; ?>;
		
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
				'current_background_width': current_background_width,
				'current_background_height': current_background_height,
				'current_background_left': current_background_left,
				'current_background_top': current_background_top,
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
		
		reload_size_change_background();
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

<?php if ($theme_has_dynamic_background === true): ?>
	<div id="change_background_dialog">
		<form id="choose_background_form" method="POST" action="/admin/theme_centers/set_use_theme_background/">
			<input type="radio" name="change_background_choice" value="theme_background" <?php if ($use_theme_background == false): ?>checked="checked"<?php endif; ?> /><span class="cache_sample_image_cont"><img src="<?php echo $default_bg_web_path; ?>" /></span>
			<?php if ($this->Theme->has_uploaded_custom_background()): ?>
				<input type="radio" name="change_background_choice" value="custom_background" <?php if ($use_theme_background == true): ?>checked="checked"<?php endif; ?> /><span class="cache_sample_image_cont"><img src="<?php echo UPLOADED_BACKGROUND_WEB_PATH; ?>" /></span>
			<?php endif; ?>
		</form>
		<form id="upload_background_file_form" method="POST" action="/admin/theme_centers/upload_background_file/" enctype="multipart/form-data">
			<input style="display: none;" id="hidden_background_file_chooser" name="hidden_background_file_chooser" type="file" accept="image/jpeg" />
		</form>
	</div>
<?php endif; ?>



<div id="configure_theme_background" class="content_only_page">
	<?php if ($theme_has_dynamic_background === true): ?>
		<div class="custom_ui">
			<div id="upload_background_button" class="add_button" type="submit"><div class="content"><?php __('Upload Background Image'); ?></div><div class="right_arrow_lines"><div></div></div></div>
		</div>
		<?php // DREW TODO - make the below div have the default bg color of the theme ?>
		<div id="theme_background_palette" style="background-color: white; position: relative; outline: 1px solid green; width: <?php echo $max_palette_width; ?>px; height: <?php echo $max_palette_height; ?>px;">
			<?php
				$start_left = ($max_palette_width/2)-($current_background_width/2);
				$start_top = ($max_palette_height/2)-($current_background_height/2);
				$start_width = $current_background_width;
				$start_height = $current_background_height;
				
				if ($use_theme_background == true) {
					$start_left = $this->ThemeHiddenSetting->getVal('uploaded_admin_current_background_left', $start_left);
					$start_top = $this->ThemeHiddenSetting->getVal('uploaded_admin_current_background_top', $start_top);
					$start_width = $this->ThemeHiddenSetting->getVal('uploaded_admin_current_background_width', $start_width);
					$start_height = $this->ThemeHiddenSetting->getVal('uploaded_admin_current_background_height', $start_height);
				} else {
					$start_left = $this->ThemeHiddenSetting->getVal('default_admin_current_background_left', $start_left);
					$start_top = $this->ThemeHiddenSetting->getVal('default_admin_current_background_top', $start_top);
					$start_width = $this->ThemeHiddenSetting->getVal('default_admin_current_background_width', $start_width);
					$start_height = $this->ThemeHiddenSetting->getVal('default_admin_current_background_height', $start_height);
				}
			?>
			<img class="theme_background_image" src="<?php echo $current_background_web_path; ?>" style="display: inline-block; position: absolute; left: <?php echo $start_left; ?>px; top: <?php echo $start_top; ?>px; width: <?php echo $start_width; ?>px; height: <?php echo $start_height; ?>px;" />
			<img class="theme_overlay_image" src="<?php echo $overlay_web_path; ?>" style="display: inline-block; position: absolute; left: <?php echo $palette_start_left; ?>px; top: <?php echo $palette_start_top; ?>px; width: <?php echo $palette_background_width; ?>px; height: <?php echo $palette_background_height; ?>px;" />
			<div class="theme_background_image_cont" style="cursor: move; outline: 1px solid blue; display: inline-block; position: absolute; left: <?php echo $start_left; ?>px; top: <?php echo $start_top; ?>px; width: <?php echo $start_width; ?>px; height: <?php echo $start_height; ?>px;"></div>
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