<?php 
	$cache_image_height = 100;
	$cache_image_width = 100;
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
			
			
			// setup the image file upload
			$('.image_upload', page_element_cont).fileupload({
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
				start: function() {
					show_modal('<?php __('Uploading'); ?>', 0, undefined, false);
				},
				stop: function() {
					remove_modal();
				},
				done: function(e, data) {
					var result = jQuery.parseJSON(data.result);
					if (result.code == 1) {
						jQuery('.image_cont .para_header_image_photo_id', page_element_cont).val(result.new_photo_id);
						jQuery('.image_cont img', page_element_cont).attr('src', result.new_photo_path);
						
						save_page_elements();
					} else {
						major_error_recover('The image failed to upload in done');
					}
				},
				fail: function(e, data) {
					major_error_recover('The image failed to upload in fail');
				}
			});
			
			
			/////////////////////////////////////////
			// testing code
			jQuery('.generic_sort_and_filters .tiny_mce_test', page_element_cont).click(function() {
				var textarea_val = jQuery(this).closest('.page_element_cont').find('.tinymce textarea').val();
				alert(textarea_val);
			});
		}
	}));
</script>

<form id="<?php echo $uuid; ?>">
	<div class="para_header_image_cont">
		<div class="page_element_top_section rounded-corners-small no-bottom-rounded">
			<?php $para_image_header_text =  isset($config['para_image_header_text']) ? $config['para_image_header_text'] : '' ; ?>
			<input value="<?php echo $para_image_header_text; ?>" name="para_image_header_text" placeholder="Page Element Heading" class="header" type="text" style="margin-bottom: 15px; width: 260px;" />
			<div class="para_image_cont">
				<?php $para_image_header_image_pos =  isset($config['para_image_header_image_pos']) ? $config['para_image_header_image_pos'] : 'left' ; ?>
				<div class="image_cont <?php echo $para_image_header_image_pos; ?>">
					<?php $para_header_image_photo_id =  isset($config['para_header_image_photo_id']) ? $config['para_header_image_photo_id'] : -1 ; ?>
					<input class="para_header_image_photo_id" name="para_header_image_photo_id" type="hidden" value="<?php echo $para_header_image_photo_id; ?>" />
					<div class="image_upload" style="width: <?php echo $cache_image_width - 4; ?>px; overflow: hidden; padding-left: 4px;">
						<input type="file" accept="image/jpeg" />
					</div>
					<?php if ($para_header_image_photo_id != -1): ?>
						<img src="<?php echo $this->Photo->get_photo_path($para_header_image_photo_id, $cache_image_height, $cache_image_width); ?>" />
					<?php else: ?>
						<img src="<?php echo $this->Photo->get_dummy_error_image_path($cache_image_height, $cache_image_width); ?>" />
					<?php endif; ?>
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
		</div>
		<div class="generic_sort_and_filters page_element_bottom_section" style="border: 0px; border-top: 1px solid #303030; height: auto; position: relative; padding: 15px;">
			<?php if (Configure::read('debug') > 1): ?>
				<div class="tiny_mce_test" style="position: absolute; top: 0px; right: 0px; background: black; color: white; cursor: pointer; padding: 5px;">Tiny Mce Value</div>
			<?php endif; ?>


			<div class="custom_ui_radio">
				<div class="para_image_header_image_pos">
					<?php $uuid = substr(base64_encode(String::uuid()), 0, 25); ?>
					<input value="left" type="radio" id="<?php echo $uuid; ?>" name="para_image_header_image_pos" <?php if ($para_image_header_image_pos == 'left'): ?>checked="checked"<?php endif; ?> /><label for="<?php echo $uuid; ?>"><?php __('Image On Left'); ?></label>
					<?php $uuid = substr(base64_encode(String::uuid()), 0, 25); ?>
					<input value="right" type="radio" id="<?php echo $uuid; ?>" name="para_image_header_image_pos" <?php if ($para_image_header_image_pos == 'right'): ?>checked="checked"<?php endif; ?> /><label for="<?php echo $uuid; ?>"><?php __('Image On Right'); ?></label>
				</div>
			</div>
				
			<div class="custom_ui_radio">
				<div class="para_image_header_image_size">
					<?php $uuid = substr(base64_encode(String::uuid()), 0, 25); ?>
					<input value="small" type="radio" id="<?php echo $uuid; ?>" name="para_image_header_image_size" <?php if ($para_image_header_image_size == 'small'): ?>checked="checked"<?php endif; ?> /><label for="<?php echo $uuid; ?>"><?php __('Small Image'); ?></label>
					<?php $uuid = substr(base64_encode(String::uuid()), 0, 25); ?>
					<input value="medium" type="radio" id="<?php echo $uuid; ?>" name="para_image_header_image_size" <?php if ($para_image_header_image_size == 'medium'): ?>checked="checked"<?php endif; ?> /><label for="<?php echo $uuid; ?>"><?php __('Medium Image'); ?></label>
					<?php $uuid = substr(base64_encode(String::uuid()), 0, 25); ?>
					<input value="large" type="radio" id="<?php echo $uuid; ?>" name="para_image_header_image_size" <?php if ($para_image_header_image_size == 'large'): ?>checked="checked"<?php endif; ?> /><label for="<?php echo $uuid; ?>"><?php __('Large Image'); ?></label>
				</div>
			</div>

			<div style="clear: both;"></div>
		</div>
	</div>
</form>
