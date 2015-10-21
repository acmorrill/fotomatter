<script type="text/javascript" src="/js/jquery_price_format/jquery.price_format.min.js"></script>

<?php echo $this->Session->flash(); ?>
<h1><?php echo __('Add/Edit Print Type - Automatic', true); ?>
	<div class="custom_ui right">
		<a href="/admin/ecommerces/manage_print_sizes">
			<div class="add_button">
				<div class="content"><?php echo __('Manage Default Print Sizes', true); ?></div><div class="right_arrow_lines icon-arrow-01"><div></div></div>
			</div>
		</a>
	</div>
	<div id="help_tour_button" class="custom_ui"><?php //echo $this->Element('/admin/get_help_button'); ?></div>
	<div style="clear: both;"></div>
</h1>
<p><?php echo __('Create print types, add sizes available to those print types, and set default pricing and shipping. To change pricing from the default structure on one specific photo, go to &ldquo;Pricing Override&rdquo; under the Photos tab.', true); ?></p>
<div class="page_content_header">
	<?php echo $this->Element('admin/back_button'); ?>
	<h2><?php echo __('Print Type Settings', true); ?></h2>
</div>

<script type="text/javascript">
	function setup_available_checkbox(checkbox) {
		var parent_tr = jQuery(checkbox).closest('tr');
		var disablable = parent_tr.find('.disablable');

		if (jQuery(checkbox).is(':checked')) {
			disablable.removeAttr('disabled');
			disablable.removeClass('disabled');
		} else {
			disablable.attr('disabled', 'disabled');
			disablable.addClass('disabled');
		}
	}

	function get_print_type_settings() {
		var print_type_data = {};
		jQuery('#print_type_settings input').each(function() {
			print_type_data[jQuery(this).attr('name')] = jQuery(this).val();
		});
		
		var photo_print_type_id = jQuery('#print_type_settings').attr('data-photo_print_type_id');
		if (typeof photo_print_type_id === 'string') {
			print_type_data['data[PhotoPrintType][id]'] = photo_print_type_id;
		}
		
		return print_type_data;
	}
	
	
	function ajax_save_print_type_price_list_item(callback, parent_tr) {
		show_universal_save();
		var data_to_save = get_print_type_settings();
		if (typeof parent_tr != 'undefined') {
			jQuery('input', parent_tr).each(function() {
				if (jQuery(this).is(':checkbox')) {
					if (jQuery(this).is(':checked')) {
						data_to_save[jQuery(this).attr('name')] = 1;
					}
				} else {
					data_to_save[jQuery(this).attr('name')] = jQuery(this).val();
				}
			});
			var photo_avail_sizes_photo_print_type_id = jQuery(parent_tr).attr('data-avail_sizes_photo_print_type_id');
			if (typeof photo_avail_sizes_photo_print_type_id === 'string') {
				data_to_save['data[PhotoAvailSizesPhotoPrintType][id]'] = photo_avail_sizes_photo_print_type_id;
			}
		}
		
		
		jQuery.ajax({
			type: 'post',
			url: '/admin/ecommerces/ajax_save_print_type_and_pricing',
			data: data_to_save,
			success: function(data) {
				if (typeof parent_tr != 'undefined') {
					jQuery(parent_tr).removeAttr('data-avail_sizes_photo_print_type_id');
					if (typeof data.data.photo_avail_sizes_photo_print_type_id == 'string') {
						jQuery(parent_tr).attr('data-avail_sizes_photo_print_type_id', data.data.photo_avail_sizes_photo_print_type_id);
					}
				}
			},
			complete: function() {
				hide_universal_save();
				callback();
			},
			error: function(jqXHR, textStatus, errorThrown) {

			},
			dataType: 'json'
		});
	}
	

	jQuery(document).ready(function() {
		jQuery('.available_checkbox').each(function(){ 
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



		var saving_input_value;
		var in_ajax = false;
		var save_timeout = 1000;
		jQuery('#print_type_settings input').keydown(function(e) {
			if (e.which == 9) { return; }
			if (in_ajax === true) {
				e.preventDefault();
				return false;
			}
			clearTimeout(saving_input_value);
			saving_input_value = setTimeout(function() {
				in_ajax = true;
				ajax_save_print_type_price_list_item(function() {
					in_ajax = false;
				});
			}, save_timeout);
		});
		jQuery('#print_type_price_list input').keydown(function(e) {
			if (e.which == 9) { return; }
			if (in_ajax === true) {
				e.preventDefault();
				return false;
			}
			clearTimeout(saving_input_value);
			var parent_tr = jQuery(this).closest('tr');
			saving_input_value = setTimeout(function() {
				in_ajax = true;
				ajax_save_print_type_price_list_item(function() {
					in_ajax = false;
				}, parent_tr);
			}, save_timeout);
		});
		jQuery('#print_type_price_list input:checkbox').change(function(e) {
			if (e.which == 9) { return; }
			if (in_ajax === true) {
				e.preventDefault();
				if (jQuery(this).is(':checked')) {
					jQuery(this).prop('checked', false);
				} else {
					jQuery(this).prop('checked', true);
				}
				return false;
			}
			clearTimeout(saving_input_value);
			if (jQuery(this).hasClass('available_checkbox')) {
				setup_available_checkbox(this);
			}
			var parent_tr = jQuery(this).closest('tr');
			in_ajax = true;
			ajax_save_print_type_price_list_item(function() {
				in_ajax = false;
			}, parent_tr);
		});
	});
</script>


	<?php 
		$photo_print_type_id_str = '';
		if (!empty($photo_print_type['PhotoPrintType']['id']) && $photo_print_type['PhotoPrintType']['id'] != '0') {
			$photo_print_type_id_str = 'data-photo_print_type_id="' . $photo_print_type['PhotoPrintType']['id'] .'"';
		}
	?>
	<form id="print_types_form" action="" method="post" data-step="1" data-intro="<?php echo __('Here is a list of all the print sizes that were created that now can be linked to a print type.', true); ?>" data-position="top">
		<div id="print_type_settings" <?php echo $photo_print_type_id_str; ?> class="generic_palette_container">
			<div class="fade_background_top"></div>
			<div class="basic_setting_cont no_border">
				<label><?php echo __('Name of Print Type', true); ?></label>
				<div class="theme_setting_inputs_container">
					<input type="text" name="data[PhotoPrintType][print_name]" value="<?php if (!empty($photo_print_type['PhotoPrintType']['print_name'])) echo $photo_print_type['PhotoPrintType']['print_name']; ?>" />
				</div>
				<div class="theme_setting_description">
					<?php echo __('The name of the type of print you are selling. Examples: canvas wrap, wood mount, aluminum, framed, poster, Fuji Crystal Archive paper, etc.', true); ?> 
				</div>
			</div>
			<div class="basic_setting_cont no_border">
				<label><?php echo __('Estimated Turnaround Time', true); ?></label>
				<div class="theme_setting_inputs_container">
					<?php $print_type_turnaround_time = (!empty($photo_print_type['PhotoPrintType']['turnaround_time'])) ? $photo_print_type['PhotoPrintType']['turnaround_time'] : ''; ?>
					<input id="print_type_turnaround_time" type="text" name="data[PhotoPrintType][turnaround_time]" prev_value="<?php echo $print_type_turnaround_time; ?>" value="<?php echo $print_type_turnaround_time; ?>" />
				</div>
				<div class="theme_setting_description">
					<?php echo __('The amount of time it takes you to fulfill an order (to receive the funds, order the print, and to ship.) Example: 3 weeks, 5 days, etc. Note: some themes currently don\'t list this anywhere. ', true); ?>
				</div>
			</div>
		</div>
		
		
		<div class="table_container" style="margin-top: 40px;">
			<div class="fade_background_top"></div>
			<div class="table_top"></div>
			<table id="print_type_price_list" class="list">
				<thead>
					<tr>
						<th class="first" style="min-width: 100px; max-width: 162px;">
							<div class="content">
								<?php echo __('Make print type available for size?', true); ?>
							</div>
						</th>
						<th style="min-width: 80px;">
							<div class="content">
								<?php echo __('Print Size Inches', true); ?>
							</div>
						</th>
						<?php /*<th style="min-width: 130px;">
							<div class="content">
								<?php echo __('Panoramic Size?', true); ?>
							</div>
						</th>*/ ?>
<!--							<th style="min-width: 144px;">
							<div class="content">
								<?php echo __('Available on Photo by Default?', true); ?>
							</div>
						</th>-->
						<th>
							<div class="content">
								<?php echo __('Default Pricing', true); ?>
							</div>
						</th>
						<th class="last">
							<div class="content">
								<?php echo __('Options', true); ?>
							</div>
						</th>
<!--							<th class="last">
							<div class="content">
								<?php echo __('Force as default on photo?', true); ?>
							</div>
						</th>-->
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
								// DREW TODO - finish doing these helps below
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
									$turnaround_help_code = 'data-step="8" data-intro="'.__('This is the default turnaround time or how long it will take in total to send the print and will be used on all images of this type if the default setting is selecting.', true).'" data-position="left"';
									$force_help_code = 'data-step="9" data-intro="'.__('Selecting the Force setting will over ride any settings that you had and will not let you edit the price/shipping on the images in the photo tab. It will use the default settings for all images but you will not be able to edit them. Only here.', true).'" data-position="left"';
								}


								$curr_avail_sizes_photo_print_type_id_str = '';
								if (!empty($photo_avail_size['PhotoAvailSizesPhotoPrintType']['id'])) {
									$curr_avail_sizes_photo_print_type_id_str = "data-avail_sizes_photo_print_type_id='{$photo_avail_size['PhotoAvailSizesPhotoPrintType']['id']}'";
								}
							?>
							<tr <?php echo $curr_avail_sizes_photo_print_type_id_str; ?>>
								<td class="first" <?php echo $td_help_code; ?>>
									<input class="available_checkbox" type="checkbox" name="data[PhotoAvailSizesPhotoPrintType][non_pano_available]" <?php if ($photo_avail_size['PhotoAvailSizesPhotoPrintType']['non_pano_available'] == 1): ?>checked="checked"<?php endif; ?> /><br />
								</td>
								<td <?php echo $size_help_code; ?>>
									<input class="disablable" type="hidden" value="<?php echo $photo_avail_size['PhotoAvailSize']['id']; ?>" name="data[PhotoAvailSizesPhotoPrintType][photo_avail_size_id]" />
									<?php echo $photo_avail_size['PhotoAvailSize']['short_side_length'];  ?>&Prime; x long-side&Prime;
									<br />
									<span style="font-size: 15px; margin-left: 0px; border-left: 0px; margin-top: 15px;">(<?php echo __('Non-Panoramic', true); ?>)</span>
								</td>
								<?php /*<td style="width: 100px;" <?php echo $pano_help_code; ?>>
									<?php echo __('No', true); ?>
									<?php if (!empty($photo_avail_size['PhotoAvailSizesPhotoPrintType']['id'])): ?>
										<input type="hidden" value="<?php echo $photo_avail_size['PhotoAvailSizesPhotoPrintType']['id']; ?>" name="data[PhotoAvailSizesPhotoPrintType][id]" />
									<?php endif; ?>
									<input class="disablable" type="hidden" value="<?php echo $photo_avail_size['PhotoAvailSize']['id']; ?>" name="data[PhotoAvailSizesPhotoPrintType][photo_avail_size_id]" />
								</td>*/ ?>
								<?php /*<td <?php echo $default_help_code; ?>>
									<input class="disablable" type="checkbox" name="data[PhotoAvailSizesPhotoPrintType][non_pano_global_default]" <?php if ($photo_avail_size['PhotoAvailSizesPhotoPrintType']['non_pano_global_default'] == 1): ?>checked="checked"<?php endif; ?> /><br />
								</td> */ ?>
								<td class="price_width" <?php echo $price_help_code; ?>>
									<span class="subitem_container">
										<label><?php echo __('Price', true); ?></label><br />
										<span><span>$</span><input class="disablable money_format" type="text" name="data[PhotoAvailSizesPhotoPrintType][non_pano_price]" value="<?php if (!empty($photo_avail_size['PhotoAvailSizesPhotoPrintType']['non_pano_price']) && $photo_avail_size['PhotoAvailSizesPhotoPrintType']['non_pano_price'] != '0.00') echo $photo_avail_size['PhotoAvailSizesPhotoPrintType']['non_pano_price']; ?>" /></span>
									</span>
									<span class="subitem_container">
										<label><?php echo __('Shipping Price', true); ?></label><br />
										<span><span>$</span><input class="disablable money_format" type="text" name="data[PhotoAvailSizesPhotoPrintType][non_pano_shipping_price]"value="<?php if (!empty($photo_avail_size['PhotoAvailSizesPhotoPrintType']['non_pano_shipping_price']) && $photo_avail_size['PhotoAvailSizesPhotoPrintType']['non_pano_shipping_price'] != '0.00') echo $photo_avail_size['PhotoAvailSizesPhotoPrintType']['non_pano_shipping_price']; ?>" /></span>
									</span>
								</td>
								<?php /*<td class="price_width" <?php echo $shipping_price_help_code; ?>>
								</td>
								<td class="text_width" <?php echo $turnaround_help_code; ?>>
								</td>*/ ?>
								<td class="last" <?php echo $force_help_code; ?>>
									<span class="subitem_container">
										<label><?php echo __('Turnaround Time', true); ?></label><br />
										&nbsp;&nbsp;<input class="default_turnaround_time disablable" type="text" name="data[PhotoAvailSizesPhotoPrintType][non_pano_custom_turnaround]" value="<?php if (!empty($photo_avail_size['PhotoAvailSizesPhotoPrintType']['non_pano_custom_turnaround'])) { echo $photo_avail_size['PhotoAvailSizesPhotoPrintType']['non_pano_custom_turnaround']; } else { echo $print_type_turnaround_time; }; ?>" /><br />
									</span>
									<span class="subitem_container">
										<input class="disablable" type="checkbox" name="data[PhotoAvailSizesPhotoPrintType][non_pano_global_default]" <?php if ($photo_avail_size['PhotoAvailSizesPhotoPrintType']['non_pano_global_default'] == 1): ?>checked="checked"<?php endif; ?> />
										<label><?php echo __('Use on Photos by Default?', true); ?></label>
									</span>
									<span class="subitem_container">
										<input class="disablable" type="checkbox" name="data[PhotoAvailSizesPhotoPrintType][non_pano_force_settings]" <?php if ($photo_avail_size['PhotoAvailSizesPhotoPrintType']['non_pano_force_settings'] == 1): ?>checked="checked"<?php endif; ?> />
										<label><?php echo __('Force Settings on All Photos?', true); ?></label>
									</span>
								</td>
							</tr>
						<?php endif; ?>
						<?php if ($has_pano): ?>
							<tr>
								<td class="first">
									<input class="available_checkbox" type="checkbox" name="data[PhotoAvailSizesPhotoPrintType][pano_available]" <?php if ($photo_avail_size['PhotoAvailSizesPhotoPrintType']['pano_available'] == 1): ?>checked="checked"<?php endif; ?> />
								</td>
								<td>
									<?php if (!empty($photo_avail_size['PhotoAvailSizesPhotoPrintType']['id'])): ?>
										<input type="hidden" value="<?php echo $photo_avail_size['PhotoAvailSizesPhotoPrintType']['id']; ?>" name="data[PhotoAvailSizesPhotoPrintType][id]" />
									<?php endif; ?>
									<input class="disablable" type="hidden" value="<?php echo $photo_avail_size['PhotoAvailSize']['id']; ?>" name="data[PhotoAvailSizesPhotoPrintType][photo_avail_size_id]" />
									<?php echo $photo_avail_size['PhotoAvailSize']['short_side_length']; ?>&Prime; x long-side&Prime;
									<br />
									<span style="font-size: 15px; margin-left: 0px; border-left: 0px; margin-top: 15px;">(<?php echo __('Panoramic', true); ?>)</span>
								</td>
								<?php /*<td style="width: 100px;">
									<?php __('Yes'); ?>
								</td>*/ ?>
								<?php /*<td>
									<input class="disablable" type="checkbox" name="data[PhotoAvailSizesPhotoPrintType][pano_global_default]" <?php if ($photo_avail_size['PhotoAvailSizesPhotoPrintType']['pano_global_default'] == 1): ?>checked="checked"<?php endif; ?> />
								</td>*/ ?>
								<td class="price_width">
									<span class="subitem_container">
										<label><?php echo __('Price', true); ?></label><br />
										<span><span>$</span><input class="disablable money_format" type="text" name="data[PhotoAvailSizesPhotoPrintType][pano_price]"value="<?php if (!empty($photo_avail_size['PhotoAvailSizesPhotoPrintType']['pano_price']) && $photo_avail_size['PhotoAvailSizesPhotoPrintType']['pano_price'] != '0.00') echo $photo_avail_size['PhotoAvailSizesPhotoPrintType']['pano_price']; ?>" /></span>
									</span>
									<span class="subitem_container">
										<label><?php echo __('Shipping Price', true); ?></label><br />
										<span><span>$</span><input class="disablable money_format" type="text" name="data[PhotoAvailSizesPhotoPrintType][pano_shipping_price]"value="<?php if (!empty($photo_avail_size['PhotoAvailSizesPhotoPrintType']['pano_shipping_price']) && $photo_avail_size['PhotoAvailSizesPhotoPrintType']['pano_shipping_price'] != '0.00') echo $photo_avail_size['PhotoAvailSizesPhotoPrintType']['pano_shipping_price']; ?>" /></span>
									</span>
								</td>
								<?php /*<td class="price_width">
									<span><span>$</span><input class="disablable money_format" type="text" name="data[PhotoAvailSizesPhotoPrintType][pano_shipping_price]"value="<?php if (!empty($photo_avail_size['PhotoAvailSizesPhotoPrintType']['pano_shipping_price']) && $photo_avail_size['PhotoAvailSizesPhotoPrintType']['pano_shipping_price'] != '0.00') echo $photo_avail_size['PhotoAvailSizesPhotoPrintType']['pano_shipping_price']; ?>" /></span>
								</td>
								<td class="text_width">
									<input class="disablable" type="input" name="data[PhotoAvailSizesPhotoPrintType][pano_custom_turnaround]" value="<?php if (!empty($photo_avail_size['PhotoAvailSizesPhotoPrintType']['pano_custom_turnaround'])) { echo $photo_avail_size['PhotoAvailSizesPhotoPrintType']['pano_custom_turnaround']; } else { echo $print_type_turnaround_time; } ?>" />
								</td>*/ ?>
								<td class="last" <?php echo $force_help_code; ?>>
									<span class="subitem_container">
										<label><?php echo __('Turnaround Time', true); ?></label><br />
										&nbsp;&nbsp;<input class="disablable" type="text" name="data[PhotoAvailSizesPhotoPrintType][pano_custom_turnaround]" value="<?php if (!empty($photo_avail_size['PhotoAvailSizesPhotoPrintType']['pano_custom_turnaround'])) { echo $photo_avail_size['PhotoAvailSizesPhotoPrintType']['pano_custom_turnaround']; } else { echo $print_type_turnaround_time; } ?>" />
									</span>
									<span class="subitem_container">
										<input class="disablable" type="checkbox" name="data[PhotoAvailSizesPhotoPrintType][pano_global_default]" <?php if ($photo_avail_size['PhotoAvailSizesPhotoPrintType']['pano_global_default'] == 1): ?>checked="checked"<?php endif; ?> />
										<label><?php echo __('Use by Default?', true); ?></label>
									</span>
									<span class="subitem_container">
										<input class="disablable" type="checkbox" name="data[PhotoAvailSizesPhotoPrintType][pano_force_settings]" <?php if ($photo_avail_size['PhotoAvailSizesPhotoPrintType']['pano_force_settings'] == 1): ?>checked="checked"<?php endif; ?> />
										<label><?php echo __('Force Settings on All Photos?', true); ?></label>
									</span>
								</td>
							</tr>
						<?php endif; ?>
					<?php $count++; endforeach; ?>
				</tbody>
			</table>
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