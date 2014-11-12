<script type="text/javascript">
	function setRowValues(attr_name, row_tr) {
		jQuery('td', row_tr).each(function() {
			var key_value = jQuery(this).attr(attr_name);
			if (key_value !== undefined) {
				var checkbox = jQuery('input:checkbox', this);
				if (checkbox.length > 0) {
					if (key_value === '1') {
						checkbox.attr('checked', 'checked');
					} else {
						checkbox.removeAttr('checked');
					}
				}

				var input = jQuery('input:text', this);
				if (input.length > 0) {
					input.val(key_value);
				}
			}
		});
	}

	function saveRowValues(attr_name, row_tr) {
		jQuery('td', row_tr).each(function() {
			var key_value = jQuery(this).attr(attr_name);
			if (key_value !== undefined) {
				var checkbox = jQuery('input:checkbox', this);
				if (checkbox.length > 0) {
					if (checkbox.is(':checked')) {
						jQuery(this).attr(attr_name, '1');
					} else {
						jQuery(this).attr(attr_name, '0');
					}
				}

				var input = jQuery('input:text', this);
				if (input.length > 0) {
					jQuery(this).attr(attr_name, input.val());
				}
			}
		});
	}


	jQuery(document).ready(function() {
		$('#image_sellable_prints .money_format').priceFormat({
			prefix: '',
			thousandsSeparator: ''
		});


		// unlock the row
		jQuery('#image_sellable_prints .lock_img').click(function() {
			if (!jQuery(this).hasClass('unlockable')) {
				jQuery(this).parent().find('.unlock_img').css('display', 'inline-block');
				jQuery(this).css('display', 'none');
				jQuery(this).parent().find('.override_for_photo').val('1');
				setRowValues('current',  jQuery(this).closest('tr'));
				jQuery(this).closest('tr').find('.disablable').removeClass('opacity_50');
				jQuery(this).closest('tr').addClass('unlocked');
			} else {
				jQuery.foto('alert', '<?php echo __('This row is unlockable because of a global setting in Manage Print Types and Pricing.', true); ?>');
			}
		});

		// lock the row
		jQuery('#image_sellable_prints .unlock_img').click(function() {
			jQuery(this).parent().find('.lock_img').css('display', 'inline-block');
			jQuery(this).css('display', 'none');
			jQuery(this).parent().find('.override_for_photo').val('0');
			var closest_tr = jQuery(this).closest('tr');
			saveRowValues('current', closest_tr);
			setRowValues('default', closest_tr);
			jQuery(this).closest('tr').find('.disablable').addClass('opacity_50');
			jQuery(this).closest('tr').removeClass('unlocked');
		});
	});
</script>



