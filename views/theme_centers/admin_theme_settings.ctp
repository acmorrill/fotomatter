<div style="clear: both;"></div>
<?php if (empty($avail_settings_list)): ?>
	<p><?php __('The current theme has no custom settings'); ?></p>
<?php else: ?>
	<?php echo $this->Element('admin/theme_center/theme_settings/theme_settings_list', compact('avail_settings_list', 'theme_id')); ?>
<?php endif; ?>