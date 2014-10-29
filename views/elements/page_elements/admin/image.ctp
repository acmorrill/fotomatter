<?php 
	$image_element_cache_image_height = 300;
	$image_element_cache_image_width = 300;
	
	$total_photos = $this->Photo->count_total_photos(true);
	$max_photo_id = $this->Photo->get_last_photo_id_based_on_limit();
	$photos_left_to_add = LIMIT_MAX_FREE_PHOTOS - $total_photos;
	$curr_limit = LIMIT_MAX_FREE_PHOTOS; 
?>

<script type="text/javascript">
	register_page_element_callbacks(new element_callbacks({
		uuid: '<?php echo $uuid; ?>',
		init: function(page_element_cont) {
			// DREW TODO - test the below limit code
			<?php if (empty($current_on_off_features['unlimited_photos']) && $photos_left_to_add <= 0): ?>
				$('.image_element_image_upload', page_element_cont).click(function() {
					$.foto('alert', '<?php echo __("You are currently using $total_photos of the $curr_limit photos available for free &mdash; to upload more photos or modify photos in galleries you can also delete existing photos over the limit of $curr_limit.", true); ?>');
				});
			<?php else: ?>
				// setup the image file upload
				jQuery('#<?php echo $uuid; ?> .progress').progressbar({
					value: false
				});
				jQuery('#<?php echo $uuid; ?> .upload_replacement_photo_button').click(function() {
					jQuery(this).closest('.actual_image_container').find('input').click();
				});
				jQuery('#<?php echo $uuid; ?>').fileupload({
					<?php if (empty($current_on_off_features['unlimited_photos'])): ?>
						maxNumberOfFiles: <?php echo $photos_left_to_add; ?>,
					<?php endif; ?>
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
								name: 'height',
								value: <?php echo $image_element_cache_image_height; ?>
							},
							{
								name: 'width',
								value: <?php echo $image_element_cache_image_width; ?>
							}
						];
					},
					progressall: function (e, data) {
						var progress = parseInt((data.loaded * .6) / data.total * 100, 10); // max is 80 percent until call comes back
						jQuery('#<?php echo $uuid; ?> .progress').progressbar({value: progress });
					},
					start: function() {
						show_universal_save();
						jQuery('#<?php echo $uuid; ?> .photo_details_upload_progress').show();
					},
					stop: function() {
					},
					done: function(e, data) {
						var result = data.result.files['0'];
						if (result.code == 1) {
							jQuery('#<?php echo $uuid; ?> .progress').progressbar({value: 100 });
							jQuery('.image_element_image_cont .image_element_image_photo_id', page_element_cont).val(result.new_photo_id);
							jQuery('.image_element_image_cont img.image_element_actual_image', page_element_cont).removeAttr('width height');
							jQuery('.image_element_image_cont img.image_element_actual_image', page_element_cont).attr('src', result.new_photo_path);

							save_page_elements(function() {
								jQuery('#<?php echo $uuid; ?> .photo_details_upload_progress').hide();
							});
						} else {
							major_error_recover('The image failed to upload in done of image element');
						}
					},
					fail: function(e, data) {
						major_error_recover('The image failed to upload in fail of page configure image upload');
						hide_universal_save();
					},
					always: function(e, data) {
					}
				});
			<?php endif; ?>
		}
	}));
</script>




<form id="<?php echo $uuid; ?>">
	<div class="image_element_image_cont">
		<div class="page_element_top_section rounded-corners-small no-bottom-rounded">
			<?php $image_element_image_photo_id =  isset($config['image_element_image_photo_id']) ? $config['image_element_image_photo_id'] : -1 ; ?>
			<input class="image_element_image_photo_id" name="image_element_image_photo_id" type="hidden" value="<?php echo $image_element_image_photo_id; ?>" />
			<div class="actual_image_container">
				<div class="actual_image_inner_container">
					<?php 
						$img_path_data = null;
						if ($image_element_image_photo_id != -1) {
							$img_path_data = $this->Photo->get_photo_path($image_element_image_photo_id, $image_element_cache_image_height, $image_element_cache_image_width, null, true);
						} else {
							$img_path_data = $this->Photo->get_dummy_error_image_path($image_element_cache_image_height, $image_element_cache_image_width, false, true);
						}
					?>
					<img class="image_element_actual_image" src="<?php echo $img_path_data['url']; ?>" <?php echo $img_path_data['tag_attributes']; ?> />
				</div>
				<div class="image_element_image_upload image_upload">
					<div class="photo_details_upload_progress" style="display: none;" >
						<!-- The global progress bar -->
						<div class="progress" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
					</div>
				</div>
				<div class="photo_details_image_upload_form">
					<input type="file" accept="image/jpeg">
					<div class="upload_replacement_photo_button" class="custom_ui">
						<div class="add_button">
							<div class="content"><?php echo __('Upload Photo', true); ?></div>
							<div class="plus_icon_lines icon-_button-01"><div class="one"></div><div class="two"></div></div>
						</div>
					</div>
				</div>
			</div>
			<?php /*<div class="image_size_text">
				<?php $image_element_image_size =  isset($config['image_element_image_size']) ? $config['image_element_image_size'] : 'medium' ; ?>
				(<?php echo ucwords($image_element_image_size); ?>)
			</div> */ ?>
		</div>
		<div class="generic_sort_and_filters page_element_bottom_section">
			<div style="clear: both;"></div>
		</div>
	</div>
</form>
