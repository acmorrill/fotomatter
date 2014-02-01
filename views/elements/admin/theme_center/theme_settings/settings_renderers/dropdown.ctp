<?php $uuid = $this->Util->uuid(); ?>
<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('#<?php echo $uuid; ?> select').change(function() {

			var setting_name = '<?php echo $setting_name; ?>';
			var setting_value = jQuery(this).val();;

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
		<div>
			<select>
				<?php foreach ($curr_setting['possible_values'] as $key => $value_name): ?>
				<option value="<?php echo $key; ?>"<span <?php if ($key == $curr_setting['current_value']): ?>selected="selected"<?php endif; ?> ><?php echo $value_name['display']; ?></option>
				<?php endforeach; ?>
			</select>
		</div>	
	</div>
	<div class="theme_setting_description">
		<?php echo $curr_setting['description']; ?>
	</div>
</div>
<div style="clear: both"></div>

<?php // debug($setting_name); ?>
<?php // debug($curr_setting); ?>