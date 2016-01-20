<?php /*<script>
	jQuery(document).ready(function() {
		
		jQuery('#choose_print_fulfiller').change(function() {
			var selected_printer_id = jQuery(this).val();
//			console.log('=================================');
//			console.log(selected_printer_id);
//			console.log('=================================');
			if (selected_printer_id != '') {
				var print_type_selector = jQuery('select.printer_print_type[data-print_fulfiller_id=' + selected_printer_id + ']' );
				jQuery('select.printer_print_type').removeClass('current');
				if (print_type_selector.length > 0) {
					print_type_selector.addClass('current');
				} else {
					console.log('failed!');
				}
			}
		});
		
		jQuery('#add_print_type_button').click(function() {
			var selected_printer_id = jQuery('#choose_print_fulfiller').val();
			var selected_print_type_id = '';
			if (selected_printer_id != '') {
				selected_print_type_id = jQuery('select.printer_print_type[data-print_fulfiller_id=' + selected_printer_id + ']' ).val();
				if (typeof selected_print_type_id == 'undefined') {
					selected_print_type_id = '';
				}
			}
			
			
			/////////////////////////////////////////////////////////////////////////////////////////////////
			// error checking - to see if they chose valid options
			if (selected_printer_id == '') {
				jQuery.foto('alert', '<?php echo __('Before you can create a print type choose an automatic (dropship) printer or choose to process orders for this print type yourself.', true); ?>');
				return false;
			}
			if (selected_printer_id != 'self' && selected_print_type_id == '') {
				jQuery.foto('alert', '<?php echo __('Choose a print type for the selected automatic (dropship) printer.', true); ?>');
				return false;
			}
			

			///////////////////////////////////////////////////////////////////////////////
			// actually go to the add print type page
			if (selected_printer_id == 'self') {
				window.location = '/admin/ecommerces/add_print_type_and_pricing/';
			} else {
				window.location = '/admin/ecommerces/add_automatic_print_type_and_pricing/' + selected_printer_id + '/' + selected_print_type_id + '/'
			}
			
			
			return true;
		});
	});
</script>
 * 
 * 
 */ ?>

<div class="select">
	<label for="billing_firstname">Choose a Printing Method</label>
	<select id="choose_print_fulfiller" ng-change="choose_print_fulfiller()" ng-model="print_fulfiller_id" style="margin-left: 15px;">
		<optgroup label="Manual Printing">
			<option value="self" style="margin-bottom: 20px !important;"><?php echo __('Process Orders Manually', true); ?></option>
		</optgroup>
		<optgroup label="Automatic Printing Labs"></optgroup>
		<?php foreach ($print_fulfillers as $section => $print_fulfiller): ?>
			<?php if ($section == 'preferred'): ?>
				<?php if (!empty($print_fulfiller)): ?>
					<optgroup label="&nbsp;&nbsp;&nbsp;&nbsp;<?php echo __('Preferred', true); ?>">
				<?php endif; ?>
					<?php $count = 0; foreach($print_fulfiller as $printer_data): ?>
						<?php echo $this->Element('admin/ecommerce/print_fulfiller_option',  array(
							'printer_data' => $printer_data,
							'prefix' => '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
							'selected' => ($count == 0)
						)); ?>
					<?php $count++; endforeach; ?>
				<?php if (!empty($print_fulfiller)): ?>
					</optgroup>
				<?php endif; ?>
			<?php else: ?>
					<?php foreach($print_fulfiller as $state => $state_printers): ?>
						<?php $state_string = $state == 'no_state' ? '' : $state; ?>
						<optgroup label="&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $section; ?> <?php echo $state_string; ?>">
							<?php foreach($state_printers as $printer_data): ?>
								<?php echo $this->Element('admin/ecommerce/print_fulfiller_option',  array(
									'printer_data' => $printer_data,
									'prefix' => '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
								)); ?>
							<?php endforeach; ?>
						</optgroup>
					<?php endforeach; ?>
			<?php endif; ?>
		<?php endforeach; ?>
	</select>
</div>
<div class="select ng-hide" ng-show="print_fulfiller_id!='self'">
	<label for="billing_firstname">Choose a Print Type</label>
	<?php foreach ($print_fulfillers as $type_section => $type_print_fulfiller): ?>
		<?php if ($type_section == 'preferred'): ?>
			<?php foreach ($type_print_fulfiller as $printer_data): ?>
				<?php if (!empty($printer_data['PrintFulfillerPrintType'])): ?>
					<select 
						class="printer_print_type ng-hide" 
						ng-show="print_fulfiller_id=='<?php echo $printer_data['PrintFulfiller']['id']; ?>'" 
						style="margin-left: 15px;"
						ng-init="printer_print_types[<?php echo $printer_data['PrintFulfiller']['id']; ?>].id='<?php echo array_values($printer_data['PrintFulfillerPrintType'])[0]['id']; ?>'" 
						ng-model="printer_print_types[<?php echo $printer_data['PrintFulfiller']['id']; ?>].id"
						ng-change="choose_print_type()"
					>
						<?php foreach($printer_data['PrintFulfillerPrintType'] as $printer_print_type): ?>
							<option value="<?php echo $printer_print_type['id']; ?>"><?php echo $printer_print_type['name']; ?> Print</option>
						<?php endforeach; ?>
					</select>
				<?php endif; ?>
			<?php endforeach; ?>
		<?php else: ?>
			<?php foreach($type_print_fulfiller as $type_printer_data): ?>
				<?php foreach ($type_printer_data as $printer_data): ?>
					<?php if (!empty($printer_data['PrintFulfillerPrintType'])): ?>
						<select 
							class="printer_print_type ng-hide" 
							ng-show="print_fulfiller_id=='<?php echo $printer_data['PrintFulfiller']['id']; ?>'" 
							style="margin-left: 15px;" 
							ng-init="printer_print_types[<?php echo $printer_data['PrintFulfiller']['id']; ?>].id='<?php echo array_values($printer_data['PrintFulfillerPrintType'])[0]['id']; ?>'" 
							ng-model="printer_print_types[<?php echo $printer_data['PrintFulfiller']['id']; ?>].id"
							ng-change="choose_print_type()"
						>
							<?php foreach($printer_data['PrintFulfillerPrintType'] as $printer_print_type): ?>
								<option value="<?php echo $printer_print_type['id']; ?>"><?php echo $printer_print_type['name']; ?> Print</option>
							<?php endforeach; ?>
						</select>
					<?php endif; ?>
				<?php endforeach; ?>
			<?php endforeach; ?>
		<?php endif; ?>
	<?php endforeach; ?>
</div>