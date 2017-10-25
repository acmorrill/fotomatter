<div class="page_content_header">
	<h2 class="ng-hide" ng-show="open_print_type.photo_print_type.PhotoPrintType.print_fulfillment_type == 'self'"><?php echo __('Manually Processed Print Settings', true); ?></h2>
	<h2 class="ng-hide" ng-show="open_print_type.photo_print_type.PhotoPrintType.print_fulfillment_type == 'autofixed'">Settings | {{open_print_type.print_fulfiller.lab_name}} | {{open_print_type.print_fulfiller_print_type.name}}</h2>
	<h2 class="ng-hide" ng-show="open_print_type.photo_print_type.PhotoPrintType.print_fulfillment_type == 'autodynamic'"><?php echo __('Dynamic Settings', true); ?></h2>
	<h2 class="ng-hide" ng-show="open_print_type.photo_print_type.PhotoPrintType.print_fulfillment_type == 'autofixeddynamic'"><?php echo __('Fixed Dynamic Settings', true); ?></h2>
	<h2 class="ng-hide" ng-show="open_print_type.photo_print_type.PhotoPrintType.print_fulfillment_type == 'automisc'"><?php echo __('Misc Settings', true); ?></h2>
</div>

	<?php 
		$photo_print_type_id_str = '';
