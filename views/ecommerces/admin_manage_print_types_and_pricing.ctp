<script src="/js/angular_1.2.22/app/js/app.js"></script>
<script src="/js/angular_1.2.22/app/js/controllers/avail_print_types.js"></script>
<script src="/js/angular_1.2.22/app/js/services.js"></script>
<script src="/js/angular_1.2.22/app/js/directives.js"></script>

<script>
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


<div ng-app="fotomatterApp" ng-controller="AvailPrintTypesCtrl" ng-model-options="{ debounce: { 'default': 750, 'blur': 0 } }">
	<h1><?php echo __('Add/Edit Print Types', true); ?>
		<div id="help_tour_button" class="custom_ui"><?php //echo $this->Element('/admin/get_help_button'); ?></div>
		<div class="custom_ui right">
			<a href="/admin/ecommerces/manage_print_sizes">
				<div class="add_button">
					<div class="content"><?php echo __('Manage Default Print Sizes', true); ?></div><div class="right_arrow_lines icon-arrow-01"><div></div></div>
				</div>
			</a>
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
	</h1>
	<p><?php echo __('Create print types, add sizes available to those print types, and set default pricing and shipping. To change pricing from the default structure on one specific photo, go to &ldquo;Pricing Override&rdquo; under the Photos tab.', true); ?></p>

	
	<br /><br /><br />
	<div class="ng-hide" ng-show="open_print_type != undefined">
		<?php echo $this->Element('admin/ecommerce/angular_add_print_type_and_pricing'); ?>
	</div>
	
	
	<div class="clear"></div>
	<div class="dynamic_list">
		<div class="table_container" data-step="1" data-intro="<?php echo __('This area shows all the print types that have been created.', true); ?>" data-position="top">
			<div class="fade_background_top"></div>
			<div class="table_top"></div>
			<table id="print_types_list" class="list" ui-sortable="printTypeSortableOptions">
				<tbody>
					<tr class="spacer"><td colspan="1"></td></tr>
					<tr class="first last ng-hide" ng-show="photo_print_types == undefined">
						<td class="first last" colspan="1" style="text-align: center;">
							<span>LOADING</span>
						</td>
					</tr>

					<tr class="first last ng-hide" ng-show="photo_print_types.length == 0">
						<td class="first last" colspan="1">
							<span>You don't have any print types</span>
						</td>
					</tr>
					
					<tr ng-repeat="photo_print_type in photo_print_types" class="sortable" <?php /* ng-class="{'current': last_open_gallery_id == photo_gallery.PhotoGallery.id}" */ ?> item_id="{{photo_print_type.PhotoPrintType.id}}">
						<td class="gallery_name gallery_id first last">
							<table>
								<tbody>
									<tr>
										<td class="first">
											<div class="reorder_grabber icon-position-01" />
										</td>
										<td class="last">
											<span ng-click="editPrintType(photo_print_type)">{{photo_print_type.PhotoPrintType.print_name}}</span>
										</td>
									</tr>
									<tr>
										<td colspan="2">
											<span class="custom_ui">
												<div 
													<?php /*ng-class="{'selected': last_open_gallery_id == photo_gallery.PhotoGallery.id && (upload_to_gallery == null || upload_to_gallery == 'empty'), 'disabled': uploading_photos == true}" */ ?>
													class="add_button icon" 
													ng-click="editPrintType(photo_print_type)"
													<?php /*ng-click="view_gallery(photo_gallery.PhotoGallery.id, 0, photo_gallery.PhotoGallery.type)" */ ?>
												>
													<div class="content icon-cogWheel" <?php /*ng-show="photo_gallery.PhotoGallery.type == 'standard'"*/ ?>></div>
												</div>
												<span <?php /*ng-class="{'disabled': uploading_photos == true}"*/ ?>>
													<span 
														ng-click="deletePrintType(photo_print_type)"
														confirm-delete confirm-message="Do you really want to delete the print type?" 
														confirm-title="Really delete print type?" 
														confirm-button-title="Delete"
													>
														<div class="add_button icon icon_close"><div class="content icon-close-01"></div></div>
													</span>
												</span>
											</span>
										</td>
									</tr>
								</tbody>
							</table>
						</td>
					</tr>
				</tbody>
				
				
				<?php /*
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
										<a href="/admin/ecommerces/add_print_type_and_pricing/<?php echo $photo_print_type['PhotoPrintType']['id']; ?>/"><div class="add_button"><div class="content"><?php echo __('Edit', true); ?></div><div class="right_arrow_lines icon-arrow-01"><div></div></div></div></a>
									<a class="delete_link" href="/admin/ecommerces/delete_print_type/<?php echo $photo_print_type['PhotoPrintType']['id']; ?>/"><div class="add_button icon icon_close"><div class="content icon-close-01"></div></div></a>
								</span>
							</td>
						</tr>
					<?php endforeach; ?> 
				</tbody>
				*/ ?>
				
				
			</table>
		</div>
	</div>
</div>