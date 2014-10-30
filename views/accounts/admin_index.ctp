<script type="text/javascript">
	var account_info = JSON.parse('<?php echo json_encode($overlord_account_info); ?>')
		
	function reload_buttons() {
		$("button").button();
	}
	
	function format_numbers() {
		$(".line_item .cost").each(function() {
			$(this).html(accounting.formatMoney($(this).html()));
		});
		
		$(".details .current_bill .info").html(accounting.formatMoney($(".details .current_bill .info").html()));
		$(".details .current_credit .info").html(accounting.formatMoney($(".details .current_credit .info").html()));
	}
	
	function roundTwoDecimal(number) {
		return Math.round(number * 100) / 100;
	}
	
	function update_total(line_item, is_add) {
		if (account_info.pending_bill === undefined) {	
			account_info.pending_bill = account_info.total_bill;
		}
		
		var floatNum = roundTwoDecimal(parseFloat(line_item.attr('data-customer_cost')), 2);
		if(is_add) {
			 account_info.pending_bill += floatNum;
		} else {
			 account_info.pending_bill -= floatNum;
		}
		account_info.pending_bill = roundTwoDecimal(account_info.pending_bill, 2);
		
		if (account_info.pending_bill === account_info.total_bill) {
			$('.new_bill .pending_total').html('');
		} else {
			$('.new_bill .pending_total').html( accounting.formatMoney(account_info.pending_bill) );
		}
	}
	
	function open_add_feature_popup(selector) {
		jQuery(selector).dialog({
			width: '950',
			title: '<?php echo __('Finish Account Changes', true); ?>',
			open: function(event, ui) {
				jQuery('.ui-dialog').prepend('<div class="fade_background_top"></div>');
				var button_pane_to_move = jQuery('.button_pane_to_move');
				var button_pane_parent = button_pane_to_move.parent().parent();
				button_pane_to_move.detach();
				button_pane_parent.find('.ui-dialog-buttonpane').remove();
				button_pane_parent.append(button_pane_to_move);
			},
			close: function(event, ui) {
				$(this).dialog('destroy').remove();
			},
			modal: true
		});
	}
	
	$(document).ready(function() {
		
		reload_buttons();
		format_numbers();
//		$("#middle").css('min-height', '450px'); //TODO Adam remove this once we have all the alacart items added
//		$("#line_item_cont .line_item .bar-right span.toggle").click(function() {
//			if ($(this).closest('.bar').next(".item_ad_area").is(':hidden')) {
//				$(this).closest('.bar').next(".item_ad_area").slideDown();
//				var icon = $(this).closest('.bar').find(".bar-right i.icon-angle-down");
//				icon.removeClass('icon-angle-down').addClass('icon-angle-right');
//			} else {
//				$(this).closest('.bar').next(".item_ad_area").slideUp();
//				var icon = $(this).closest('.bar').find(".bar-right i.icon-angle-right");
//				icon.removeClass('icon-angle-right').addClass('icon-angle-down');
//			}
//		});

		var inAjaxCall = false;
		$("#line_item_cont .line_item .cancel_remove").click(function(e) {
			e.preventDefault();
			if (inAjaxCall) {
				return false;
			}
			inAjaxCall = true;

			$.ajax({
				url: '/admin/accounts/ajax_undo_cancellation/' + $(this).attr('data_id'),
				dataType: 'json',
				type: 'GET',
				success: function(data) {
					window.location.href = '/admin/accounts/index';
				},
				complete: function(data) {
					inAjaxCall = false;
				},
				error: function(data) {
					window.location.href = '/admin/accounts/index';
				}
			});
		});

		$("#line_item_cont .line_item .remove_item").click(function(e) {
			e.preventDefault();
			if (inAjaxCall) {
				return false;
			}

			var line_item_id = $(this).attr('data_id');
			jQuery.foto('confirm', {
				message: '<?php echo __('This feature will remain on your account until the next billing cycle.', true); ?><br /><br /><?php echo __('Are you sure you want to remove this item?'); ?>',
				onConfirm: function() {
					inAjaxCall = true;
					jQuery.ajax({
						type: 'GET',
						url: '/admin/accounts/ajax_remove_item/'+ line_item_id,
						success: function(data) {
							window.location.href = '/admin/accounts/index';
						},
						complete: function(data) {
							inAjaxCall = false;
						},
						error: function(data) {
							window.location.href = '/admin/accounts/index';
						}
					});
				}
			});
		});

		$("#line_item_cont .line_item .add_item").click(function(e) {
			e.preventDefault();
			if (inAjaxCall) {
				return false;
			}
			inAjaxCall = true;
			var argsToSend = {};
			argsToSend.id = $(this).attr('data_id');
			if ($(this).hasClass('pending')) {
				argsToSend.checked = 0;
				$(this).show();
				$(this).removeClass('pending');
			} else {
				argsToSend.checked = 1;
				$(this).hide();
				$(this).addClass('pending');
			}
			
			var line_item = $(this).closest('.line_item');
			if(line_item.hasClass('line_not_added')) {
				update_total(line_item, true);
				line_item.removeClass('line_not_added');
			} else {
				update_total(line_item, false);
				line_item.addClass('line_not_added');
			}
			
			if ($(".pending").length > 0) {
				$("#account-details").addClass('finish-shown');
//				$(".finish-outer-cont").fadeIn();
				$(".finish-outer-cont").show();
				$(".finish-outer-cont").effect('bounce', { times: 3, distance: 15 }, 600);
			} else {
				$("#account-details").removeClass('finish-shown');
				$(".finish-outer-cont").hide();
			}
			
			jQuery.ajax({
				type: 'post',
				url: '/admin/accounts/ajax_setItemChecked',
				data: argsToSend,
				success: function(data) {

				},
				complete: function() {
					inAjaxCall = false;
				},
				error: function() {

				},
				dataType: 'json'
			});
		});


		/* $("#line_item_cont .line_item input").change(function(e) {
		 e.preventDefault();
		 if (inAjaxCall) {
		 return false;
		 }
		 inAjaxCall = true;
		 var args_item = {};
		 args_item.checked = 0;
		 if ($(this).is(':checked')) {
		 args_item.checked = 1;
		 }
		 args_item.id = $(this).attr('data_id');
		 var this_check_box = $(this);
		 $("#line_item_cont .finish input[type=button]").fadeIn();
		 $.post('/accounts/ajax_setItemChecked', args_item, 
		 function(data) {
		 inAjaxCall = false;
		 $("#line_item_cont .line_item input").removeAttr('disabled');
		 }, 'json');
		 return false;
		 }); */

		$(".finish_account_add").click(function() {
			if (inAjaxCall) {
				return false;
			}
			inAjaxCall = true;
		
			jQuery.ajax({
				type: 'get',
				url: "/admin/accounts/ajax_finishLineChange",
				success: function(data) {
					var div = $("<div class='popup_content_with_table'></div>");
					div.html(data.html);
					open_add_feature_popup(div);
				},
				complete: function() {
					inAjaxCall = false;
				},
				error: function() {

				},
				dataType: 'json'
			});
		});
		
		$(".pay_fail_message a").click(function(e) {
			if (inAjaxCall) {
				return false;
			}
			inAjaxCall = true;
			
			e.preventDefault();
			jQuery.ajax({
				type: 'get',
				url: "/admin/accounts/ajax_addPreviousItems",
				success: function(data) {
					$(".finish_account_add").trigger('click');
				},
				complete: function() {
					inAjaxCall = false;
				},
				error: function() {

				},
				dataType: 'json'
			});
		});
		
	});
