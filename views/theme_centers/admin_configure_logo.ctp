<div id="configure_logo_cont" style="padding: 20px; margin: 20px; margin-top: 0px; margin-left: 0px;" class="content_only_page"><?php // DREW TODO move the css to content_only_page  for this page and admin_configure_background.ctp ?>
	<?php // class="content-background" ?>
	<?php // echo $this->Element('admin/theme_center/main_menu/list_main_menu_items'); ?>
	
	<?php 
//	echo $this->Element('admin/sub_submenu', array( 
//		'tabs' => array(
//			'Choose Logo' => 'admin/theme_center/configure_logo/logo_upload',
//			'Size and Position Your Logo' => 'admin/theme_center/configure_logo/theme_logo_size',
//		),
//		'width' => 814,
//		'lighter' => true,
//		'right_side_content' => 'admin/theme_center/configure_logo/right_logo_help'
//	)); 
	?>
	
	<script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery('#change_logo_dialog').dialog({
				autoOpen: false,
				title: "<?php __('Choose Logo'); ?>",
				buttons: [
					{
						text: "<?php __('Use Selected'); ?>",
						click: function() {
							jQuery('#choose_logo_form').submit();
							jQuery(this).dialog('close');
						}
					},
					{
						text: "<?php __('Upload New'); ?>",
						click: function() {
							jQuery('#hidden_logo_file_chooser').click();
							jQuery(this).dialog('close');
						}
					}
				],
				minWidth: 500,
				minHeight: 200,
				modal: true,
				resizable: false
			});
			
			jQuery('#hidden_logo_file_chooser').change(function() {
				//console.log (jQuery('#hidden_logo_file_chooser').val());
				jQuery('#upload_logo_file_form').submit();
			});
			
			jQuery('#change_logo_button').click(function() {
				jQuery('#change_logo_dialog').dialog('open');
			});
		});
	</script>
	
	<?php $use_theme_logo = $this->Theme->get_theme_setting('use_theme_logo', true); ?>
	
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
	</style>
	
	<div id="change_logo_dialog">
		<?php $theme_logo_cache_path = $this->ThemeLogo->get_logo_cache_size_path(80, 150); ?>
		<form id="choose_logo_form" method="POST" action="/admin/theme_centers/set_use_theme_logo/">
			<input type="radio" name="change_logo_choice" value="theme_logo" <?php if ($use_theme_logo): ?>checked="checked"<?php endif; ?> /><span class="cache_sample_image_cont"><img src="<?php echo $theme_logo_cache_path; ?>" /></span>
			<?php if ($this->ThemeLogo->has_uploaded_custom_logo()): ?>
				<?php $theme_uploaded_logo_cache_path = $this->ThemeLogo->get_logo_cache_size_path(80, 150, false, false); ?>
				<input type="radio" name="change_logo_choice" value="custom_logo" <?php if (!$use_theme_logo): ?>checked="checked"<?php endif; ?> /><span class="cache_sample_image_cont"><img src="<?php echo $theme_uploaded_logo_cache_path; ?>" /></span>
			<?php endif; ?>
		</form>
		<form id="upload_logo_file_form" method="POST" action="/admin/theme_centers/upload_logo_file/" enctype="multipart/form-data">
			<input style="display: none;" id="hidden_logo_file_chooser" name="hidden_logo_file_chooser" type="file" accept="image/png" />
		</form>
	</div>
	
	<div class="custom_ui" style="margin: 5px; margin-bottom: 15px;">
		<input id="change_logo_button" class="add_button" type="submit" value="<?php __('Choose Different Logo'); ?>" />
	</div>
	<?php echo $this->Element('admin/theme_center/configure_logo/theme_logo_size'); ?>
	
	
	<div class="clear"></div>
</div>

<?php ob_start(); ?>
<ol>This page is used to both upload a custom logo and to resize and place the logo on the theme
	<li>Things to remember
		<ol>
			<li>We can do any design we want "around" the actual spot where the theme and logo are shown (ie for the max width or max height - maybe ruler type style?)</li>
			<li>The "inside" (ie logo and blue icon) can't change in size but can change in color and the width of the borders etc - the code is very complex though - so prob don't want to change is so much)</li>
			<li>We need a design for the choose logo popup</li>
			<li>We probobly want an explanation section for how to use the tool</li>
			<li>This page has no flash message</li>
		</ol>
	</li>
</ol>
<?php
$html = ob_get_contents();
ob_end_clean();
	echo $this->Element('admin/richard_notes', array(
	'html' => $html
)); ?>