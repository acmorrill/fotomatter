<h1><?php echo __('Edit Gallery', true); ?>
	<div id="help_tour_button" class="custom_ui"><?php echo $this->Element('/admin/get_help_button'); ?></div>
</h1>


<div class="page_content_header generic_basic_settings">
	<div style="<?php if (empty($add_feature_ref_name)): ?>display:none;<?php endif; ?>" class='finish-outer-cont custom_ui right'>
		<div class="add_button highlight bigger finish_account_add" type="submit">
			<div class="content"><?php echo __('Finalize Changes', true); ?></div>
			<div class="right_arrow_lines"><div></div></div>
		</div>
	</div>
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
			<div class="content">Save</div>
		</div>
	<?php echo $this->Form->end(); ?>
</div>