</script>

<?php if (!empty($add_feature_ref_name)): ?>
	<script type="text/javascript">
		jQuery(document).ready(function() {
			open_add_feature_popup('#add_feature_ref_name_popup_html');
		});
	</script>
	<div id="add_feature_ref_name_popup_html">
		<?php echo $add_feature_ref_name_popup_html; ?>
	</div>
<?php endif; ?>
	
<h1><?php echo __('Manage Features', true); ?>
	<div id="help_tour_button" class="custom_ui"><?php echo $this->Element('/admin/get_help_button'); ?></div>
</h1>


<?php //debug($overlord_account_info['Account']['next_bill_date']); ?>
<div id="account-details" class="<?php if (!empty($add_feature_ref_name)): ?> finish-shown <?php endif; ?> generic_photo_gallery_cont">
	<div class="page_content_header">
		<h2><?php echo __('Billing Status', true); ?></h2>
	</div>
	<div class="generic_palette_container">
		<div class='details'>
			<?php if (!empty($overlord_account_info['Account']['next_bill_date'])): ?>
				<div class='detail next_bill_date'>
					<span class='title'><?php echo __('Next Bill Date', true); ?></span>
					<span class='info'><?php echo date("M j", strtotime($overlord_account_info['Account']['next_bill_date'])); ?></span>
				</div>
			<?php endif; ?>
			<div class='detail new_bill'>
				<span class='title'><?php echo __('Projected Bill', true); ?></span>
				<span class='info pending_total'></span>
			</div>
			<div class='detail current_bill'>
				<span class='title'><?php echo __('Current Bill', true); ?></span>
				<span class='info'><?php echo $overlord_account_info['total_bill']; ?></span>
			</div>
			<div class='detail current_credit'>
				<span class='title'><?php echo __('Fotomatter Credit', true); ?></span>
				<span class='info'><?php echo $overlord_account_info['Account']['promo_credit_balance']; ?></span>
			</div>
		</div>
	</div>
