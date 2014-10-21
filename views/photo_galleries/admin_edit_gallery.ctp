<h1><?php echo __('Edit Gallery', true); ?>
	<div id="help_tour_button" class="custom_ui"><?php echo $this->Element('/admin/get_help_button'); ?></div>
</h1>


<div class="page_content_header generic_basic_settings">
	<p><?php echo __('modify settings below', true); ?></p>
	<div style="clear: both;"></div>
</div>
<div class="generic_palette_container">
	<div class="fade_background_top"></div>
	<?php echo $this->Form->create('PhotoGallery'); ?>
		<div class="generic_inner_container">
			<div class="generic_dark_cont fotomatter_form">
				<div style="display:none;">
					<input type="hidden" name="_method" value="PUT">
				</div>
				<?php echo $this->Form->input('display_name'); ?>
				<?php echo $this->Form->input('description'); ?>
			</div>
		</div>
		<div class="submit save_button javascript_submit">
			<div class="content"><?php echo __('Save', true); ?></div>
		</div>
	<?php echo $this->Form->end(); ?>
</div>



