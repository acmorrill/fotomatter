<h1>Theme Settings
	<?php echo $this->Element('/admin/get_help_button'); ?>
</h1>
<p>
	This is the theme settings page - cool right?
</p>
<?php if (empty($avail_settings_list)): ?>
	<h1><?php __('The current theme has no custom settings'); ?></h1>
<?php else: ?>
	<?php echo $this->Element('admin/theme_center/theme_settings/theme_settings_list', compact('avail_settings_list', 'theme_id')); ?>
<?php endif; ?>

	
<?php ob_start(); ?>
<ol>This page is used to select settings specific to your current theme. <br/>Every theme can any settings it wants (as long as it uses the available setting types)
	<li>Here are all the theme setting options (this page has examples of each - make sure you are on the andrewmorrill theme to see settings)
		<ol>
			<li>Checkboxs</li>
			<li>Color radio chooser</li>
			<li>Dropdown</li>
			<li>Numeric Dropdown</li>
			<li>On/Off Dropdown</li>
			<li>Generic Radio</li>
			<li>Generic Text</li>
			<li>Text area with wyziwig</li>
		</ol>
	</li>
	<li>It would good to not change the design of this page too much - be sure to give a design for all the elements</li>
	<li>Notice there is a saving thing that popups in the corner on change of any setting (this is an ajax save) - we need a design for this
		<br/>There are other pages that have an ajax save - those pages should probobly have a similar looking save message
	</li>
</ol>
<?php
$html = ob_get_contents();
ob_end_clean();
	echo $this->Element('admin/richard_notes', array(
	'html' => $html
)); ?>