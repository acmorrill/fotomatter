<?php 
	$image_element_cache_image_width = 373;
	$image_element_cache_image_height = 400;
?>

<script type="text/javascript" src="/js/jquery_price_format/jquery.price_format.min.js"></script>


<div id="image_edit_container" class="no_td_as_block">
	<h1><?php echo __('Photo Details', true); ?>
		<?php echo $this->Element('/admin/get_help_button'); ?>
	</h1>
	<p><?php echo __('Add titles, subtitles, descriptions, and other information to appear next to your images on your site. Leave blank and nothing besides the photo will be displayed.', true); ?></p>
	<div class="actual_image_container" data-step="7" data-intro="<?php echo __('You can upload an updated version of the image here.', true); ?>" data-position="right">
		<div class="actual_image_inner_container">
			<?php $img_path = $this->Photo->get_photo_path($this->data['Photo']['id'], $image_element_cache_image_height, $image_element_cache_image_width, .4, true, false); ?>
			<img src="<?php echo $img_path['url']; ?>" <?php echo $img_path['tag_attributes']; ?> alt="" />
		</div>
		<script type="text/javascript">
			jQuery(document).ready(function() {
				jQuery('#photo_details_upload_progress .progress').progressbar({
					value: false
				});
				
				jQuery('#photo_details_image_upload_form').fileupload({
					disableImageResize: /Android(?!.*Chrome)|Opera/.test(window.navigator && navigator.userAgent),
					imageMaxWidth: <?php echo FREE_MAX_RES; ?>,
					imageMaxHeight: <?php echo FREE_MAX_RES; ?>,
					dataType: 'json',
					sequentialUploads: true,
					autoUpload: true,
					url: '/admin/photos/process_mass_photos/',
					formData: function() {
						return [
							{
								name: 'photo_id',
								value: <?php echo $this->data['Photo']['id']; ?>
							},
							{
								name: 'height',
								value: <?php echo $image_element_cache_image_height; ?>
							},
							{
								name: 'width',
								value: <?php echo $image_element_cache_image_width; ?>
							}
						];
					},
					start: function() {
						jQuery('#photo_details_upload_progress').show();
					},
					stop: function() {
					},
					done: function(e, data) {
						var result = data.result.files['0'];
						if (result.code == 1) {
							jQuery('#image_edit_container .actual_image_inner_container img').attr('src', result.new_photo_path);
							jQuery('#image_edit_container .actual_image_inner_container img').attr('width', '');
							jQuery('#image_edit_container .actual_image_inner_container img').attr('height', '');
						} else {
							if (typeof result.error == 'string') {
								jQuery.foto('alert', result.error);
							}
							major_error_recover('The image failed to upload in done of image element');
						}
					},
					fail: function(e, data) {
						$.foto('alert', '<?php echo __("Photo upload failed &mdash; you may be over the limit.", true); ?>');
						major_error_recover('The image failed to upload in fail');
					},
					always: function(e, data) {
						jQuery('#photo_details_upload_progress').hide();
					}
				});
				
				
				
				jQuery('#upload_replacement_photo_button').click(function() {
					jQuery(this).closest('.actual_image_container').find('input').click();
				});
				
				
				jQuery('.photo_details_save_button').click(function() {
					jQuery('#image_edit_container .submit input').click();
				});
			});
		</script>
		<div id="photo_details_upload_progress" style="display: none;">
			<!-- The global progress bar -->
			<div class="progress" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
		</div>
		<form id="photo_details_image_upload_form">
			<input type="file" accept="image/jpeg" />
			<div id="upload_replacement_photo_button" class="custom_ui">
				<div class="add_button">
					<div class="content"><?php echo __('Replace Photo', true); ?></div>
					<div class="plus_icon_lines icon-_button-01"><div class="one"></div><div class="two"></div></div>
				</div>
			</div>
		</form>
	</div>
	
	<div style="clear: both;"></div>
	<?php
		echo $this->Form->create('Photo', array('enctype' => 'multipart/form-data'));
                echo $this->Form->hidden('override_pricing');
		echo $this->Element('admin/sub_submenu', array(
			'tabs' => array(
				'Image Details' => 'admin/photo/photo_details_image_edit',
				'Pricing Override' => 'admin/photo/photo_details_ecommerce',
			),
			'starting_tab' => $starting_tab,
//			'css' => 'margin-top: -26px;',
		));
		echo $this->Form->end('Save');
	?>


	<div class="clear"></div>
	
	
	
</div>

<?php /*
<?php ob_start(); ?>
<ol>
	<li>This page is where edit the settings for an individual photo</li>
	<li><a href="/img/admin_screenshots/edit_photo.jpg" target="_blank">Screenshot</a></li>
	<li>Things to remember
		<ol>
			<li>This page needs a flash message</li>
			<li>The choose available sizes section needs styling
				<ol>
					<li>The locked vs unlocked status needs style (locked the whole row is grayed out - see screenshot)</li>
				</ol>
			</li>
		</ol>
	</li>
</ol>
<?php
$html = ob_get_contents();
ob_end_clean();
	echo $this->Element('admin/richard_notes', array(
	'html' => $html
)); ?>
 * 
 */ ?>