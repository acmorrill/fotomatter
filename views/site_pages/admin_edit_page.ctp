<h1><?php echo __('Edit Page', true); ?>
	<div id="help_tour_button" class="custom_ui"><?php echo $this->Element('/admin/get_help_button'); ?></div>
</h1>

<div class="page_content_header generic_basic_settings">
	<p><?php echo __('modify settings below', true); ?></p>
	<div style="clear: both;"></div>
</div>
<div class="generic_palette_container">
	<div class="fade_background_top"></div>
	<?php echo $this->Form->create('SitePage'); ?>
		<div class="generic_inner_container">
			<div class="generic_dark_cont fotomatter_form">
				<div class="input text" data-step="1" data-intro="<?php echo __('First, name your page as you would like it to appear in the Main Menu. Ex: About, Workshops, Pricing, etc.', true); ?>" data-position="top">
					<?php 
						echo $this->Form->input('title', array(
							'label' => 'Menu Title',
							'div' => false
						));
					?>
				</div>
				<?php if (isset($this->data['SitePage']['type']) && $this->data['SitePage']['type'] == 'external'): ?>
					<div class="input text" data-step="2" data-intro="<?php echo __('The URL to a page not on your website. Ex: Blog, Youtube Channel, etc. The url should include http:// or https:// and should be a full web address.', true); ?>" data-position="top">
						<?php echo $this->Form->input('external_link', array(
							'div' => false
						)); ?>
					</div>
				<?php endif; ?>
				<?php if (isset($this->data['SitePage']['type']) && $this->data['SitePage']['type'] == 'contact_us'): ?>
					<div class="input text" data-step="3" data-intro="<?php echo __('The heading text that goes at the top of your contact page. Ex: Contact Me.', true); ?>" data-position="top">
						<?php echo $this->Form->input('contact_header', array(
							'div' => false
						)); ?>
					</div>
					<div class="input text" data-step="4" data-intro="<?php echo __('The explanation text that goes at the top of your contact page. Here you might say your time frame for getting back to people or a message if you are out of town.', true); ?>" data-position="top">
						<?php echo $this->Form->input('contact_message', array(
							'div' => false
						)); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<div class="submit save_button javascript_submit"data-step="5" data-intro="<?php echo __ ('Save your changes.',true); ?>" data-position="top">
			<div class="content"><?php echo __('Save', true); ?></div>
		</div>
	<?php echo $this->Form->end(); ?>
</div>


<?php /*
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
 */ ?>