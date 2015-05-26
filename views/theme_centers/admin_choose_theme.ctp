<h1><?php echo __('Choose Your Theme', true); ?>
	<div id="help_tour_button" class="custom_ui"><?php echo $this->Element('/admin/get_help_button'); ?></div>
</h1>
<p><?php echo __('Each fotomatter.net theme is highly customizable. Try each one to see its specialized features and make it your own. We are currently in the process of adding additional themes and color variations. Click on “Live Site” (bottom left) to view your site at any time.', true)?>
<?php echo $this->Element('admin/theme_center/choose_theme'); ?>


<?php /*
<?php ob_start(); ?>
<ol>
	<li>This page will need a a flash message possibly</li>
	<li>When you change current theme - get flash message that says your theme changed</li>
	<li>Do a modal popup for zoom to theme</li>
	<li>Current Theme should be highlighted somehow</li>
	<li>We should maybe have a link to a live demo for each theme (which we will have for the frontend site)</li>
</ol>
<?php
$html = ob_get_contents();
ob_end_clean();
	echo $this->Element('admin/richard_notes', array(
	'html' => $html
)); ?>
 * 
 */ ?>