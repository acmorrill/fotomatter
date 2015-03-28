<?php $uuid = $this->Util->uuid(); ?>
<script type="text/javascript">
	jQuery(document).ready(function() {
		var saving_input_value;
		
		var site_setting_name_name = jQuery('#<?php echo $uuid; ?>').attr('data-site_setting_name');
		var possible_value_regex = "<?php echo $curr_setting['possible_values']; ?>";
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
//						console.log ("success");
					}, 
					function() {
//						console.log ("error");
					},
					site_setting_name_name
				);
			}, 700);
		});
	});
</script>

<?php
	$data_site_setting_name = '';
	if(isset($curr_setting['site_settings_name'])) {
		$data_site_setting_name = "data-site_setting_name='{$curr_setting['site_settings_name']}'";
	}
?>
<div id="<?php echo $uuid; ?>" class="theme_setting_container" <?php echo $data_site_setting_name; ?> >
	<label class="text_input"><?php echo $curr_setting['display_name']; ?></label>
	<div class="theme_setting_inputs_container">
		<form class="text_input_text">
			<input type="text" value="<?php echo $curr_setting['current_value']; ?>" />
		</form>
	</div>
	<p>
		<?php echo $curr_setting['description']; ?>
	</p>
</div>
<div style="clear: both"></div>

<?php // debug($setting_name); ?>
<?php // debug($curr_setting); ?>