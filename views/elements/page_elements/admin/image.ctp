<?php 
	$image_element_cache_image_height = 300;
	$image_element_cache_image_width = 300;
?>

<script type="text/javascript">
	register_page_element_callbacks(new element_callbacks({
		uuid: '<?php echo $uuid; ?>',
		init: function(page_element_cont) {
			// setup the image file upload
			$('.image_element_image_upload', page_element_cont).fileupload({
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
				start: function() {
					show_modal('<?php __('Uploading'); ?>', 0, undefined, false);
				},
				stop: function() {
					remove_modal();
				},
				done: function(e, data) {
					var result = jQuery.parseJSON(data.result);
					console.log (result);
					if (result.code == 1) {
						jQuery('.image_element_image_cont .image_element_image_photo_id', page_element_cont).val(result.new_photo_id);
						jQuery('.image_element_image_cont img.image_element_actual_image', page_element_cont).attr('src', result.new_photo_path);
						
						save_page_elements();
					} else {
						major_error_recover('The image failed to upload in done of image element');
					}
				},
				fail: function(e, data) {
					major_error_recover('The image failed to upload in fail');
				}
			});
		}
	}));
</script>

<form id="<?php echo $uuid; ?>">
	<div class="image_element_image_cont">
		<div class="page_element_top_section rounded-corners-small no-bottom-rounded">
			<?php $image_element_image_photo_id =  isset($config['image_element_image_photo_id']) ? $config['image_element_image_photo_id'] : -1 ; ?>
			<input class="image_element_image_photo_id" name="image_element_image_photo_id" type="hidden" value="<?php echo $image_element_image_photo_id; ?>" />
			<?php if ($image_element_image_photo_id != -1): ?>
				<img class="image_element_actual_image" src="<?php echo $this->Photo->get_photo_path($image_element_image_photo_id, $image_element_cache_image_height, $image_element_cache_image_width); ?>" />
			<?php else: ?>
				<img class="image_element_actual_image" src="<?php echo $this->Photo->get_dummy_error_image_path($image_element_cache_image_height, $image_element_cache_image_width); ?>" />
			<?php endif; ?>
			<br/><div class="image_element_image_upload image_upload" style="width: <?php echo $image_element_cache_image_width - 4; ?>px; overflow: hidden; padding-left: 158px; margin-top: 8px;">
				<input type="file" accept="image/jpeg" />
			</div>
			<?php /*<div class="image_size_text">
				<?php $image_element_image_size =  isset($config['image_element_image_size']) ? $config['image_element_image_size'] : 'medium' ; ?>
				(<?php echo ucwords($image_element_image_size); ?>)
			</div> */ ?>
		</div>
		<div class="generic_sort_and_filters page_element_bottom_section" style="border: 0px; border-top: 1px solid #303030; height: auto; position: relative; padding: 15px;">
			<div style="clear: both;"></div>
		</div>
	</div>
</form>
