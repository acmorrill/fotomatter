<div class="para_header_image_cont">
	<div class="page_element_top_section rounded-corners-small no-bottom-rounded">
		<input class="defaultText header" type="text" title="Page Element Heading" style="margin-bottom: 10px; width: 260px;" />
		<div class="para_image_cont">
			<div class="image_cont left">
				<img src="<?php echo $this->Photo->get_dummy_error_image_path(100, 100); ?>" />
			</div>
			<div class="paragraph tinymce">
				<textarea class="" title="The default paragraph text" style="width: 75%; height: 124px;"></textarea>
			</div>
		</div>
	</div>
	<div class="generic_sort_and_filters" style="border: 0px; border-top: 1px solid #303030; height: auto; position: relative; padding: 15px;">
		<?php if (Configure::read('debug') > 1): ?>
			<div class="tiny_mce_test" style="position: absolute; top: 0px; right: 0px; background: black; color: white; cursor: pointer; padding: 5px;">Tiny Mce Value</div>
		<?php endif; ?>


		<div class="custom_ui_radio">
			<div class="para_image_header_image_pos">
				<?php $uuid = substr(base64_encode(String::uuid()), 0, 25); ?>
				<input type="radio" id="<?php echo $uuid; ?>" name="para_image_header_image_pos" checked="checked" /><label for="<?php echo $uuid; ?>"><?php __('Image On Left'); ?></label>
				<?php $uuid = substr(base64_encode(String::uuid()), 0, 25); ?>
				<input type="radio" id="<?php echo $uuid; ?>" name="para_image_header_image_pos" /><label for="<?php echo $uuid; ?>"><?php __('Image On Right'); ?></label>
			</div>
		</div>

		<div style="clear: both;"></div>
	</div>
</div>
