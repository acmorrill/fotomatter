<h1><?php echo __('Edit Page', true); ?>
	<div id="help_tour_button" class="custom_ui"><?php echo $this->Element('/admin/get_help_button'); ?></div>
</h1>

<div class="page_content_header generic_basic_settings">
	<p data-step="1" data-intro="<?php echo __ ('Adjust the settings for the page below by add a page title. If all the features are unlock on your theme you may attach an external page, or create the contact information.',true); ?>" data-position="bottom"><?php echo __('modify settings below', true); ?></p>
	<div style="clear: both;"></div>
</div>
<div class="generic_palette_container">
	<div class="fade_background_top"></div>
	<?php echo $this->Form->create('SitePage'); ?>
		<div class="generic_inner_container">
			<div class="generic_dark_cont fotomatter_form">
				<?php 
					echo $this->Form->input('title');
					if (isset($this->data['SitePage']['type']) && $this->data['SitePage']['type'] == 'external') {
						echo $this->Form->input('external_link');
					}
					if (isset($this->data['SitePage']['type']) && $this->data['SitePage']['type'] == 'contact_us') {
						echo $this->Form->input('contact_header');
						echo $this->Form->input('contact_message');
					}
				?>
			</div>
		</div>
		<div class="submit save_button javascript_submit"data-step="2" data-intro="<?php echo __ ('Save your changes.',true); ?>" data-position="top">
			<div class="content"><?php echo __('Save', true); ?></div>
		</div>
	<?php echo $this->Form->end(); ?>
</div>


<?php ob_start(); ?>
<ol>
	<li>This page is where you can edit a page title (and potentially other settings later) :)</li>
	<li>Things to remember
		<ol>
			<li>This page needs a flash message</li>
		</ol>
	</li>
</ol>
<?php
$html = ob_get_contents();
ob_end_clean();
	echo $this->Element('admin/richard_notes', array(
	'html' => $html
)); ?>