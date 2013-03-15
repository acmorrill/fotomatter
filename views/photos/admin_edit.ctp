<script type="text/javascript" src="/js/jquery_price_format/jquery.price_format.min.js"></script>


<div id="image_edit_container">
	<?php echo $this->Form->create('Photo', array('enctype' => 'multipart/form-data')); ?>
	
	<div class="image_edit_block" >
		

		<?php $img_path = $this->Photo->get_photo_path($this->data['Photo']['id'], 350, 350); ?>
		<img src="<?php echo $img_path; ?>" />


		<?php echo $session->flash(); ?>
		<br/>

		<?php /*<script type="text/javascript">
			jQuery(document).ready(function() {
				jQuery('#OldPhotoEditOldphotoForm').validate({
					rules: {
							firstname: "required",
							lastname: "required",
							username: {
								required: true,
								minlength: 2,
								remote: "users.php"
							},
							password: {
								required: true,
								minlength: 5
							},
							password_confirm: {
								required: true,
								minlength: 5,
								equalTo: "#password"
							},
							email: {
								required: true,
								email: true,
								remote: "emails.php"
							},
							dateformat: "required",
							terms: "required"
					},
					messages: {
							firstname: "Enter your firstname",
							lastname: "Enter your lastname",
							username: {
							required: "Enter a username",
							minlength: jQuery.format("Enter at least {0} characters"),
							remote: jQuery.format("{0} is already in use")
							},
							password: {
								required: "Provide a password",
								rangelength: jQuery.format("Enter at least {0} characters")
							},
							password_confirm: {
								required: "Repeat your password",
								minlength: jQuery.format("Enter at least {0} characters"),
								equalTo: "Enter the same password as above"
							},
							email: {
								required: "Please enter a valid email address",
								minlength: "Please enter a valid email address",
								remote: jQuery.format("{0} is already in use")
							},
							dateformat: "Choose your preferred dateformat",
							terms: " "
					}
				});
			});
		</script>*/ ?>

		<?php //if ($mode == 'edit'): ?>
		<?php //else: ?>
			<?php //echo $this->Form->create('Photo', array('url' => array('controller' => 'photos', 'action' => 'add'), 'enctype' => 'multipart/form-data')); ?>
		<?php //endif; ?>
		<?php
			echo $this->Form->input('display_title');
			echo $this->Form->input('display_subtitle');
			//echo $this->Form->input('title');
			echo $this->Form->input('alt_text');
			//echo $this->Form->input('shotDate');
			echo $this->Form->input('description');
			echo $this->Form->input('date_taken', array(
				'dateFormat' => 'DMY',
				'minYear' => date('Y') - 100,
				'maxYear' => date('Y'),
			));
			?>
		<!--	<div class="input text">
					<label>Date Taken</label>
					<script type="text/javascript">
						jQuery(document).ready(function() {
							jQuery('#date_taken').datepicker({

							});
						});
					</script>
					<input id="date_taken" name="data[Photo][date_taken]" type="text" value="" />
				</div>-->
			<?php

			/*echo $this->Form->input('Photo.galleries', array(
				'type' => 'select',
				'multiple' => 'checkbox',
				'options' => array(
					'largeFormatColor' => 'largeFormatColor',
					'largeFormatBW' => 'largeFormatBW',
					'digitalIdeas' => 'digitalIdeas',
					'panoramics' => 'panoramics',
					'favorites' => 'favorites',
					'temples' => 'temples',
					'noPano' => 'noPano'
				),
				'selected' => $currGalleries
			));*/
			//echo $this->Form->input('tier');
			//echo $this->Form->label('Photo.enabled');
			echo $this->Form->input('enabled');
			/*echo $this->Form->input('Photo.format', array(
				'type' => 'select',
				'options' => array(
					'landscape' => 'landscape',
					'portrait' => 'portrait',
					'square' => 'square',
					'panoramic' => 'panoramic'
				),
				'selected' => $this->data['Photo']['format']
			));*/
			/*echo $this->Form->input('Photo.availSizes', array(
				'type' => 'select',
				'multiple' => 'checkbox',
				'options' => array(
					'5' => '5',
					'8' => '8',
					'10' => '10',
					'11' => '11',
					'16' => '16',
					'20' => '20',
					'22' => '22',
					'24' => '24',
					'26' => '26',
					'29' => '29',
					'30' => '30',
					'35' => '35',
					'40' => '40',
					'44' => '44',
					'48' => '48'
				),
				'selected' => $currSizes
			));
			echo $this->Form->input('pricePerFoot');
			echo $this->Form->label('Photo.thumbImage');
			echo $this->Form->file('Photo.thumbImage');
			echo '<br/>';
			echo $this->Form->label('Photo.largeImage');
			echo $this->Form->file('Photo.largeImage');
			echo '<br/>';
			echo $this->Form->label('Photo.extraLargeImage');
			echo $this->Form->file('Photo.extraLargeImage');
			* 
			*/
			echo $this->Form->input('Photo.cdn-filename', array('type' => 'file'));
			echo $this->Form->input('Photo.photo_format_id');
		?>
			<div class="input text">
				<label><?php __('Photo Tags'); ?></label><br style="clear: both;"/>
				<select name="data[Photo][tag_ids][]" multiple="multiple" class="chzn-select" data-placeholder="Find Tags ..." style="width: 300px;">
					<?php foreach ($tags as $tag): ?>
						<option value="<?php echo $tag['Tag']['id']; ?>" <?php if (in_array($tag['Tag']['id'], $photo_tag_ids)): ?>selected="selected"<?php endif; ?> ><?php echo $tag['Tag']['name']; ?></option>
					<?php endforeach; ?>
				</select>
			</div>

		<br/>
		<br/>

	</div>
	
	<script type="text/javascript">
		function setRowValues(attr_name, row_tr) {
			jQuery('td', row_tr).each(function() {
				var key_value = jQuery(this).attr(attr_name);
				console.log (key_value);
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
				} else {
					jQuery.foto('alert', '<?php __('This row is unlockable because of a global setting in Manage Print Types and Pricing.'); ?>');
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
			});
		});
	</script>
	<div id="image_sellable_prints" class="image_edit_block" style="width: 575px;">
		<h1><?php __('Choose Available Sizes'); ?></h1>
		<table class="list">
			<thead>
				<tr>
					<th><?php __('Override Global Default'); ?></th>
					<th style="width: 50px;"><?php __('Photo Uses Size'); ?></th>
					<th><?php __('Print Type and Size'); ?></th>
					<th><?php __('Price'); ?></th>
					<th><?php __('Shipping Price'); ?></th>
					<th><?php __('Turnaround Time'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php $count = 0; foreach ($photo_sellable_prints as $photo_sellable_print): ?>
					<tr>
						<td>
							<?php 
								$override_for_photo =  $photo_sellable_print['CurrentPrintData']['override_for_photo'];
								$force_defaults = $photo_sellable_print['DefaultPrintData']['force_defaults']; 
								if ($force_defaults === '1') {
									$override_for_photo = '0';
								}
							?>
							<input class="override_for_photo" type="hidden" name="data[PhotoSellablePrint][<?php echo $count; ?>][override_for_photo]" value="<?php echo $override_for_photo; ?>" />
							<img class="lock_img <?php if ($force_defaults === '1'): ?>unlockable<?php endif; ?>" src="/img/admin/icons/lock_white.png" style="<?php if($override_for_photo === '1'): ?>display: none;<?php endif; ?>" />
							<img class="unlock_img" src="/img/admin/icons/unlock_white.png" style="<?php if($override_for_photo === '1'): ?>display: inline-block;<?php endif; ?>" />
							
							<input name="data[PhotoSellablePrint][<?php echo $count; ?>][photo_avail_sizes_photo_print_type_id]" type="hidden" value="<?php echo $photo_sellable_print['PrintTypeJoin']['id']; ?>" />
							<input name="data[PhotoSellablePrint][<?php echo $count; ?>][photo_id]" type="hidden" value="<?php echo $this->data['Photo']['id']; ?>" />
							<?php if (isset($photo_sellable_print['PhotoSellablePrint']['id'])): ?>
								<input name="data[PhotoSellablePrint][<?php echo $count; ?>][id]" type="hidden" value="<?php echo $photo_sellable_print['PhotoSellablePrint']['id']; ?>" />
							<?php endif; ?>
						</td>
						<td default="<?php echo $photo_sellable_print['DefaultPrintData']['default_available']; ?>" current="<?php echo $photo_sellable_print['CurrentPrintData']['available']; ?>">
							<div class="disablable <?php if ($override_for_photo === '0'): ?>opacity_50<?php endif; ?>">
								<input type="checkbox"  name="data[PhotoSellablePrint][<?php echo $count; ?>][available]" <?php if ($photo_sellable_print['CurrentPrintData']['available'] === '1'): ?>checked="checked"<?php endif; ?> default="<?php echo $photo_sellable_print['DefaultPrintData']['default_available']; ?>" custom="<?php echo isset($photo_sellable_print['PhotoSellablePrint']['override_for_photo']) ? $photo_sellable_print['PhotoSellablePrint']['override_for_photo'] : 0 ; ?>" />
								<input type="hidden"  name="data[PhotoSellablePrint][<?php echo $count; ?>][defaults][available]" value="<?php echo $photo_sellable_print['DefaultPrintData']['default_available']; ?>"  />
							</div>
						</td>
						<td style="width: 200px;">
							<div class="disablable <?php if ($override_for_photo === '0'): ?>opacity_50<?php endif; ?>">
								<?php echo $photo_sellable_print['PhotoPrintType']['print_name']; ?> &mdash; <?php echo $photo_sellable_print['DefaultPrintData']['short_side_inches']; ?>" x <?php echo $photo_sellable_print['DefaultPrintData']['long_side_feet_inches']; ?>
							</div>
						</td>
						<td class="input" default="<?php echo $photo_sellable_print['DefaultPrintData']['price']; ?>" current="<?php echo $photo_sellable_print['CurrentPrintData']['price']; ?>">
							<div class="disablable <?php if ($override_for_photo === '0'): ?>opacity_50<?php endif; ?>">
								$<input class="money_format" value="<?php echo $photo_sellable_print['CurrentPrintData']['price']; ?>" name="data[PhotoSellablePrint][<?php echo $count; ?>][price]" type="text" />
								<input type="hidden"  name="data[PhotoSellablePrint][<?php echo $count; ?>][defaults][price]" value="<?php echo $photo_sellable_print['DefaultPrintData']['price']; ?>"  />
							</div>
						</td>
						<td class="input" default="<?php echo $photo_sellable_print['DefaultPrintData']['shipping_price']; ?>" current="<?php echo $photo_sellable_print['CurrentPrintData']['shipping_price']; ?>">
							<div class="disablable <?php if ($override_for_photo === '0'): ?>opacity_50<?php endif; ?>">
								$<input class="money_format" value="<?php echo $photo_sellable_print['CurrentPrintData']['shipping_price']; ?>" name="data[PhotoSellablePrint][<?php echo $count; ?>][shipping_price]" type="text" />
								<input type="hidden"  name="data[PhotoSellablePrint][<?php echo $count; ?>][defaults][shipping_price]" value="<?php echo $photo_sellable_print['DefaultPrintData']['shipping_price']; ?>"  />
							</div>
						</td>
						<td class="input" default="<?php echo $photo_sellable_print['DefaultPrintData']['custom_turnaround']; ?>" current="<?php echo $photo_sellable_print['CurrentPrintData']['custom_turnaround']; ?>">
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
	
	<?php echo $this->Form->end('Save'); ?>
</div>