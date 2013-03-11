<script type="text/javascript" src="/js/jquery_price_format/jquery.price_format.min.js"></script>

<div class="basic_settings">
	<div class="basic_setting_cont">
		<label>&nbsp;</label>
		<div class="theme_setting_inputs_container">
			<?php echo $this->Session->flash(); ?>
		</div>
	</div>
	<form action="" method="post">
<!--		<h2 class="group_list_name">Add Dimension</h2>-->
		<?php if (!empty($photo_print_type['PhotoPrintType']['id']) && $photo_print_type['PhotoPrintType']['id'] != '0'): ?>
			<input type="hidden" name="data[PhotoPrintType][id]" value="<?php echo $photo_print_type['PhotoPrintType']['id']; ?>" />
		<?php endif; ?>
		<div class="basic_setting_cont">
			<label><?php __('Print Type Name'); ?></label>
			<div class="theme_setting_inputs_container">
				<input type="text" name="data[PhotoPrintType][print_name]" value="<?php if (!empty($photo_print_type['PhotoPrintType']['print_name'])) echo $photo_print_type['PhotoPrintType']['print_name']; ?>" />
			</div>
			<div class="theme_setting_description">
				This is the description.
			</div>
		</div>
		<div class="basic_setting_cont">
			<label><?php __('Turnaround Time'); ?></label>
			<div class="theme_setting_inputs_container">
				<?php $print_type_turnaround_time = (!empty($photo_print_type['PhotoPrintType']['turnaround_time'])) ? $photo_print_type['PhotoPrintType']['turnaround_time'] : ''; ?>
				<input id="print_type_turnaround_time" type="text" name="data[PhotoPrintType][turnaround_time]" prev_value="<?php echo $print_type_turnaround_time; ?>" value="<?php echo $print_type_turnaround_time; ?>" />
			</div>
			<div class="theme_setting_description">
				This is the other description.
			</div>
		</div>
		<div id="print_type_pricing_cont" class="basic_setting_cont">
			<label><?php __('Pricing'); ?></label>
			<div class="theme_setting_inputs_container">
				<script type="text/javascript">
					function setup_available_checkbox(checkbox) {
						var parent_tr = jQuery(checkbox).closest('tr');
						var disablable = parent_tr.find('.disablable');

						if (jQuery(checkbox).is(':checked')) {
							disablable.removeAttr('disabled');
						} else {
							disablable.attr('disabled', 'disabled');
						}
					}
					
					jQuery(document).ready(function() {
						jQuery('.available_checkbox').change(function() {
							setup_available_checkbox(this);
						}).each(function(){ 
							setup_available_checkbox(this);
						});
						
						$('#print_type_pricing_cont .money_format').priceFormat({
							prefix: '',
							thousandsSeparator: ''
						});
						
						// set the turnaraund times below based on changes to the default (only if below is not set differently)
						jQuery('#print_type_turnaround_time').keyup(function() {
							var curr_value = jQuery(this).val();
							var prev_value = jQuery(this).attr('prev_value');
							jQuery(this).attr('prev_value', curr_value);
							jQuery('#print_type_price_list tbody tr td input.default_turnaround_time').filter(function() { 
								return $(this).val() == "" || $(this).val() == prev_value; 
							}).val(curr_value);
						});
					});
				</script>
				
				<table id="print_type_price_list" class="list">
					<thead>
						<tr>
							<th><?php __('Print Type Available at Size and Format?'); ?></th>
							<th><?php __('Size'); ?></th>
							<th><?php __('Formats'); ?></th>
							<th><?php __('Available on Photo by Default?'); ?></th>
							<th><?php __('Default Price'); ?></th>
							<th><?php __('Default Shipping Price'); ?></th>
							<th><?php __('Default Turnaround Time'); ?></th>
							<th><?php __('Force Defaults On Photo?'); ?></th>
						</tr>
					</thead>
					<tbody>
						
						<?php $count = 0; foreach ($photo_avail_sizes as $photo_avail_size): ?>
							<?php 
								$has_non_pano = $this->Ecommerce->print_size_has_non_pano($photo_avail_size);
								$has_pano = $this->Ecommerce->print_size_has_pano($photo_avail_size);
							?>
							<?php if ($has_non_pano): ?>
								<tr>
									<td>
										<input class="available_checkbox" type="checkbox" name="data[PhotoAvailSizesPhotoPrintType][<?php echo $count; ?>][non_pano_available]" <?php if ($photo_avail_size['PhotoAvailSizesPhotoPrintType']['non_pano_available'] == 1): ?>checked="checked"<?php endif; ?> /><br />
									</td>
									<td><?php echo $photo_avail_size['PhotoAvailSize']['short_side_length']; ?> x --</td>
									<td style="width: 100px;">
										<?php __('Landscape | Portrait | Square'); ?>
										<?php if (!empty($photo_avail_size['PhotoAvailSizesPhotoPrintType']['id'])): ?>
											<input <?php /*class="disablable"*/ ?> type="hidden" value="<?php echo $photo_avail_size['PhotoAvailSizesPhotoPrintType']['id']; ?>" name="data[PhotoAvailSizesPhotoPrintType][<?php echo $count; ?>][id]" />
										<?php endif; ?>
										<input class="disablable" type="hidden" value="<?php echo $photo_avail_size['PhotoAvailSize']['id']; ?>" name="data[PhotoAvailSizesPhotoPrintType][<?php echo $count; ?>][photo_avail_size_id]" />
									</td>
									<td>
										<input class="disablable" type="checkbox" name="data[PhotoAvailSizesPhotoPrintType][<?php echo $count; ?>][non_pano_global_default]" <?php if ($photo_avail_size['PhotoAvailSizesPhotoPrintType']['non_pano_global_default'] == 1): ?>checked="checked"<?php endif; ?> /><br />
									</td>
									<td class="price_width">
										$<input class="disablable money_format" type="text" name="data[PhotoAvailSizesPhotoPrintType][<?php echo $count; ?>][non_pano_price]" value="<?php if (!empty($photo_avail_size['PhotoAvailSizesPhotoPrintType']['non_pano_price']) && $photo_avail_size['PhotoAvailSizesPhotoPrintType']['non_pano_price'] != '0.00') echo $photo_avail_size['PhotoAvailSizesPhotoPrintType']['non_pano_price']; ?>" /><br />
									</td>
									<td class="price_width">
										$<input class="disablable money_format" type="text" name="data[PhotoAvailSizesPhotoPrintType][<?php echo $count; ?>][non_pano_shipping_price]"value="<?php if (!empty($photo_avail_size['PhotoAvailSizesPhotoPrintType']['non_pano_shipping_price']) && $photo_avail_size['PhotoAvailSizesPhotoPrintType']['non_pano_shipping_price'] != '0.00') echo $photo_avail_size['PhotoAvailSizesPhotoPrintType']['non_pano_shipping_price']; ?>" /><br />
									</td>
									<td>
										<input class="default_turnaround_time disablable" type="input" name="data[PhotoAvailSizesPhotoPrintType][<?php echo $count; ?>][non_pano_custom_turnaround]" value="<?php if (!empty($photo_avail_size['PhotoAvailSizesPhotoPrintType']['non_pano_custom_turnaround'])) { echo $photo_avail_size['PhotoAvailSizesPhotoPrintType']['non_pano_custom_turnaround']; } else { echo $print_type_turnaround_time; }; ?>" /><br />
									</td>
									<td>
										<input class="disablable" type="checkbox" name="data[PhotoAvailSizesPhotoPrintType][<?php echo $count; ?>][non_pano_force_settings]" <?php if ($photo_avail_size['PhotoAvailSizesPhotoPrintType']['non_pano_force_settings'] == 1): ?>checked="checked"<?php endif; ?> /><br />
									</td>
								</tr>
							<?php endif; ?>
							<?php if ($has_pano): ?>
								<tr>
									<td>
										<input class="available_checkbox" type="checkbox" name="data[PhotoAvailSizesPhotoPrintType][<?php echo $count; ?>][pano_available]" <?php if ($photo_avail_size['PhotoAvailSizesPhotoPrintType']['pano_available'] == 1): ?>checked="checked"<?php endif; ?> />
									</td>
									<td><?php echo $photo_avail_size['PhotoAvailSize']['short_side_length']; ?> x --</td>
									<td style="width: 100px;">
										<?php __('Panoramic | Vertical Panoramic'); ?>
									</td>
									<td>
										<input class="disablable" type="checkbox" name="data[PhotoAvailSizesPhotoPrintType][<?php echo $count; ?>][pano_global_default]" <?php if ($photo_avail_size['PhotoAvailSizesPhotoPrintType']['pano_global_default'] == 1): ?>checked="checked"<?php endif; ?> />
									</td>
									<td class="price_width">
										$<input class="disablable money_format" type="text" name="data[PhotoAvailSizesPhotoPrintType][<?php echo $count; ?>][pano_price]"value="<?php if (!empty($photo_avail_size['PhotoAvailSizesPhotoPrintType']['pano_price']) && $photo_avail_size['PhotoAvailSizesPhotoPrintType']['pano_price'] != '0.00') echo $photo_avail_size['PhotoAvailSizesPhotoPrintType']['pano_price']; ?>" />
									</td>
									<td class="price_width">
										$<input class="disablable money_format" type="text" name="data[PhotoAvailSizesPhotoPrintType][<?php echo $count; ?>][pano_shipping_price]"value="<?php if (!empty($photo_avail_size['PhotoAvailSizesPhotoPrintType']['pano_shipping_price']) && $photo_avail_size['PhotoAvailSizesPhotoPrintType']['pano_shipping_price'] != '0.00') echo $photo_avail_size['PhotoAvailSizesPhotoPrintType']['pano_shipping_price']; ?>" />
									</td>
									<td>
										<input class="disablable" type="input" name="data[PhotoAvailSizesPhotoPrintType][<?php echo $count; ?>][pano_custom_turnaround]" value="<?php if (!empty($photo_avail_size['PhotoAvailSizesPhotoPrintType']['pano_custom_turnaround'])) { echo $photo_avail_size['PhotoAvailSizesPhotoPrintType']['pano_custom_turnaround']; } else { echo $print_type_turnaround_time; } ?>" />
									</td>
									<td>
										<input class="disablable" type="checkbox" name="data[PhotoAvailSizesPhotoPrintType][<?php echo $count; ?>][pano_force_settings]" <?php if ($photo_avail_size['PhotoAvailSizesPhotoPrintType']['pano_force_settings'] == 1): ?>checked="checked"<?php endif; ?> />
									</td>
								</tr>
							<?php endif; ?>
						<?php $count++; endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
		<div style="clear: both"></div>
		<div class="basic_setting_cont">
			<label>&nbsp;</label>
			<div class="theme_setting_inputs_container">
				<input type="submit" value="Save" />
			</div>
		</div>
	</form>
</div>

<table>
	
</table>