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
		<div class="basic_setting_cont">
			<label><?php __('Print Type Name'); ?></label>
			<div class="theme_setting_inputs_container">
				<input type="text" name="data[PhotoPrintType][print_name]" />
			</div>
			<div class="theme_setting_description">
				This is the description.
			</div>
		</div>
		<div class="basic_setting_cont">
			<label><?php __('Default Turnaround Time'); ?></label>
			<div class="theme_setting_inputs_container">
				<input type="text" name="data[PhotoPrintType][turnaround_time]" />
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
					});
				</script>
				
				<table class="list">
					<thead>
						<tr>
							<th>Dimension</th>
							<th>Formats</th>
							<th>Available at Size</th>
							<th>Price</th>
							<th>Shipping Price</th>
							<th>Custom Turnaround Time</th>
							<th>Used by Default</th>
							<th>Force Setting on Photos?</th>
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
									<td><?php echo $photo_avail_size['PhotoAvailSize']['short_side_length']; ?> x --</td>
									<td style="width: 100px;">
										Landscape | Portrait | Square
										<input class="disablable" type="hidden" value="<?php echo $photo_avail_size['PhotoAvailSize']['id']; ?>" name="data[PhotoAvailSizesPhotoPrintType][<?php echo $count; ?>][photo_avail_size_id]" />
									</td>
									<td>
										<input class="available_checkbox" type="checkbox" name="data[PhotoAvailSizesPhotoPrintType][<?php echo $count; ?>][non_pano_available]" /><br />
									</td>
									<td class="price_width">
										$<input class="disablable money_format" type="text" name="data[PhotoAvailSizesPhotoPrintType][<?php echo $count; ?>][non_pano_price]" /><br />
									</td>
									<td class="price_width">
										$<input class="disablable money_format" type="text" name="data[PhotoAvailSizesPhotoPrintType][<?php echo $count; ?>][non_pano_shipping_price]" /><br />
									</td>
									<td>
										<input class="disablable" type="input" name="data[PhotoAvailSizesPhotoPrintType][<?php echo $count; ?>][non_pano_custom_turnaround]" /><br />
									</td>
									<td>
										<input class="disablable" type="checkbox" name="data[PhotoAvailSizesPhotoPrintType][<?php echo $count; ?>][non_pano_global_default]" /><br />
									</td>
									<td>
										<input class="disablable" type="checkbox" name="data[PhotoAvailSizesPhotoPrintType][<?php echo $count; ?>][non_pano_force_settings]" /><br />
									</td>
								</tr>
							<?php endif; ?>
							<?php if ($has_pano): ?>
								<tr>
									<td><?php echo $photo_avail_size['PhotoAvailSize']['short_side_length']; ?> x --</td>
									<td style="width: 100px;">
										Panoramic | Vertical Panoramic
									</td>
									<td>
										<input class="available_checkbox" type="checkbox" name="data[PhotoAvailSizesPhotoPrintType][<?php echo $count; ?>][pano_available]" />
									</td>
									<td class="price_width">
										$<input class="disablable money_format" type="text" name="data[PhotoAvailSizesPhotoPrintType][<?php echo $count; ?>][pano_price]" />
									</td>
									<td class="price_width">
										$<input class="disablable money_format" type="text" name="data[PhotoAvailSizesPhotoPrintType][<?php echo $count; ?>][pano_shipping_price]" />
									</td>
									<td>
										<input class="disablable" type="input" name="data[PhotoAvailSizesPhotoPrintType][<?php echo $count; ?>][pano_custom_turnaround]" />
									</td>
									<td>
										<input class="disablable" type="checkbox" name="data[PhotoAvailSizesPhotoPrintType][<?php echo $count; ?>][pano_global_default]" />
									</td>
									<td>
										<input class="disablable" type="checkbox" name="data[PhotoAvailSizesPhotoPrintType][<?php echo $count; ?>][pano_force_settings]" />
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