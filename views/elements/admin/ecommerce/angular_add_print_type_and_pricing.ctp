<div class="page_content_header">
	<h2 class="ng-hide" ng-show="open_print_type.photo_print_type.PhotoPrintType.print_fulfillment_type == 'self'"><?php echo __('Manually Processed Print Settings', true); ?></h2>
	<h2 class="ng-hide" ng-show="open_print_type.photo_print_type.PhotoPrintType.print_fulfillment_type == 'autofixed'"><?php echo __('Fixed Settings', true); ?></h2>
	<h2 class="ng-hide" ng-show="open_print_type.photo_print_type.PhotoPrintType.print_fulfillment_type == 'autodynamic'"><?php echo __('Dynamic Settings', true); ?></h2>
	<h2 class="ng-hide" ng-show="open_print_type.photo_print_type.PhotoPrintType.print_fulfillment_type == 'automisc'"><?php echo __('Misc Settings', true); ?></h2>
</div>

	<?php 
		$photo_print_type_id_str = '';
//		if (!empty($photo_print_type['PhotoPrintType']['id']) && $photo_print_type['PhotoPrintType']['id'] != '0') {
//			$photo_print_type_id_str = 'data-photo_print_type_id="' . $photo_print_type['PhotoPrintType']['id'] .'"';
//		}
	?>
	<form id="print_types_form" action="" method="post">
		<div id="print_type_settings" <?php echo $photo_print_type_id_str; ?> class="generic_palette_container">
			<div class="fade_background_top"></div>
			<div class="basic_setting_cont no_border">
				<label><?php echo __('Name of Print Type', true); ?></label>
				<div class="theme_setting_inputs_container">
					<input 
						type="text" 
						ng-model="open_print_type.photo_print_type.PhotoPrintType.print_name"
						ng-change="savePrintTypeSetting(open_print_type.photo_print_type)" 
					/>
				</div>
				<div class="theme_setting_description">
					<?php echo __('The name of the type of print you are selling. Examples: canvas wrap, wood mount, aluminum, framed, poster, Fuji Crystal Archive paper, etc.', true); ?> 
				</div>
			</div>
			<div class="basic_setting_cont no_border">
				<label><?php echo __('Estimated Turnaround Time', true); ?></label>
				<div class="theme_setting_inputs_container">
					<input 
						id="print_type_turnaround_time" 
						type="text"
						ng-model="open_print_type.photo_print_type.PhotoPrintType.turnaround_time"
						ng-change="savePrintTypeSetting(open_print_type.photo_print_type, '{{open_print_type.photo_print_type.PhotoPrintType.turnaround_time}}')" 
					/>
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
					</tr>
				</thead>
				<tbody>
					<tr class="spacer"><td colspan="8"></td></tr>
					
					<tr ng-if="0" ng-repeat-start="photo_avail_size in open_print_type.photo_avail_sizes"></tr>
						<tr ng-if="photo_avail_size.PhotoAvailSize.has_non_pano == true">
							<td class="first">
								<input 
									class="available_checkbox" 
									type="checkbox" 
									ng-model="photo_avail_size.PhotoAvailSizesPhotoPrintType.non_pano_available" 
									ng-change="savePrintType(photo_avail_size, $index)" 
									ng-model-options="{}"
								/><br />
							</td>
							<td>
								{{photo_avail_size.PhotoAvailSize.short_side_length}}&Prime; x long-side&Prime;
								<br />
								<span style="font-size: 15px; margin-left: 0px; border-left: 0px; margin-top: 15px;">(<?php echo __('Non-Panoramic', true); ?>)</span>
							</td>
							<td class="price_width">
								<span class="subitem_container">
									<label><?php echo __('Price', true); ?></label><br />
									<span>
										<span>$</span>
										<input
											class="money_format"
											type="text"
											ng-model="photo_avail_size.PhotoAvailSizesPhotoPrintType.non_pano_price"
											ng-change="savePrintType(photo_avail_size, $index)"
											ng-class="{'disabled': photo_avail_size.PhotoAvailSizesPhotoPrintType.non_pano_available != true}"
											ng-disabled="photo_avail_size.PhotoAvailSizesPhotoPrintType.non_pano_available != true"
										/>
									</span>
								</span>
								<span class="subitem_container">
									<label><?php echo __('Shipping Price', true); ?></label><br />
									<span>
										<span>$</span>
										<input 
											class="money_format"
											type="text"
											ng-change="savePrintType(photo_avail_size, $index)" 
											ng-model="photo_avail_size.PhotoAvailSizesPhotoPrintType.non_pano_shipping_price"
											ng-class="{'disabled': photo_avail_size.PhotoAvailSizesPhotoPrintType.non_pano_available != true}"
											ng-disabled="photo_avail_size.PhotoAvailSizesPhotoPrintType.non_pano_available != true"
										/>
									</span>
								</span>
							</td>
							<td class="last" ng-if-end >
								<span class="subitem_container">
									<label><?php echo __('Turnaround Time', true); ?></label><br />
									&nbsp;&nbsp;
									<input
										class="default_turnaround_time" 
										type="text"
										ng-change="savePrintType(photo_avail_size, $index)" 
										ng-model="photo_avail_size.PhotoAvailSizesPhotoPrintType.non_pano_custom_turnaround"
										ng-class="{'disabled': photo_avail_size.PhotoAvailSizesPhotoPrintType.non_pano_available != true}"
										ng-disabled="photo_avail_size.PhotoAvailSizesPhotoPrintType.non_pano_available != true"
									/><br />
								</span>
								<span class="subitem_container">
									<input 
										type="checkbox" 
										ng-change="savePrintType(photo_avail_size, $index)" 
										ng-model="photo_avail_size.PhotoAvailSizesPhotoPrintType.non_pano_global_default" 
										ng-class="{'disabled': photo_avail_size.PhotoAvailSizesPhotoPrintType.non_pano_available != true}"
										ng-disabled="photo_avail_size.PhotoAvailSizesPhotoPrintType.non_pano_available != true"
									/>
									<label><?php echo __('Use on Photos by Default?', true); ?></label>
								</span>
								<span class="subitem_container">
									<input 
										type="checkbox" 
										ng-change="savePrintType(photo_avail_size, $index)" 
										ng-model="photo_avail_size.PhotoAvailSizesPhotoPrintType.non_pano_force_settings" 
										ng-class="{'disabled': photo_avail_size.PhotoAvailSizesPhotoPrintType.non_pano_available != true}"
										ng-disabled="photo_avail_size.PhotoAvailSizesPhotoPrintType.non_pano_available != true"
									/>
									<label><?php echo __('Force Settings on All Photos?', true); ?></label>
								</span>
							</td>
						</tr>
						
						<tr ng-if="photo_avail_size.PhotoAvailSize.has_pano == true">
							<td class="first" ng-if-start="photo_avail_size.PhotoAvailSize.has_pano == true" >
								<input 
									class="available_checkbox" 
									type="checkbox" 
									ng-model="photo_avail_size.PhotoAvailSizesPhotoPrintType.pano_available" 
									ng-change="savePrintType(photo_avail_size, $index)" 
									ng-model-options="{}"
								/><br />
							</td>
							<td>
								{{photo_avail_size.PhotoAvailSize.short_side_length}}&Prime; x long-side&Prime;
								<br />
								<span style="font-size: 15px; margin-left: 0px; border-left: 0px; margin-top: 15px;">(<?php echo __('Panoramic', true); ?>)</span>
							</td>
							<td class="price_width">
								<span class="subitem_container">
									<label><?php echo __('Price', true); ?></label><br />
									<span>
										<span>$</span>
										<input 
											class="money_format" 
											type="text" 
											ng-model="photo_avail_size.PhotoAvailSizesPhotoPrintType.pano_price" 
											ng-change="savePrintType(photo_avail_size, $index)" 
											ng-class="{'disabled': photo_avail_size.PhotoAvailSizesPhotoPrintType.pano_available != true}"
											ng-disabled="photo_avail_size.PhotoAvailSizesPhotoPrintType.pano_available != true"
										/>
									</span>
								</span>
								<span class="subitem_container">
									<label><?php echo __('Shipping Price', true); ?></label><br />
									<span>
										<span>$</span>
										<input 
											class="money_format" 
											type="text" 
											ng-model="photo_avail_size.PhotoAvailSizesPhotoPrintType.pano_shipping_price" 
											ng-change="savePrintType(photo_avail_size, $index)" 
											ng-class="{'disabled': photo_avail_size.PhotoAvailSizesPhotoPrintType.pano_available != true}"
											ng-disabled="photo_avail_size.PhotoAvailSizesPhotoPrintType.pano_available != true"
										/>
									</span>
								</span>
							</td>
							<td class="last" ng-if-end >
								<span class="subitem_container">
									<label><?php echo __('Turnaround Time', true); ?></label><br />
									&nbsp;&nbsp;
									<input 
										class="default_turnaround_time" 
										type="text" 
										ng-model="photo_avail_size.PhotoAvailSizesPhotoPrintType.pano_custom_turnaround" 
										ng-change="savePrintType(photo_avail_size, $index)" 
										ng-class="{'disabled': photo_avail_size.PhotoAvailSizesPhotoPrintType.pano_available != true}"
										ng-disabled="photo_avail_size.PhotoAvailSizesPhotoPrintType.pano_available != true"
									/><br />
								</span>
								<span class="subitem_container">
									<input 
										type="checkbox" 
										ng-model="photo_avail_size.PhotoAvailSizesPhotoPrintType.pano_global_default" 
										ng-change="savePrintType(photo_avail_size, $index)" 
										ng-class="{'disabled': photo_avail_size.PhotoAvailSizesPhotoPrintType.pano_available != true}"
										ng-disabled="photo_avail_size.PhotoAvailSizesPhotoPrintType.pano_available != true"
									/>
									<label><?php echo __('Use on Photos by Default?', true); ?></label>
								</span>
								<span class="subitem_container">
									<input 
										type="checkbox" 
										ng-model="photo_avail_size.PhotoAvailSizesPhotoPrintType.pano_force_settings" 
										ng-change="savePrintType(photo_avail_size, $index)" 
										ng-class="{'disabled': photo_avail_size.PhotoAvailSizesPhotoPrintType.pano_available != true}"
										ng-disabled="photo_avail_size.PhotoAvailSizesPhotoPrintType.pano_available != true"
									/>
									<label><?php echo __('Force Settings on All Photos?', true); ?></label>
								</span>
							</td>
						</tr>
					<tr ng-if="0" ng-repeat-end></tr>
				</tbody>
			</table>
		</div>
	</form>
