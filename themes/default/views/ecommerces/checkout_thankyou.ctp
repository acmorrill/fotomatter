<div id="thankyou_page_container">
	<?php echo $this->Session->flash(); ?>

	<h1><?php echo __('Thank You!', true); ?></h1>
	<p><?php echo strip_tags($this->Util->get_not_empty_theme_setting_or($theme_custom_settings, 'global_frontend_checkout_thankyou_page_text')); ?></p>
</div>