<div id="image_sellable_prints">
	<table class="list">
		<thead>
			<tr>
				<th class="first">
					<div class="content one_line">
						<?php echo __('Override Global Default', true); ?>
					</div>
				</th>
				<th class="photo_use_sizes">
					<div class="content one_line">
						<?php echo __('Photo Uses Size', true); ?>
					</div>
				</th>
				<th>
					<div class="content one_line">
						<?php echo __('Print Type and Size', true); ?>
					</div>
				</th>
				<th>
					<div class="content">
						<?php echo __('Price', true); ?>
					</div>
				</th>
				<th>
					<div class="content one_line">
						<?php echo __('Shipping Price', true); ?>
					</div>
				</th>
				<th class="last">
					<div class="content one_line">
						<?php echo __('Turnaround Time', true); ?>
					</div>
				</th>
			</tr>
		</thead>
		<tbody>
			<tr class="spacer"><td colspan="6"></td></tr>
			<?php if (empty($photo_sellable_prints)): ?>
				<tr>
					<td colspan="6" class="custom_ui" style="text-align: center;">
						<div class="add_button highlight add_feature_button" type="submit" ref_feature_name="basic_shopping_cart" style="margin-top: 10px;">
							<div class="content" style="font-size: 18px;"><?php echo __('Add Ecommerce', true); ?></div>
							<div class="right_arrow_lines icon-arrow-01"><div></div></div>
						</div>
					</td>
				</tr>
			<?php endif; ?>
			<?php $count = 0; foreach ($photo_sellable_prints as $photo_sellable_print): ?>
				<?php 
					$override_for_photo = $photo_sellable_print['CurrentPrintData']['override_for_photo'];
					$force_defaults = $photo_sellable_print['DefaultPrintData']['force_defaults']; 
					if ($force_defaults === '1') {
						$override_for_photo = '0';
					}
				?>
				<tr class="<?php if($override_for_photo === '1'): ?> unlocked <?php endif; ?>">
					<td class="first">
						<input class="override_for_photo" type="hidden" name="data[PhotoSellablePrint][<?php echo $count; ?>][override_for_photo]" value="<?php echo $override_for_photo; ?>" />
						<i class="icon-priceLock-01-01 lock_img <?php if ($force_defaults === '1'): ?>unlockable<?php endif; ?>" style="<?php if($override_for_photo === '1'): ?>display: none;<?php endif; ?>"></i>
						<i class="icon-priceUnLock-01 unlock_img" style="<?php if($override_for_photo === '1'): ?>display: inline-block;<?php endif; ?>"></i>

						<input name="data[PhotoSellablePrint][<?php echo $count; ?>][photo_avail_sizes_photo_print_type_id]" type="hidden" value="<?php echo $photo_sellable_print['PrintTypeJoin']['id']; ?>" />
						<input name="data[PhotoSellablePrint][<?php echo $count; ?>][photo_id]" type="hidden" value="<?php echo $this->data['Photo']['id']; ?>" />
						<?php if (isset($photo_sellable_print['PhotoSellablePrint']['id'])): ?>
							<input name="data[PhotoSellablePrint][<?php echo $count; ?>][id]" type="hidden" value="<?php echo $photo_sellable_print['PhotoSellablePrint']['id']; ?>" />
						<?php endif; ?>
					</td>
					<td class="input photo_use_sizes" default="<?php echo $photo_sellable_print['DefaultPrintData']['default_available']; ?>" current="<?php echo $photo_sellable_print['CurrentPrintData']['available']; ?>">
						<div class="rightborder"></div>
						<div class="disablable <?php if ($override_for_photo === '0'): ?>opacity_50<?php endif; ?>">
							<input type="checkbox"  name="data[PhotoSellablePrint][<?php echo $count; ?>][available]" <?php if ($photo_sellable_print['CurrentPrintData']['available'] === '1'): ?>checked="checked"<?php endif; ?> default="<?php echo $photo_sellable_print['DefaultPrintData']['default_available']; ?>" custom="<?php echo isset($photo_sellable_print['PhotoSellablePrint']['override_for_photo']) ? $photo_sellable_print['PhotoSellablePrint']['override_for_photo'] : 0 ; ?>" />
							<?php /*<input type="hidden"  name="data[PhotoSellablePrint][<?php echo $count; ?>][defaults][available]" value="<?php echo $photo_sellable_print['DefaultPrintData']['default_available']; ?>"  /> */ // this is turned off so that changes to availability will always save ?>
						</div>
					</td>
					<td>
						<div class="disablable <?php if ($override_for_photo === '0'): ?>opacity_50<?php endif; ?>">
							<?php echo $photo_sellable_print['PhotoPrintType']['print_name']; ?> &mdash; <?php echo $photo_sellable_print['DefaultPrintData']['short_side_inches']; ?>" x <?php echo $photo_sellable_print['DefaultPrintData']['long_side_feet_inches']; ?>
						</div>
					</td>
					<td class="input" default="<?php echo $photo_sellable_print['DefaultPrintData']['price']; ?>" current="<?php echo $photo_sellable_print['CurrentPrintData']['price']; ?>">
						<div class="disablable <?php if ($override_for_photo === '0'): ?>opacity_50<?php endif; ?>">
							<span class="money_symbol">$</span><input class="money_format" value="<?php echo $photo_sellable_print['CurrentPrintData']['price']; ?>" name="data[PhotoSellablePrint][<?php echo $count; ?>][price]" type="text" />
							<input type="hidden"  name="data[PhotoSellablePrint][<?php echo $count; ?>][defaults][price]" value="<?php echo $photo_sellable_print['DefaultPrintData']['price']; ?>"  />
						</div>
					</td>
					<td class="input" default="<?php echo $photo_sellable_print['DefaultPrintData']['shipping_price']; ?>" current="<?php echo $photo_sellable_print['CurrentPrintData']['shipping_price']; ?>">
						<div class="disablable <?php if ($override_for_photo === '0'): ?>opacity_50<?php endif; ?>">
							<span class="money_symbol">$</span><input class="money_format" value="<?php echo $photo_sellable_print['CurrentPrintData']['shipping_price']; ?>" name="data[PhotoSellablePrint][<?php echo $count; ?>][shipping_price]" type="text" />
							<input type="hidden"  name="data[PhotoSellablePrint][<?php echo $count; ?>][defaults][shipping_price]" value="<?php echo $photo_sellable_print['DefaultPrintData']['shipping_price']; ?>"  />
						</div>
					</td>
					<td class="input last" default="<?php echo $photo_sellable_print['DefaultPrintData']['custom_turnaround']; ?>" current="<?php echo $photo_sellable_print['CurrentPrintData']['custom_turnaround']; ?>">
						<div class="disablable <?php if ($override_for_photo === '0'): ?>opacity_50<?php endif; ?>">
							<input value="<?php echo $photo_sellable_print['CurrentPrintData']['custom_turnaround']; ?>" name="data[PhotoSellablePrint][<?php echo $count; ?>][custom_turnaround]" type="text" />
							<input type="hidden"  name="data[PhotoSellablePrint][<?php echo $count; ?>][defaults][custom_turnaround]" value="<?php echo $photo_sellable_print['DefaultPrintData']['custom_turnaround']; ?>"  />
						</div>
					</td>
				</tr>
			<?php $count++; endforeach; ?>
		</tbody>
	</table>
</div>
<div class="photo_details_save_button save_button"><div class="content">Save</div></div>
