<h1><?php echo __('Choose Your Theme', true); ?></h1>
<div id="welcome_page_container">
	<?php echo $this->Element('admin/theme_center/choose_theme', array(
		'switch_text' => __('Choose Theme', true),
		'change_theme_action' => '/admin/welcome/choose_theme',
		'hide_current' => true,
	)); ?>
</div>
	 

