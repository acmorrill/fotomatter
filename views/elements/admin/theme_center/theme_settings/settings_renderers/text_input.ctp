<?php $uuid = $this->Util->uuid(); ?>
<script type="text/javascript">
	jQuery(document).ready(function() {
		var saving_input_value;
		
		var possible_value_regex = "<?php echo $curr_setting['possible_values']; ?>";
		console.log (possible_value_regex);
		var patt = undefined;
		if (possible_value_regex != '') {
			patt = new RegExp(possible_value_regex);
		}
		
		jQuery('#<?php echo $uuid; ?> form input').keyup(function() {
			clearTimeout(saving_input_value);


			saving_input_value = setTimeout(function() {
				var setting_name = '<?php echo $setting_name; ?>';
				var setting_value = jQuery('#<?php echo $uuid; ?> form input').val();

				if (patt != undefined && patt.test(setting_value) === false) {
					
					return false;
				}


				save_theme_setting(setting_name, setting_value, 
					function() {
						console.log ("success");
					}, 
					function() {
						console.log ("error");
					}
				);
			}, 700);
		});
	});
</script>

<div id="<?php echo $uuid; ?>" class="theme_setting_container">
	<label><?php echo $curr_setting['display_name']; ?></label>
	<div class="theme_setting_inputs_container">
		<form>
			<input type="text" value="<?php echo $curr_setting['current_value']; ?>" />
		</form>
	</div>
	<div class="theme_setting_description">
		<?php echo $curr_setting['description']; ?>
	</div>
</div>
<div style="clear: both"></div>

<?php // debug($setting_name); ?>
<?php // debug($curr_setting); ?>