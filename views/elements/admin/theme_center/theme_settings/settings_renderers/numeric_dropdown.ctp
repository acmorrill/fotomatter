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
	<label class="numeric_dropdown"><?php echo $curr_setting['display_name']; ?></label>
	<div class="theme_setting_inputs_container">
		<select>
			<?php for ($i = $curr_setting['possible_values']['min']; $i <= $curr_setting['possible_values']['max']; $i++): ?>
				<option value="<?php echo $i; ?>" <?php if ($i == $curr_setting['current_value']): ?>selected="selected"<?php endif; ?> ><?php echo $i; ?></option>
			<?php endfor; ?>
		</select>
	</div>
	<p>
		<?php echo $curr_setting['description']; ?>
	</p>
</div>
<div style="clear: both"></div>

<?php // debug($setting_name); ?>
<?php // debug($curr_setting); ?>