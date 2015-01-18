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
	<label class="small_image_radio_text"><?php echo $curr_setting['display_name']; ?></label>
	<div class="theme_setting_inputs_container">
		<form class="small_image_radio">
			<?php $count = 1; foreach ($curr_setting['possible_values'] as $value_name): ?>
				<input type="radio" name="<?php echo $setting_name; ?>" value="<?php echo $value_name; ?>" <?php if ($value_name == $curr_setting['current_value'] || (empty($curr_setting['current_value']) == true && $count == 1)): ?>checked="checked"<?php endif; ?>  />
				<a href='<?php echo $value_name; ?>' target='<?php echo $value_name; ?>'><span class="image_chooser" style="background: url('<?php echo $value_name; ?>') no-repeat; background-position: center center; background-size: 100% auto;">&nbsp;</span></a>
				<?php if ($count % 2 == 0): ?><div></div><?php endif; ?>
			<?php $count++; endforeach; ?>
		</form>
	</div>
	<p>
		<?php echo $curr_setting['description']; ?>
	</p>
</div>
<div style="clear: both"></div>

<?php // debug($setting_name); ?>
<?php // debug($curr_setting); ?>