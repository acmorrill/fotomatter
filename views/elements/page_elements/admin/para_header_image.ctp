<?php //debug($config); ?>

<div class="para_header_image_cont">
	<input class="defaultText header" type="text" title="Page Element Heading" style="margin-bottom: 10px;" />
	<div class="para_image_cont">
		<div class="image_cont" style="float: left; margin-right: 10px;">
			<img src="<?php echo $this->Photo->get_dummy_error_image_path(50, 50); ?>" />
		</div>
		<div class="paragraph" style="">
			<textarea>This is the paragraph</textarea>
		</div>
	</div>
</div>
