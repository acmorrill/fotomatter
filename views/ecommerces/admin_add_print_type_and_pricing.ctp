<script type="text/javascript" src="/js/jquery_price_format/jquery.price_format.min.js"></script>

<?php echo $this->Session->flash(); ?>
<h1>
	<div id="help_tour_button" class="custom_ui"><?php echo $this->Element('/admin/get_help_button'); ?></div>
	<div style="clear: both;"></div>
</h1>
<p>
	<?php echo __('Print Types are the kind of print you offer. Such as canvas wrap, framed, poster, wood mount, framed textured and so on.
	This pages will allow you to add and create new print types that you would like to offer. 
	There can be multiple print types for one image. Now get going on your awesome sauce.', true); ?>
</p>
<div class="page_content_header">
	<?php echo $this->Element('admin/back_button'); ?>
	<h2><?php echo __('Create Available Print Type', true); ?></h2>
</div>
	<form action="" method="post" data-step="1" data-intro="<?php echo __('Here is a list of all the print sizes that were created that now can be linked to a print type.', true); ?>" data-position="top">
		<?php if (!empty($photo_print_type['PhotoPrintType']['id']) && $photo_print_type['PhotoPrintType']['id'] != '0'): ?>
			<input type="hidden" name="data[PhotoPrintType][id]" value="<?php echo $photo_print_type['PhotoPrintType']['id']; ?>" />
		<?php endif; ?>
		<div class="generic_palette_container">
			<div class="fade_background_top"></div>
			<div class="basic_setting_cont no_border">
				<label><?php __('Enter Name of Print Type'); ?></label>
				<div class="theme_setting_inputs_container">
					<input type="text" name="data[PhotoPrintType][print_name]" value="<?php if (!empty($photo_print_type['PhotoPrintType']['print_name'])) echo $photo_print_type['PhotoPrintType']['print_name']; ?>" />
				</div>
				<div class="theme_setting_description">
					<?php echo __('The name of the complete package you are selling. Example: canvas wrap, framed, poster, wood mount, and so on.', true); ?> 
				</div>
			</div>
			<div class="basic_setting_cont no_border">
				<label><?php __('Enter Estimated Turnaround Time'); ?></label>
				<div class="theme_setting_inputs_container">
					<?php $print_type_turnaround_time = (!empty($photo_print_type['PhotoPrintType']['turnaround_time'])) ? $photo_print_type['PhotoPrintType']['turnaround_time'] : ''; ?>
					<input id="print_type_turnaround_time" type="text" name="data[PhotoPrintType][turnaround_time]" prev_value="<?php echo $print_type_turnaround_time; ?>" value="<?php echo $print_type_turnaround_time; ?>" />
				</div>
				<div class="theme_setting_description">
					<?php echo __('The time it takes for you to receive the funds to order the print and send it off. Example: 2 weeks, 4 days and so on.', true); ?>
				</div>
			</div>
		</div>
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
				
			<div class="table_container" style="margin-top: 40px;">
				<div class="fade_background_top"></div>
				<div class="table_top"></div>
				<table id="print_type_price_list" class="list">
					<thead>
						<tr>
							<th class="first" style="min-width: 190px;">
								<div class="content">
									<?php __('Make print type available at size and format??'); ?>
								</div>
							</th>
							<th style="min-width: 80px;">
								<div class="content">
									<?php __('Print Size'); ?>
								</div>
							</th>
							<th style="min-width: 130px;">
								<div class="content">
									<?php __('Print Formats'); ?>
								</div>
							</th>
							<th style="min-width: 144px;">
								<div class="content">
									<?php __('Available on Photo by Default?'); ?>
								</div>
							</th>
							<th>
								<div class="content">
									<?php __('Default Price'); ?>
								</div>
							</th>
							<th>
								<div class="content">
									<?php __('Default Shipping Price'); ?>
								</div>
							</th>
							<th>
								<div class="content">
									<?php __('Default Turnaround Time'); ?>
								</div>
							</th>
							<th class="last">
								<div class="content">
									<?php __('Force as default on photo?'); ?>
								</div>
							</th>
						</tr>
					</thead>
					<tbody>
						<tr class="spacer"><td colspan="8"></td></tr>
						<?php if (empty($photo_avail_sizes)): ?>
							<tr class="first last">
								<td class="first last" colspan="8">
									<div class="rightborder"></div>
									<span><?php echo __('You have not added any print sizes yet.', true); ?></span>
								</td>
							</tr>
						<?php endif; ?>
						<?php $count = 0; foreach ($photo_avail_sizes as $photo_avail_size): ?>
							<?php 
								$has_non_pano = $this->Ecommerce->print_size_has_non_pano($photo_avail_size);
								$has_pano = $this->Ecommerce->print_size_has_pano($photo_avail_size);
							?>
							<?php if ($has_non_pano): ?>
						
							<?php 
								$td_help_code = ''; 
								$size_help_code = ''; 
								$pano_help_code = ''; 
								$default_help_code = ''; 
								$price_help_code = ''; 
								$shipping_price_help_code = ''; 
								$turnaround_help_code = ''; 
								$force_help_code = ''; 
								if ($count === 0) {
									$td_help_code = 'data-step="2" data-intro="'.__('Selecting here will make the print type available on your site.', true).'" data-position="right"';
									$size_help_code = 'data-step="3" data-intro="'.__('This is the image size that has already been created in the managing print size page.', true).'" data-position="bottom"';
									$pano_help_code = 'data-step="4" data-intro="'.__('The format of the image is displayed here. Landscape, panoramic, square etc. ', true).'" data-position="bottom"';
									$default_help_code = 'data-step="5" data-intro="'.__('When you add a new photo it will receive the default settings if this is selected. If you would like not to have each image with the default settings be sure to uncheck this. However you can still modify the price/shipping on the image in the photos tab above.', true).'" data-position="bottom"';
									$price_help_code = 'data-step="6" data-intro="'.__('This is the default price for all images of this type. And will be add to all images of this type if the default setting is selected.', true).'" data-position="bottom"';
									$shipping_price_help_code = 'data-step="7" data-intro="'.__('This is the default shipping price and will be used on all images of this type if the default setting is selecting.', true).'" data-position="left"';
									$turnaround_help_code = 'data-step="8" data-intro="'.__('This is the default turnaround time or how long it will take in total to send the print and will be used on all images of this type if the default setting is selecting.', true).'" data-position="bottom"';
									$force_help_code = 'data-step="9" data-intro="'.__('Selecting the Force setting will over ride any settings that you had and will not let you edit the price/shipping on the images in the photo tab. It will use the default settings for all images but you will not be able to edit them. Only here.', true).'" data-position="left"';
								}
							?>
						
								<tr>
									<td class="first" <?php echo $td_help_code; ?>>
										<input class="available_checkbox" type="checkbox" name="data[PhotoAvailSizesPhotoPrintType][<?php echo $count; ?>][non_pano_available]" <?php if ($photo_avail_size['PhotoAvailSizesPhotoPrintType']['non_pano_available'] == 1): ?>checked="checked"<?php endif; ?> /><br />
									</td>
									<td <?php echo $size_help_code; ?>><?php echo $photo_avail_size['PhotoAvailSize']['short_side_length'];  ?> x -- </td>
									<td style="width: 100px;" <?php echo $pano_help_code; ?>>
										<?php __('Landscape |<br />Portrait |<br />Square'); ?>
										<?php if (!empty($photo_avail_size['PhotoAvailSizesPhotoPrintType']['id'])): ?>
											<input <?php /*class="disablable"*/ ?> type="hidden" value="<?php echo $photo_avail_size['PhotoAvailSizesPhotoPrintType']['id']; ?>" name="data[PhotoAvailSizesPhotoPrintType][<?php echo $count; ?>][id]" />
										<?php endif; ?>
										<input class="disablable" type="hidden" value="<?php echo $photo_avail_size['PhotoAvailSize']['id']; ?>" name="data[PhotoAvailSizesPhotoPrintType][<?php echo $count; ?>][photo_avail_size_id]" />
									</td>
									<td <?php echo $default_help_code; ?>>
										<input class="disablable" type="checkbox" name="data[PhotoAvailSizesPhotoPrintType][<?php echo $count; ?>][non_pano_global_default]" <?php if ($photo_avail_size['PhotoAvailSizesPhotoPrintType']['non_pano_global_default'] == 1): ?>checked="checked"<?php endif; ?> /><br />
									</td>
									<td class="price_width" <?php echo $price_help_code; ?>>
										<span><span>$</span><input class="disablable money_format" type="text" name="data[PhotoAvailSizesPhotoPrintType][<?php echo $count; ?>][non_pano_price]" value="<?php if (!empty($photo_avail_size['PhotoAvailSizesPhotoPrintType']['non_pano_price']) && $photo_avail_size['PhotoAvailSizesPhotoPrintType']['non_pano_price'] != '0.00') echo $photo_avail_size['PhotoAvailSizesPhotoPrintType']['non_pano_price']; ?>" /></span>
									</td>
									<td class="price_width" <?php echo $shipping_price_help_code; ?>>
										<span><span>$</span><input class="disablable money_format" type="text" name="data[PhotoAvailSizesPhotoPrintType][<?php echo $count; ?>][non_pano_shipping_price]"value="<?php if (!empty($photo_avail_size['PhotoAvailSizesPhotoPrintType']['non_pano_shipping_price']) && $photo_avail_size['PhotoAvailSizesPhotoPrintType']['non_pano_shipping_price'] != '0.00') echo $photo_avail_size['PhotoAvailSizesPhotoPrintType']['non_pano_shipping_price']; ?>" /></span>
									</td>
									<td class="text_width" <?php echo $turnaround_help_code; ?>>
										<input class="default_turnaround_time disablable" type="input" name="data[PhotoAvailSizesPhotoPrintType][<?php echo $count; ?>][non_pano_custom_turnaround]" value="<?php if (!empty($photo_avail_size['PhotoAvailSizesPhotoPrintType']['non_pano_custom_turnaround'])) { echo $photo_avail_size['PhotoAvailSizesPhotoPrintType']['non_pano_custom_turnaround']; } else { echo $print_type_turnaround_time; }; ?>" /><br />
									</td>
									<td class="last"<?php echo $force_help_code; ?>>
										<input class="disablable" type="checkbox" name="data[PhotoAvailSizesPhotoPrintType][<?php echo $count; ?>][non_pano_force_settings]" <?php if ($photo_avail_size['PhotoAvailSizesPhotoPrintType']['non_pano_force_settings'] == 1): ?>checked="checked"<?php endif; ?> /><br />
									</td>
								</tr>
							<?php endif; ?>
							<?php if ($has_pano): ?>
								<tr>
									<td class="first">
										<input class="available_checkbox" type="checkbox" name="data[PhotoAvailSizesPhotoPrintType][<?php echo $count; ?>][pano_available]" <?php if ($photo_avail_size['PhotoAvailSizesPhotoPrintType']['pano_available'] == 1): ?>checked="checked"<?php endif; ?> />
									</td>
									<td><?php echo $photo_avail_size['PhotoAvailSize']['short_side_length']; ?> x --</td>
									<td style="width: 100px;">
										<?php __('Panoramic |<br />Vertical Panoramic'); ?>
									</td>
									<td>
										<input class="disablable" type="checkbox" name="data[PhotoAvailSizesPhotoPrintType][<?php echo $count; ?>][pano_global_default]" <?php if ($photo_avail_size['PhotoAvailSizesPhotoPrintType']['pano_global_default'] == 1): ?>checked="checked"<?php endif; ?> />
									</td>
									<td class="price_width">
										<span><span>$</span><input class="disablable money_format" type="text" name="data[PhotoAvailSizesPhotoPrintType][<?php echo $count; ?>][pano_price]"value="<?php if (!empty($photo_avail_size['PhotoAvailSizesPhotoPrintType']['pano_price']) && $photo_avail_size['PhotoAvailSizesPhotoPrintType']['pano_price'] != '0.00') echo $photo_avail_size['PhotoAvailSizesPhotoPrintType']['pano_price']; ?>" /></span>
									</td>
									<td class="price_width">
										<span><span>$</span><input class="disablable money_format" type="text" name="data[PhotoAvailSizesPhotoPrintType][<?php echo $count; ?>][pano_shipping_price]"value="<?php if (!empty($photo_avail_size['PhotoAvailSizesPhotoPrintType']['pano_shipping_price']) && $photo_avail_size['PhotoAvailSizesPhotoPrintType']['pano_shipping_price'] != '0.00') echo $photo_avail_size['PhotoAvailSizesPhotoPrintType']['pano_shipping_price']; ?>" /></span>
									</td>
									<td class="text_width">
										<input class="disablable" type="input" name="data[PhotoAvailSizesPhotoPrintType][<?php echo $count; ?>][pano_custom_turnaround]" value="<?php if (!empty($photo_avail_size['PhotoAvailSizesPhotoPrintType']['pano_custom_turnaround'])) { echo $photo_avail_size['PhotoAvailSizesPhotoPrintType']['pano_custom_turnaround']; } else { echo $print_type_turnaround_time; } ?>" />
									</td>
									<td class="last">
										<input class="disablable" type="checkbox" name="data[PhotoAvailSizesPhotoPrintType][<?php echo $count; ?>][pano_force_settings]" <?php if ($photo_avail_size['PhotoAvailSizesPhotoPrintType']['pano_force_settings'] == 1): ?>checked="checked"<?php endif; ?> />
									</td>
								</tr>
							<?php endif; ?>
						<?php $count++; endforeach; ?>
					</tbody>
				</table>
				<script type="text/javascript">
					jQuery(document).ready(function() {
						jQuery('#save_print_type_button').click(function() {
							jQuery(this).closest('form').submit();
						});
					});
				</script>
				<div id="save_print_type_button" class="save_button" data-step="10" data-intro="<?php echo __('Make sure to save your work.', true); ?>" data-position="top">
					<div class="content"><?php echo __('Save', true); ?></div>
				</div>
			</div>
	</form>

<?php ob_start(); ?>
<ol>
	<li>This page is where you can add a print type</li>
	<li><a href="/img/admin_screenshots/add_print_type.jpg" target="_blank">screenshot</a></li>
	<li>Things to remember
		<ol>
			<li>This page needs a flash message</li>
			<li>This page will need explanation text at the top (or somewhere)</li>
			<li>If a print type is not available at a size and format the whole line should be grayed out - we need a design for this</li>
			<li>We probobly want to put the save button at the top and the bottom so its obvious</li>
		</ol>
	</li>
</ol>
<?php
$html = ob_get_contents();
ob_end_clean();
	echo $this->Element('admin/richard_notes', array(
	'html' => $html
)); ?>