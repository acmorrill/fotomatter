<div class="input">
	<label for="print_type_name">Choose a Print Name (ie glossy, matted and framed etc)</label>
	<input 
		style="margin-left: 15px;"
		id="print_type_name"
		ng-model="print_type_name"
	/>
</div>
<div class="select">
	<label for="billing_firstname">Choose a Printing Method</label>
	<select 
		id="choose_print_fulfiller"
		ng-model="print_fulfiller_id"
		style="margin-left: 15px;"
	>
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