<?php /*<script>
	jQuery(document).ready(function() {
//		jQuery('#print_types_list tbody').sortable({
//			items: 'tr.photo_print_type_item',
//			handle : '.reorder_print_type_grabber',
//			update : function(event, ui) {
//				var context = this;
//				jQuery(context).sortable('disable');
//				
//				// figure the the new position of the dragged element
//				var photo_print_type_id = jQuery(ui.item).attr('photo_print_type_id');
//				var newPosition = position_of_element_among_siblings(jQuery('.photo_print_type_item', this), jQuery(ui.item));
//				
//				jQuery.ajax({
//					type: 'post',
//					url: '/admin/ecommerces/ajax_set_print_type_order/'+photo_print_type_id+'/'+newPosition+'/',
//					data: {},
//					success: function(data) {
//						if (data.code != 1) {
//							// DREW TODO - maybe revert the draggable back to its start position here
//						}
//					},
//					complete: function() {
//						jQuery(context).sortable('enable');
//					},
//					error: function() {
//						//console.log ("this is where an error would occure");
//					},
//					dataType: 'json'
//				});
//			}
//		}).disableSelection();
		
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

	<select id="choose_print_fulfiller">
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

	<?php foreach ($print_fulfillers as $type_section => $type_print_fulfiller): ?>
		<?php if ($type_section == 'preferred'): ?>
			<?php //print_r($type_print_fulfiller);  die('suckit'); ?>
			<?php foreach ($type_print_fulfiller as $printer_data): ?>
				<?php if (!empty($printer_data['PrintFulfillerPrintType'])): ?>
					<select class="printer_print_type" data-print_fulfiller_id="<?php echo $printer_data['PrintFulfiller']['id']; ?>">
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
						<select class="printer_print_type" data-print_fulfiller_id="<?php echo $printer_data['PrintFulfiller']['id']; ?>">
							<?php foreach($printer_data['PrintFulfillerPrintType'] as $printer_print_type): ?>
								<option value="<?php echo $printer_print_type['id']; ?>"><?php echo $printer_print_type['name']; ?> Print</option>
							<?php endforeach; ?>
						</select>
					<?php endif; ?>
				<?php endforeach; ?>
			<?php endforeach; ?>
		<?php endif; ?>
	<?php endforeach; ?>