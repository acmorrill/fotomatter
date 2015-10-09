<?php 
	$prefix_str = '';
	if (!empty($prefix)) { $prefix_str = $prefix; }
	$selected_str = '';
	if ($selected == true) {
		$selected_str = 'SELECTED="SELECTED"';
	}
?>
<option <?php echo $selected_str; ?> value="<?php echo $printer_data['PrintFulfiller']['id']; ?>"><?php echo $prefix_str; ?><?php echo $printer_data['PrintFulfiller']['lab_name']; ?> (<?php if (!empty($printer_data['PrintFulfiller']['state_code'])) { echo trim($printer_data['PrintFulfiller']['state_code'] . ", "); } ?><?php echo trim($printer_data['PrintFulfiller']['country_code']); ?>)</option>