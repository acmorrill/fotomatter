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
			<div class="generic_dark_cont fotomatter_form" data-step="1" data-intro="<?php echo __('Edit the name and description of your gallery. Note: The descriptions are currently only visible here in the admin. There will be an option on future themes to display the descriptions on your site.', true); ?>" data-position="top">
				<div style="display:none;">
					<input type="hidden" name="_method" value="PUT">
				</div>
				<?php echo $this->Form->input('display_name'); ?>
				<?php echo $this->Form->input('description'); ?>
			</div>
		</div>
		<div class="submit save_button javascript_submit" data-step="2" data-intro="<?php echo __("Don’t forget to save your changes. Select &ldquo;Manage Gallery Photos&rdquo; (on the left) to continue working with this gallery.", true); ?>" data-position="top">
			<div class="content"><?php echo __('Save', true); ?></div>
		</div>
	<?php echo $this->Form->end(); ?>
</div>



