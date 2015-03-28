<?php $uuid = $this->Util->uuid(); ?>
<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('#<?php echo $uuid; ?> form input:checkbox[name=<?php echo $setting_name; ?>]').click(function() {
			var setting_name = '<?php echo $setting_name; ?>';
			var setting_values = new Array();
			jQuery('#<?php echo $uuid; ?> form input:checkbox[name=<?php echo $setting_name; ?>]:checked').each(function() {
				setting_values.push(jQuery(this).val());
			});
			var setting_value = setting_values.join('|');

//			console.log (setting_name);
//			console.log (setting_value);

			save_theme_setting(setting_name, setting_value, 
				function() {
//					console.log ("success");
				}, 
				function() {
//					console.log ("error");
				}
			);
		});
	});
</script>

<div id="<?php echo $uuid; ?>" class="theme_setting_container">
	<label class="check_boxes_text"><?php echo $curr_setting['display_name']; ?></label>
	<div class="theme_setting_inputs_container">
		<form class="check_boxes">
			<?php $selected_values = explode('|', $curr_setting['current_value']); ?>
			<?php foreach ($curr_setting['possible_values'] as $key => $value_name): ?>
				<input type="checkbox" name="<?php echo $setting_name; ?>" value="<?php echo $key; ?>" <?php if (in_array($key, $selected_values)): ?>checked="checked"<?php endif; ?> />
				<label><?php echo $value_name['display']; ?></label>
				<div></div>
			<?php endforeach; ?>
		</form>
	</div>
	<p>
		<?php echo $curr_setting['description']; ?>
	</p>
</div>
<div style="clear: both"></div>

<?php // debug($setting_name); ?>
<?php // debug($curr_setting); ?>