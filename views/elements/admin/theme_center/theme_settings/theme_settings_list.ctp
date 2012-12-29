<?php //debug($avail_settings_list); ?>

<script type="text/javascript">
	function save_theme_setting(setting_name, setting_value, success, error) {
		jQuery.ajax({
			type: 'post',
			url: '/admin/theme_settings/ajax_save_theme_settings',
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

<?php foreach ($avail_settings_list as $setting_name => $curr_setting): ?>
	<?php echo $this->Element('admin/theme_center/theme_settings/settings_renderers/'.$curr_setting['type'], compact(
			'setting_name',
			'curr_setting'
	)); ?>
<?php endforeach; ?>
