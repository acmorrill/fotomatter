<?php 
	$cache_image_height = 100;
	$cache_image_width = 100;
	
	$total_photos = $this->Photo->count_total_photos(true);
	$max_photo_id = $this->Photo->get_last_photo_id_based_on_limit();
	$photos_left_to_add = LIMIT_MAX_FREE_PHOTOS - $total_photos;
	$curr_limit = LIMIT_MAX_FREE_PHOTOS; 
?>

<script type="text/javascript">
	register_page_element_callbacks(new element_callbacks({
		uuid: '<?php echo $uuid; ?>',
		init: function(page_element_cont) {
			jQuery('.para_header_image_cont .para_image_header_image_pos, .para_header_image_cont .para_image_header_image_size', page_element_cont).buttonset();
			
			jQuery('.para_header_image_cont .para_image_header_image_pos', page_element_cont).change(function() {
				var container = jQuery(this).closest('.para_header_image_cont');

				if (container.find('.image_cont').hasClass('left')) {
					container.find('.image_cont').removeClass('left');
					container.find('.image_cont').addClass('right');
				} else {
					container.find('.image_cont').removeClass('right');
					container.find('.image_cont').addClass('left');
				}
			});
			
			
			jQuery('.para_header_image_cont .para_image_header_image_size', page_element_cont).change(function() {
				var container = jQuery(this).closest('.para_header_image_cont');
				var curr_size = jQuery('input[name=para_image_header_image_size]:checked', page_element_cont).val();

				container.find('.image_size_text').text('('+ucwords(curr_size)+')');
			});
	
			// DREW TODO - test the below limiting code
			<?php if (empty($current_on_off_features['unlimited_photos']) && $photos_left_to_add <= 0): ?>
				$('.image_upload', page_element_cont).click(function() {
					$.foto('alert', '<?php echo __("You are currently using $total_photos of the $curr_limit photos available for free &mdash; to upload more photos or modify photos in galleries you can also delete existing photos over the limit of $curr_limit.", true); ?>');
				});
			<?php else: ?>
				// setup the image file upload
				jQuery('#<?php echo $uuid; ?> .progress').progressbar({
					value: false
				});
				jQuery('#<?php echo $uuid; ?> .upload_replacement_photo_button').click(function() {
					jQuery(this).closest('.photo_details_image_upload_form').find('input').click();
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
								value: <?php echo $cache_image_height; ?>
							},
							{
								name: 'width',
								value: <?php echo $cache_image_width; ?>
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
							jQuery('.image_cont .para_header_image_photo_id', page_element_cont).val(result.new_photo_id);
							jQuery('.image_cont img', page_element_cont).removeAttr('width height')
							jQuery('.image_cont img', page_element_cont).attr('src', result.new_photo_path);

							save_page_elements(function() {
								jQuery('#<?php echo $uuid; ?> .photo_details_upload_progress').hide();
							});
						} else {
							major_error_recover('The image failed to upload in done');
						}
					},
					fail: function(e, data) {
						major_error_recover('The image failed to upload in fail');
						hide_universal_save();
					},
					always: function(e, data) {
					}
				});
			<?php endif; ?>
			
			/////////////////////////////////////////
			// testing code
			jQuery('.generic_sort_and_filters .tiny_mce_test', page_element_cont).click(function() {
				var textarea_val = jQuery(this).closest('.page_element_cont').find('.tinymce textarea').val();
			});
		}
	}));
</script>

