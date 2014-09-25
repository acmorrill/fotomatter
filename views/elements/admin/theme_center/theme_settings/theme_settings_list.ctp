<?php //debug($avail_settings_list); ?>

<script type="text/javascript">
	function save_theme_setting(setting_name, setting_value, success, error) {
		show_universal_save();
		
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
				hide_universal_save();
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

<?php
	$grouped_avail_settings = array();
	$group_count = 0; 
	foreach ($avail_settings_list as $avail_setting_name => $avail_setting) {
		if ($avail_setting['type'] === 'group_name') {
			$group_count++;
			$grouped_avail_settings[$group_count]['group_name'] = $avail_setting['display_name'];
		} else {
			$grouped_avail_settings[$group_count]['group_avail_settings'][$avail_setting_name] = $avail_setting;
		}
	}
?>
<div id="theme_centers_list_container">
	<?php foreach($grouped_avail_settings as $grouped_avail_setting): ?>
		<div class="page_content_header">
			<p><?php echo $grouped_avail_setting['group_name']; ?></p>
		</div>
		<div class='generic_palette_container'>
			<div class="fade_background_top"></div>
			<?php $total_group_settings = count($grouped_avail_setting['group_avail_settings']); $count = 1; foreach ($grouped_avail_setting['group_avail_settings'] as $setting_name => $curr_setting): ?>
				<?php echo $this->Element('admin/theme_center/theme_settings/settings_renderers/'.$curr_setting['type'], compact(
						'setting_name',
						'curr_setting'
				)); ?>
				<?php if ($count != $total_group_settings): ?>
					<div class="hr_element"></div>
				<?php endif; ?>
			<?php $count++; endforeach; ?>
		</div>
	<?php endforeach; ?>
</div>
