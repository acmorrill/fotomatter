<?php $uuid = $this->Util->uuid(); ?>
<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('#<?php echo $uuid; ?> form input:radio[name=<?php echo $setting_name; ?>]').click(function() {
			var setting_name = '<?php echo $setting_name; ?>';
			var setting_value = jQuery(this).val();

//			console.log (setting_name);
//			console.log (setting_value);

			save_theme_setting(setting_name, setting_value, 
				function() {
					console.log ("success");
				}, 
				function() {
					console.log ("error");
				}
			);
		});
	});
</script>

<div id="<?php echo $uuid; ?>" class="theme_setting_container">
	<label><?php echo $curr_setting['display_name']; ?></label>
	<div class="theme_setting_inputs_container">
		<form>
			<?php $count = 1; foreach ($curr_setting['possible_values'] as $value_name): ?>
				<input type="radio" name="<?php echo $setting_name; ?>" value="<?php echo $value_name; ?>" <?php if ($value_name == $curr_setting['current_value'] || (empty($curr_setting['current_value']) == true && $count == 1)): ?>checked="checked"<?php endif; ?>  /> <span class="color_chooser" style="background-color: <?php echo $value_name; ?>;">&nbsp;</span><br/>
			<?php $count++; endforeach; ?>
		</form>
	</div>
	<div class="theme_setting_description">
		<?php echo $curr_setting['description']; ?>
	</div>
</div>
<div style="clear: both"></div>

<?php // debug($setting_name); ?>
<?php // debug($curr_setting); ?>