//		if (!empty($photo_print_type['PhotoPrintType']['id']) && $photo_print_type['PhotoPrintType']['id'] != '0') {
//			$photo_print_type_id_str = 'data-photo_print_type_id="' . $photo_print_type['PhotoPrintType']['id'] .'"';
//		}
	?>
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
					<select 
						ng-model="open_print_type.photo_print_type.PhotoPrintType.turnaround_time"
						ng-options="key as value.text for (key, value) in global_turnaround_days"
						ng-change="savePrintTypeSetting(open_print_type.photo_print_type, '{{open_print_type.photo_print_type.PhotoPrintType.turnaround_time}}')" 
					></select>
				</div>
				<div class="theme_setting_description">
					<?php echo __('The amount of time it takes you to fulfill an order (to receive the funds, order the print, and to ship.) Example: 3 weeks, 5 days, etc. Note: some themes currently don\'t list this anywhere. ', true); ?>
				</div>
			</div>
            <div ng-if="open_print_type.photo_print_type.PhotoPrintType.print_fulfillment_type == 'self'" class="basic_setting_cont no_border">
                <label><?php echo __('Print Type Ships by Itself?', true); ?></label>
                <div class="theme_setting_inputs_container">
                    <select
                            ng-model="open_print_type.photo_print_type.PhotoPrintType.print_type_ships_by_itself"
                            ng-options="key as value.text for (key, value) in yes_no_options"
                            ng-change="savePrintTypeSetting(open_print_type.photo_print_type, '{{open_print_type.photo_print_type.PhotoPrintType.print_type_ships_by_itself}}')"
                    ></select>
                </div>
                <div class="theme_setting_description">
                    <?php echo __('Used for estimating the shipping cost of this print type in the shopping cart.', true); ?>
                </div>
            </div>
			<div ng-if="open_print_type.photo_print_type.PhotoPrintType.print_fulfillment_type == 'self'" class="basic_setting_cont no_border">
				<label><?php echo __('Print Type Can Be Rolled?', true); ?></label>
				<div class="theme_setting_inputs_container">
					<select
							ng-model="open_print_type.photo_print_type.PhotoPrintType.print_type_can_be_rolled"
							ng-options="key as value.text for (key, value) in yes_no_options"
							ng-change="savePrintTypeSetting(open_print_type.photo_print_type, '{{open_print_type.photo_print_type.PhotoPrintType.print_type_can_be_rolled}}')"
					></select>
				</div>
				<div class="theme_setting_description">
					<?php echo __('Used for estimating the shipping cost of this print type in the shopping cart.', true); ?>
				</div>
			</div>
		</div>
		
			<div class="table_container" <?php /*ng-if="open_print_type.photo_print_type.PhotoPrintType.print_fulfillment_type == 'autofixed' || open_print_type.photo_print_type.PhotoPrintType.print_fulfillment_type == 'autodynamic' || open_print_type.photo_print_type.PhotoPrintType.print_fulfillment_type == 'autofixeddynamic'"*/ ?> style="margin-top: 40px;">
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
									<?php echo __('Print Size &amp; Cost', true); ?>
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

						<tr ng-repeat="print_size in open_print_type.print_sizes_list">
							<td class="first">
								<toggle-switch
									ng-model="print_size.PhotoAvailSizesPhotoPrintType.available" 
									ng-change="savePrintType(print_size, $index, true)" 
									ng-model-options="{}"
								><toggle-switch>
								<br />
							</td>
							<td ng-if="print_size.display_type == 'fixed'">
								{{print_size.short_side_inches}}&Prime; x {{print_size.long_side_inches}}<span ng-if="open_print_type.photo_print_type.PhotoPrintType.print_fulfillment_type == 'autofixeddynamic'">&Prime; &mdash; Standard</span>
							</td>
							<td ng-if="print_size.display_type == 'dynamic' || print_size.display_type == 'self'">
								{{print_size.PhotoAvailSize.short_side_length}}&Prime; x long-side<span ng-if="open_print_type.photo_print_type.PhotoPrintType.print_fulfillment_type == 'autofixeddynamic'">&Prime; &mdash; Custom</span>
								<br />
								<span 
									ng-if='print_size.PhotoAvailSize.has_non_pano == true'
									style="font-size: 15px; margin-left: 0px; border-left: 0px; margin-top: 15px;"
								>(<?php echo __('Non-Panoramic', true); ?>)</span>
								<span 
									ng-if='print_size.PhotoAvailSize.has_pano == true'
									style="font-size: 15px; margin-left: 0px; border-left: 0px; margin-top: 15px;"
								>(<?php echo __('Panoramic', true); ?>)</span>
							</td>
							<td class="price_width">
								<span class="subitem_container" ng-if="print_size.display_type != 'self'">
									<label ng-if="print_size.display_type == 'fixed'"><?php echo __('Your Cost', true); ?></label>
									<label ng-if="print_size.display_type == 'dynamic'"><?php echo __('Your Cost', true); ?> ( ${{print_size.PhotoAvailSize.dynamic_cost_sq_inch}} / in&sup2; )<span class="icon-info" init-toolbar="The real cost depends on the actual demensions of the image."></span></label>
									<br />
									<span>
										<span ng-if="print_size.display_type == 'fixed'">{{display_price(print_size.cost)}}</span>
										<span ng-if="print_size.display_type == 'dynamic'">
											{{display_price(print_size.PhotoAvailSize.min_est_cost_display)}} &mdash; {{display_price(print_size.PhotoAvailSize.max_est_cost_display)}}
										</span>
									</span>
								</span>
								<span class="subitem_container">
									<label><?php echo __('Price', true); ?></label><br />
									<span>
										<span 
											onaftersave="savePrintType(print_size, $index, true)" 
											editable-text="print_size.PhotoAvailSizesPhotoPrintType.price"
											e-form="price"
											ng-click="show_editable(price, !!print_size.PhotoAvailSizesPhotoPrintType.available == true)"
											ng-class="{'disabled': !!print_size.PhotoAvailSizesPhotoPrintType.available != true}"
											class="editable editable-click"
										>{{ display_price(print_size.PhotoAvailSizesPhotoPrintType.price) }}</span>
									</span>
								</span>
								<span class="subitem_container">
									<label><?php echo __('Handling Charge', true); ?></label><br />
									<span>
										<span
												onaftersave="savePrintType(print_size, $index, true)"
												editable-text="print_size.PhotoAvailSizesPhotoPrintType.handling_price"
												e-form="handling_price"
												ng-click="show_editable(handling_price, !!print_size.PhotoAvailSizesPhotoPrintType.available == true)"
												ng-class="{'disabled': !!print_size.PhotoAvailSizesPhotoPrintType.available != true}"
												class="editable editable-click"
										>{{ display_price(print_size.PhotoAvailSizesPhotoPrintType.handling_price) }}</span>
									</span>
								</span>
								<?php /*<span class="subitem_container">
									<label><?php echo __('Shipping Price', true); ?></label><br />
									<span>
										<span>$</span>
										<input 
											class="money_format"
											type="text"
										/>
									</span>
								</span>*/ ?>
							</td>
							<td class="last" ng-if-end >
								<span class="subitem_container">
									<label><?php echo __('Turnaround Time', true); ?></label><br />
									&nbsp;&nbsp;
									<span 
										onaftersave="savePrintType(print_size, $index, true)" 
										editable-select="print_size.PhotoAvailSizesPhotoPrintType.custom_turnaround"
										e-form="custom_turnaround"
										ng-click="show_editable(custom_turnaround, !!print_size.PhotoAvailSizesPhotoPrintType.available == true)"
										class="editable editable-click"
										ng-class="{'disabled': !!print_size.PhotoAvailSizesPhotoPrintType.available != true}"
										e-ng-options="s.value as s.text for s in turnaround_days"
									>{{ show_turnaround(print_size.PhotoAvailSizesPhotoPrintType.custom_turnaround) }}</span>
									<br />
								</span>
								<span class="subitem_container">
									<input 
										type="checkbox" 
										ng-change="savePrintType(print_size, $index, true)" 
										ng-model="print_size.PhotoAvailSizesPhotoPrintType.global_default" 
										ng-class="{'disabled': !!print_size.PhotoAvailSizesPhotoPrintType.available != true}"
										ng-disabled="!!print_size.PhotoAvailSizesPhotoPrintType.available != true"
									/>
									<label><?php echo __('Use on Photos by Default?', true); ?></label>
								</span>
								<span class="subitem_container">
									<input 
										type="checkbox" 
										ng-change="savePrintType(print_size, $index, true)" 
										ng-model="print_size.PhotoAvailSizesPhotoPrintType.force_settings" 
										ng-class="{'disabled': !!print_size.PhotoAvailSizesPhotoPrintType.available != true}"
										ng-disabled="!!print_size.PhotoAvailSizesPhotoPrintType.available != true"
									/>
									<label><?php echo __('Force Settings on All Photos?', true); ?></label>
								</span>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</switch>
		
		
		
		