</div>



<?php //debug($overlord_account_info); ?>


<div class='clear' id="line_item_cont">
	<?php /*<?php if($overlord_account_info['is_pay_fail']): ?>
		<div class='pay_fail_message rounded-corners'>
			<p>
				We failed to charge your credit card. Your premium features have been suspended. If you wish to restore your account with the same features, <a href='#' class='payment_restore'>click here.</a>
			</p>
		</div>
	<?php endif; ?> */ ?>
	
	
	<div class="page_content_header">
		<div style="<?php if (empty($add_feature_ref_name)): ?>display:none;<?php endif; ?>" class='finish-outer-cont custom_ui right'>
			<div class="add_button highlight bigger finish_account_add" type="submit">
				<div class="content"><?php echo __('Finalize Changes', true); ?></div>
				<div class="right_arrow_lines icon-arrow-01"><div></div></div>
			</div>
		</div>
		<p><?php echo __('add/remove features below', true); ?></p>
		<div style="clear: both;"></div>
	</div>
	<div class="generic_palette_container">
		<div class="fade_background_top"></div>
		<div class='table_cont'>
			<table class="list">
				<tbody>
					<?php $items_length = count($overlord_account_info['items']); ?>
					<?php $count = 1; foreach ($overlord_account_info['items'] as $line_item): ?>
						<?php 
							$icon_css = '';
							switch ($line_item['AccountLineItem']['ref_name']) {
								case 'unlimited_photos':
									$icon_css = 'icon-unlimitedPhotos-01';
									break;
								case 'basic_shopping_cart':
									$icon_css = 'icon-shoppingCart-01';
									break;
								case 'page_builder':
									$icon_css = 'icon-pageBuilder_2-01';
									break;
								case 'mobile_theme':
									$icon_css = 'icon-mobileThemes-01';
									break;
								case 'remove_fotomatter_branding':
									$icon_css = 'icon-noBranding-01';
									break;
								case 'email_support':
									$icon_css = 'icon-emailSupport-01';
									break;
							}
						
						?>
						<?php $start_queued = ($line_item['AccountLineItem']['ref_name'] == $add_feature_ref_name); ?>
						<?php
							$first_last_class = '';
							if ($count === 1) {
								$first_last_class = ' first ';
							}
							if ($count === $items_length) {
								$first_last_class = ' last ';
							}
						?>
						<tr
							class="line_item <?php echo $first_last_class; ?> <?php if ($line_item['AccountLineItem']['removed_scheduled']): ?> remove_pending <?php endif; ?> <?php if (!empty($line_item['AccountLineItem']['active'])): ?> active <?php endif; ?> <?php echo ($start_queued == false && $line_item['AccountLineItem']['addable'] || $line_item['AccountLineItem']['removed_scheduled']) ? 'line_not_added' : ''; ?> "
							data-customer_cost="<?php echo $line_item['AccountLineItem']['current_cost']; ?>" 
						>
							<?php /*<td class="first last" colspan="3">
								<div class="rightborder"></div>
								<span><?php echo __('Drag images here or click "Choose Photos" above.', true); ?></span>
							</td> */ ?>
							<td class="first">
								<div class="rightborder"></div>
								<i class="<?php echo $icon_css; ?>"></i>
								<span><?php echo $line_item['AccountLineItem']['name']; ?></span>
							</td>
							<td>
								<div class="rightborder"></div>
								<div class="cost"><?php echo $line_item['AccountLineItem']['current_cost']; ?></div>
								<div class="feature_on"><?php echo __('Active', true); ?></div>
								<div class="cancel_pending"><?php echo __('Cancel Pending', t-rue); ?></div>
							</td>
							<td class="last table_actions custom_ui">
								<div class="rightborder"></div>
								<div class="feature_pending"><?php echo __('Add Pending', true); ?></div>
								<?php if ($line_item['AccountLineItem']['active']): ?>
									<div data_id='<?php echo $line_item['AccountLineItem']['id']; ?>' class="add_button icon remove_item icon_close"><div class="content icon-close-01"></div></div>
								<?php elseif ($line_item['AccountLineItem']['removed_scheduled']): ?>
									<div data_id='<?php echo $line_item['AccountLineItem']['id']; ?>' class="add_button cancel_remove" type="submit">
										<div class="content"><?php echo __('Undo Cancellation', true); ?></div>
									</div>
								<?php else: ?>
									<?php if ($start_queued): ?>
										<?php /*<div data_id='<?php echo $line_item['AccountLineItem']['id']; ?>' class="add_button add_item pending" type="submit">
											<div class="content"><?php echo __('Cancel', true); ?></div>
											<div style="display: none;" class="plus_icon_lines"><div class="one"></div><div class="two"></div></div>
										</div>*/ ?>
									<?php else: ?>
										<div data_id='<?php echo $line_item['AccountLineItem']['id']; ?>' class="add_button highlight add_item" type="submit">
											<div class="content"><?php echo __('Add Feature', true); ?></div>
											<div class="plus_icon_lines icon-_button-01"><div class="one"></div><div class="two"></div></div>
										</div>
									<?php endif; ?>
								<?php endif; ?>
							</td>
						</tr>
						<?php /*
							<div data-customer_cost="<?php echo $line_item['AccountLineItem']['current_cost']; ?>" 
								class="line_item <?php echo ($start_queued == false && $line_item['AccountLineItem']['addable'] || $line_item['AccountLineItem']['removed_scheduled']) ? 'line_not_added' : ''; ?> ">
								<div class="bar hidden rounded-corners-tiny">
									<h5><?php echo $line_item['AccountLineItem']['name']; ?> - <span class='cost'><?php echo $line_item['AccountLineItem']['current_cost']; ?></span></h5>
									<div class="bar-right custom_ui">
										<?php if ($line_item['AccountLineItem']['active']): ?>
											<button data_id='<?php echo $line_item['AccountLineItem']['id']; ?>' class='remove_item'>Remove</button>
										<?php elseif ($line_item['AccountLineItem']['removed_scheduled']): ?>
											<button data_id='<?php echo $line_item['AccountLineItem']['id']; ?>' class='cancel_remove'>Undo Cancellation</button>
										<?php else: ?>
											<?php if ($start_queued): ?>
												<button data_id='<?php echo $line_item['AccountLineItem']['id']; ?>' class='add_item'>Queued</button>
											<?php else: ?>
												<button data_id='<?php echo $line_item['AccountLineItem']['id']; ?>' class='add_item'>Add</button>
											<?php endif; ?>
										<?php endif; ?>
										<!--<span>Active</span><input <?php echo $line_item['AccountLineItem']['active'] ? 'CHECKED' : ''; ?> type='checkbox' name='data[AccountLineItem][]'  data_id='<?php echo $line_item['AccountLineItem']['id']; ?>' /> -->
										<span class="toggle">Show Details</span><i class="icon-angle-down"></i>
									</div>
									<div style="clear:both"></div>
								</div>
								<div class="item_ad_area">
									<?php
									$ad_file = "line_item_ads/" . str_replace(' ', '_', strtolower($line_item['AccountLineItem']['name']));
									if (is_file(ROOT . '/app/views/elements/' . $ad_file . '.ctp')) {
										echo $this->Element($ad_file);
									} else {
										echo $this->Element('line_item_ads/default');
									}
									?>
								</div>
							</div>
						*/ ?>
					<?php $count++; endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
	
	<?php /*
    <div class="body_container">   
		<?php foreach ($overlord_account_info['items'] as $line_item): ?>
			<?php $start_queued = ($line_item['AccountLineItem']['ref_name'] == $add_feature_ref_name); ?>
			<div data-customer_cost="<?php echo $line_item['AccountLineItem']['current_cost']; ?>" 
				class="line_item <?php echo ($start_queued == false && $line_item['AccountLineItem']['addable'] || $line_item['AccountLineItem']['removed_scheduled']) ? 'line_not_added' : ''; ?> ">
				<div class="bar hidden rounded-corners-tiny">
					<h5><?php echo $line_item['AccountLineItem']['name']; ?> - <span class='cost'><?php echo $line_item['AccountLineItem']['current_cost']; ?></span></h5>
					<div class="bar-right custom_ui">
						<?php if ($line_item['AccountLineItem']['active']): ?>
							<button data_id='<?php echo $line_item['AccountLineItem']['id']; ?>' class='remove_item'>Remove</button>
						<?php elseif ($line_item['AccountLineItem']['removed_scheduled']): ?>
							<button data_id='<?php echo $line_item['AccountLineItem']['id']; ?>' class='cancel_remove'>Undo Cancellation</button>
						<?php else: ?>
							<?php if ($start_queued): ?>
								<button data_id='<?php echo $line_item['AccountLineItem']['id']; ?>' class='add_item'>Queued</button>
							<?php else: ?>
								<button data_id='<?php echo $line_item['AccountLineItem']['id']; ?>' class='add_item'>Add</button>
							<?php endif; ?>
						<?php endif; ?>
						<!--<span>Active</span><input <?php echo $line_item['AccountLineItem']['active'] ? 'CHECKED' : ''; ?> type='checkbox' name='data[AccountLineItem][]'  data_id='<?php echo $line_item['AccountLineItem']['id']; ?>' /> -->
						<span class="toggle">Show Details</span><i class="icon-angle-down"></i>
					</div>
					<div style="clear:both"></div>
				</div>
				<div class="item_ad_area">
					<?php
					$ad_file = "line_item_ads/" . str_replace(' ', '_', strtolower($line_item['AccountLineItem']['name']));
					if (is_file(ROOT . '/app/views/elements/' . $ad_file . '.ctp')) {
						echo $this->Element($ad_file);
					} else {
						echo $this->Element('line_item_ads/default');
					}
					?>
				</div>
			</div>
		<?php endforeach; ?>
    </div>
	*/ ?>
	
	
</div>