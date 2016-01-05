<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('#print_types_list tbody').sortable({
			items: 'tr.photo_print_type_item',
			handle : '.reorder_print_type_grabber',
			update : function(event, ui) {
				var context = this;
				jQuery(context).sortable('disable');
				
				// figure the the new position of the dragged element
				var photo_print_type_id = jQuery(ui.item).attr('photo_print_type_id');
				var newPosition = position_of_element_among_siblings(jQuery('.photo_print_type_item', this), jQuery(ui.item));
				
				jQuery.ajax({
					type: 'post',
					url: '/admin/ecommerces/ajax_set_print_type_order/'+photo_print_type_id+'/'+newPosition+'/',
					data: {},
					success: function(data) {
						if (data.code != 1) {
							// DREW TODO - maybe revert the draggable back to its start position here
						}
					},
					complete: function() {
						jQuery(context).sortable('enable');
					},
					error: function() {
						//console.log ("this is where an error would occure");
					},
					dataType: 'json'
				});
			}
		}).disableSelection();
		
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

<h1><?php echo __('Available Print Types', true); ?>
	<div class="custom_ui right">
		<a href="/admin/ecommerces/manage_print_sizes">
			<div class="add_button">
				<div class="content"><?php echo __('Manage Default Print Sizes', true); ?></div><div class="right_arrow_lines icon-arrow-01"><div></div></div>
			</div>
		</a>
	</div>
</h1>
<p><?php echo __('The print types are the names of the kinds of prints you offer (e.g. canvas wrap, wood mount, aluminum, framed, poster, Fuji Crystal Archive paper, etc). You can have multiple print types per image if you offer more than one option.', true); ?></p>
<?php 
//	print_r($overlord_account_info['print_fulfillers']);
//	die();
?>




<div class="right">
	<div class="add_gallery_element add_element custom_ui" style="margin: 5px; margin-bottom: 15px;">
		<select id="choose_print_fulfiller">
			<optgroup label="Manual Printing">
				<option value="self" style="margin-bottom: 20px !important;"><?php echo __('Process Orders Manually', true); ?></option>
			</optgroup>
			<optgroup label="Automatic Printing Labs"></optgroup>
			<?php foreach ($overlord_account_info['print_fulfillers'] as $section => $print_fulfiller): ?>
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
		
		<?php foreach ($overlord_account_info['print_fulfillers'] as $type_section => $type_print_fulfiller): ?>
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
		
		<div id="add_print_type_button" class="add_button">
			<div class="content"><?php echo __('Go', true); ?></div><div class="right_arrow_lines icon-arrow-01"><div></div></div>
		</div>
		<div style="clear: both;"></div>
	</div>
</div>
<div class="clear"></div>

<div class="table_container" data-step="1" data-intro="<?php echo __('This area shows all the print types that have been created.', true); ?>" data-position="top">
	<div class="fade_background_top"></div>
		<div class="table_top"></div>
	<table id="print_types_list" class="list">
		<thead>
			<tr> 
				<th class="" colspan="2">
					<div class="content one_line">
						<?php echo __('Print Types', true); ?>
					</div>
				</th> 
				<th class="last actions_call"></th>
			</tr> 
		</thead>
		<tbody>
			<tr class="spacer"><td colspan="3"></td></tr>

			<?php if (empty($photo_print_types)): ?>
				<tr class="first last">
					<td class="first last" colspan="3">
						<div class="rightborder"></div>
						<span><?php echo __('You have not added any print types yet.', true); ?></span>
					</td>
				</tr>
			<?php endif; ?>
			<?php // KENT TODO - fix the below as they are in a foreach ?>
			<?php foreach($photo_print_types as $photo_print_type): ?> 
				<tr class="photo_print_type_item" photo_print_type_id=" <?php echo $photo_print_type['PhotoPrintType']['id']; ?>">
					<td class="print_type_id first table_width_reorder_icon"><div class="reorder_print_type_grabber reorder_grabber icon-position-01"/> </td> 
					<td class="print_type">
						<div class="rightborder"></div>
						<span><?php echo $photo_print_type['PhotoPrintType']['print_name']; ?></span>
					</td>
					<td class="table_actions last">
						<span class="custom_ui">
							<?php if ($photo_print_type['PhotoPrintType']['print_fulfillment_type'] == 'self'): ?>
								<a href="/admin/ecommerces/add_print_type_and_pricing/<?php echo $photo_print_type['PhotoPrintType']['id']; ?>/"><div class="add_button"><div class="content"><?php echo __('Edit', true); ?></div><div class="right_arrow_lines icon-arrow-01"><div></div></div></div></a>
							<?php else: ?>
								<a href="/admin/ecommerces/add_automatic_print_type_and_pricing/<?php echo $photo_print_type['PhotoPrintType']['print_fulfiller_id']; ?>/<?php echo $photo_print_type['PhotoPrintType']['print_fulfiller_print_type_id']; ?>/<?php echo $photo_print_type['PhotoPrintType']['id']; ?>/"><div class="add_button"><div class="content"><?php echo __('Edit', true); ?></div><div class="right_arrow_lines icon-arrow-01"><div></div></div></div></a>
							<?php endif; ?>
							<a class="delete_link" href="/admin/ecommerces/delete_print_type/<?php echo $photo_print_type['PhotoPrintType']['id']; ?>/"><div class="add_button icon icon_close"><div class="content icon-close-01"></div></div></a>
						</span>
					</td>
				</tr>
			<?php endforeach; ?> 
		</tbody>
	</table>
</div>

<?php ob_start(); ?>
<ol>
	<li>This page just list available print types you have created (and has a button to add them)</li>
	<li><a href="/img/admin_screenshots/manage_print_types.jpg" target="_blank">screenshot</a></li>
	<li>Things to remember
		<ol>
			<li>This page will need explanation text at the top (or somewhere)</li>
			<li>We need a state for before the user has added any print types (with help making it obvious that they need to add one)</li>
			<li>Don't forget a design for the add new print type page</li>
			<li>This page needs a flash message</li>
		</ol>
	</li>
</ol>
<?php
$html = ob_get_contents();
ob_end_clean();
	echo $this->Element('admin/richard_notes', array(
	'html' => $html
)); ?>