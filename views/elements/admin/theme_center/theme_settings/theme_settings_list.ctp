<?php //debug($avail_settings_list); ?>

<script type="text/javascript">
	function save_theme_setting(setting_name, setting_value, success, error) {
		jQuery('#theme_centers_list_container .saving_theme_setting').stop().fadeIn('fast');
		
		jQuery.ajax({
			type: 'post',
			url: '/admin/theme_centers/ajax_save_theme_settings',
			data: {
				setting_name: setting_name,
				setting_value: setting_value,
				theme_id: '<?php echo $theme_id; ?>'
			},
			success: function(the_data) {
				if (the_data.code == '1') {
					success();
				} else {
					error();
				}
			},
			complete: function() {
				jQuery('#theme_centers_list_container .saving_theme_setting').stop().fadeOut('slow');
				//				console.log ("complete");
			},
			error: function(jqXHR, textStatus, errorThrown) {
				error();
				//				console.log ("error");
				//				console.log (textStatus);
				//				console.log (errorThrown);
			},
			dataType: 'json'
		});
	}
</script>

<?php //debug($avail_settings_list); ?>
<div id="theme_centers_list_container">
	<?php foreach ($avail_settings_list as $setting_name => $curr_setting): ?>
		<?php echo $this->Element('admin/theme_center/theme_settings/settings_renderers/'.$curr_setting['type'], compact(
				'setting_name',
				'curr_setting'
		)); ?>
	<?php endforeach; ?>
	<div class="saving_theme_setting"><?php __('Saving'); ?></div>
</div>