<form id="<?php echo $uuid; ?>">
	<div class="para_header_image_cont">
		<div class="page_element_top_section rounded-corners-small no-bottom-rounded">
			<div class="para_configure_cont">
				<?php $para_image_header_text =  isset($config['para_image_header_text']) ? $config['para_image_header_text'] : '' ; ?>
				<input value="<?php echo $para_image_header_text; ?>" name="para_image_header_text" placeholder="Page Element Heading" class="header" type="text" style="margin-bottom: 15px; width: 260px;" />
				<div class="para_image_cont">
					<?php $para_image_header_image_pos =  isset($config['para_image_header_image_pos']) ? $config['para_image_header_image_pos'] : 'left' ; ?>
					<div class="image_cont <?php echo $para_image_header_image_pos; ?>">
						<?php $para_header_image_photo_id =  isset($config['para_header_image_photo_id']) ? $config['para_header_image_photo_id'] : -1 ; ?>
						<input class="para_header_image_photo_id" name="para_header_image_photo_id" type="hidden" value="<?php echo $para_header_image_photo_id; ?>" />
						<div class="actual_image_container">
							<div class="image_upload" style="width: <?php echo $cache_image_width - 4; ?>px; overflow: hidden; min-width: 125px;">
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
										<?php /*<div class="plus_icon_lines icon-_button-01"><div class="one"></div><div class="two"></div></div>*/ ?>
									</div>
								</div>
							</div>
							<div class="actual_image_inner_container">
								<?php 
									$img_path_data = null;
									if ($para_header_image_photo_id != -1) {
										$img_path_data = $this->Photo->get_photo_path($para_header_image_photo_id, $cache_image_height, $cache_image_width, null, true);
									} else {
										$img_path_data = $this->Photo->get_dummy_error_image_path($cache_image_height, $cache_image_width, false, true);
									}
								?>
								<img src="<?php echo $img_path_data['url']; ?>" <?php echo $img_path_data['tag_attributes']; ?> />
							</div>
						</div>
						<div class="image_size_text">
							<?php $para_image_header_image_size =  isset($config['para_image_header_image_size']) ? $config['para_image_header_image_size'] : 'medium' ; ?>
							(<?php echo ucwords($para_image_header_image_size); ?>)
						</div>
					</div>
					<div class="paragraph tinymce">
						<?php $para_header_image_paragraph_text =  isset($config['para_image_paragraph_text']) ? $config['para_image_paragraph_text'] : '' ; ?>
						<textarea name="para_image_paragraph_text" class="" style="width: 506px; height: 124px;"><?php echo $para_header_image_paragraph_text; ?></textarea>
					</div>
				</div>
				<div style="clear: both;"></div>
			</div>
		</div>
		<div class="generic_sort_and_filters page_element_bottom_section">
			<?php if (Configure::read('debug') > 1): ?>
				<div class="tiny_mce_test" style="position: absolute; top: 0px; right: 0px; background: black; color: white; cursor: pointer; padding: 5px;">Tiny Mce Value</div>
			<?php endif; ?>

			<table>
				<tbody>
					<tr>
						<td>
							<div class="para_button_cont para_image_header_image_pos">
								<?php $uuid = substr(base64_encode(String::uuid()), 0, 25); ?>
								<input value="left" type="radio" id="<?php echo $uuid; ?>" name="para_image_header_image_pos" <?php if ($para_image_header_image_pos == 'left'): ?>checked="checked"<?php endif; ?> />
								<label class="add_button" for="<?php echo $uuid; ?>"><div class='content'><?php echo __('Image On Left', true); ?></div></label>
								<?php $uuid = substr(base64_encode(String::uuid()), 0, 25); ?>
								<input value="right" type="radio" id="<?php echo $uuid; ?>" name="para_image_header_image_pos" <?php if ($para_image_header_image_pos == 'right'): ?>checked="checked"<?php endif; ?> />
								<label class="add_button" for="<?php echo $uuid; ?>"><div class='content'><?php echo __('Image On Right', true); ?></div></label>
							</div>

							<div class="para_button_cont para_image_header_image_size">
								<?php $uuid = substr(base64_encode(String::uuid()), 0, 25); ?>
								<input value="small" type="radio" id="<?php echo $uuid; ?>" name="para_image_header_image_size" <?php if ($para_image_header_image_size == 'small'): ?>checked="checked"<?php endif; ?> />
								<label class="add_button" for="<?php echo $uuid; ?>"><div class='content'><?php echo __('Small Image', true); ?></div></label>
								<?php $uuid = substr(base64_encode(String::uuid()), 0, 25); ?>
								<input value="medium" type="radio" id="<?php echo $uuid; ?>" name="para_image_header_image_size" <?php if ($para_image_header_image_size == 'medium'): ?>checked="checked"<?php endif; ?> />
								<label class="add_button" for="<?php echo $uuid; ?>"><div class='content'><?php echo __('Medium Image', true); ?></div></label>
								<?php $uuid = substr(base64_encode(String::uuid()), 0, 25); ?>
								<input value="large" type="radio" id="<?php echo $uuid; ?>" name="para_image_header_image_size" <?php if ($para_image_header_image_size == 'large'): ?>checked="checked"<?php endif; ?> />
								<label class="add_button" for="<?php echo $uuid; ?>"><div class='content'><?php echo __('Large Image', true); ?></div></label>
							</div>
						</td>
					</tr>
				</tbody>
			</table>

			<div style="clear: both;"></div>
		</div>
	</div>
</form>
