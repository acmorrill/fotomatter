<script type="text/javascript">
	var account_info = JSON.parse('<?php echo json_encode($overlord_account_info); ?>')
	var inAjaxCall = false;
		
	function reload_buttons() {
		$("button").button();
	}
	
	function format_numbers() {
		$(".line_item .cost > span, .line_item .feature_on > span > span").each(function() {
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
	
	
	
	$(document).ready(function() {
		reload_buttons();
		format_numbers();
		
		<?php if (!empty($update_billing)): ?>
			open_add_profile_popup_close_when_done();
		<?php endif; ?>

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
				message: '<?php echo __('This feature will remain on your account until the next billing cycle.', true); ?><br /><br /><?php echo __('If you add this feature again in the future you could lose your current price if prices change.', true); ?><br /><br /><?php echo __('Are you sure you want to remove this item?'); ?>',
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
				$(".finish-outer-cont").show();
				setTimeout(function() {
					$(".finish-outer-cont").stop().effect('bounce', { times: 3, distance: 15 }, 600);
				}, 300);
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



		$(".finish_account_add").click(function() {
			if (typeof inAjaxCall == 'boolean' && inAjaxCall) {
				return false;
			}
			show_universal_load();
			open_finish_account_change();
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
	<div id="add_feature_ref_name_popup_html">
		<?php echo $add_feature_ref_name_popup_html; ?>
	</div>
<?php endif; ?>
<h1><?php echo __('Manage Features', true); ?> ( <a href="#what_comes_free"><?php echo __('free features?', true); ?></a> )
	<div id="help_tour_button" class="custom_ui"><?php echo $this->Element('/admin/get_help_button'); ?></div>
</h1>
<p><?php echo __('All of fotomatter.net’s features are offered a la carte. Choose the features you need now. You can add or delete features whenever you want. Many more features are currently underway.', true); ?></p>
<p><?php echo __('When you check out, you will be charged a prorated amount based on your new features.', true); ?></p>

<?php //debug($overlord_account_info['Account']['next_bill_date']); ?>
<div id="account-details" class="<?php if (!empty($add_feature_ref_name)): ?> finish-shown <?php endif; ?> generic_photo_gallery_cont" data-step="5" data-intro="<?php echo __('After you’ve added features, your projected monthly bill will be displayed here. When you check out, you will be charged a prorated amount based on your new features.', true); ?>" data-position="bottom">
	<div class="page_content_header">
		<h2><?php echo __('Billing Status', true); ?></h2>
	</div>
	<div class="generic_palette_container">
		<div class='details'>
			<?php if (!empty($overlord_account_info['total_bill']) && empty($overlord_account_info['is_free_account'])): ?>
				<div class='detail next_bill_date'>
					<span class='title'><?php echo __('Next Bill Date', true); ?></span>
					<span class='info'><?php echo date("M j", strtotime($overlord_account_info['Account']['next_bill_date'])); ?></span>
				</div>
			<?php endif; ?>
			<?php if (!empty($overlord_account_info['is_free_account'])): ?>
				<div class='detail next_bill_date green'>
					<span class='title'><?php echo __('Free Until', true); ?></span>
					<span class='info'><?php echo date("M j", strtotime($overlord_account_info['Account']['free_until'])); ?></span>
				</div>
			<?php endif; ?>
			<div class='detail new_bill <?php if (!empty($overlord_account_info['is_free_account'])): ?> strike <?php endif; ?>'>
				<span class='title'><?php echo __('Projected Monthly Bill', true); ?></span>
				<span class='info pending_total'></span>
			</div>
			<div class='detail current_bill <?php if (!empty($overlord_account_info['is_free_account'])): ?> strike <?php endif; ?>'>
				<span class='title'><?php echo __('Current Monthly Bill', true); ?></span>
				<span class='info'><?php echo $overlord_account_info['total_bill']; ?></span>
			</div>
			<div class='detail current_credit <?php if ($overlord_account_info['Account']['promo_credit_balance'] > 0): ?> green <?php endif; ?>'>
				<span class='title'><?php echo __('Fotomatter Credit', true); ?></span>
				<span class='info'><?php echo $overlord_account_info['Account']['promo_credit_balance']; ?></span>
			</div>
			
			<div class='detail update_credit_card custom_ui' onclick="open_add_profile_popup_close_when_done()">
				<div class="add_button">
					<div class="content"><?php echo __('Update Billing Info', true); ?></div>
					<div class="right_arrow_lines icon-arrow-01"><div></div></div>
				</div>
			</div>
		</div>
	</div>
</div>





<a name="finalize_changes" class="anchor_tag"></a>

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
					<?php 
						//$this->Util->preprint($overlord_account_info);
						$items_length = count($overlord_account_info['items']); 
						$features_that_are_on = array(
							'unlimited_photos' => 1,
							'basic_shopping_cart' => 1,
							'page_builder' => 1,
							'remove_fotomatter_branding' => 1,
							'email_chat_support' => 1,
						);
					?>
					<?php $count = 1; $step_count = 1; foreach ($overlord_account_info['items'] as $line_item): ?>
						<?php 
							if (empty($features_that_are_on[$line_item['AccountLineItem']['ref_name']])) {
								continue;
							}
							
							$icon_css = '';
							$help_text = '';
							switch ($line_item['AccountLineItem']['ref_name']) {
								case 'unlimited_photos':
									$help_text = 'data-step="' . $step_count++ . '" data-intro="' . sprintf(__("Every fotomatter.net customer gets %s photos free. If you need more than that, add unlimited photos here.", true), LIMIT_MAX_FREE_PHOTOS) . '" data-position="top"';
									$icon_css = 'icon-unlimitedPhotos-01';
									break;
								case 'unlimited_storage':
									$help_text = 'data-step="' . $step_count++ . '" data-intro="' . sprintf(__("Every fotomatter.net customer gets %s gigabytes free. If you need more than that, add unlimited storage here.", true), 100) . '" data-position="top"';
									$icon_css = 'icon-unlimitedPhotos-01';
									break;
								case 'basic_shopping_cart':
									$help_text = 'data-step="' . $step_count++ . '" data-intro="' . __("To easily sell your photos, add e-commerce to your site.", true) . '" data-position="top"';
									$icon_css = 'icon-shoppingCart-01';
									break;
								case 'auto_fulfillment':
									$help_text = 'data-step="' . $step_count++ . '" data-intro="' . __("Sell photos to clients without all the hassle of working with printers and shipping prints!", true) . '" data-position="top"';
									$icon_css = 'icon-shoppingCart-01';
									break;
								case 'page_builder':
									$help_text = 'data-step="' . $step_count++ . '" data-intro="' . __("To add pages such as About, Contact, Pricing, etc. add our Page Builder.", true) . '" data-position="top"';
									$icon_css = 'icon-pageBuilder_2-01';
									break;
								case 'mobile_theme':
									$icon_css = 'icon-mobileThemes-01';
									break;
								case 'remove_fotomatter_branding':
									$help_text = 'data-step="' . $step_count++ . '" data-intro="' . __("Our logo automatically appears at the bottom of the sites we host unless you choose to remove it.", true) . '" data-position="top"';
									$icon_css = 'icon-noBranding-01';
									break;
								case 'email_chat_support':
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
							data-customer_cost="<?php echo empty($line_item['AccountLineItem']['customer_cost']) ? $line_item['AccountLineItem']['current_cost'] : $line_item['AccountLineItem']['customer_cost']; ?>" 
						>
							<?php /*<td class="first last" colspan="3">
								<div class="rightborder"></div>
								<span><?php echo __('Drag images here or click "Choose Photos" above.', true); ?></span>
							</td> */ ?>
							<td class="first">
								<div class="rightborder"></div>
								<span <?php echo $help_text; ?>><i class="<?php echo $icon_css; ?>"></i><?php echo $line_item['AccountLineItem']['name']; ?></span>
							</td>
							<td>
								<div class="rightborder"></div>
								<div class="cost <?php if (!empty($overlord_account_info['is_free_account'])): ?> strike <?php endif; ?> ">
									<?php if (!empty($line_item['AccountLineItem']['customer_cost']) && $line_item['AccountLineItem']['current_cost'] != $line_item['AccountLineItem']['customer_cost'] && empty($overlord_account_info['is_free_account'])): ?>
										<?php echo sprintf(__('<span class="strike">%s</span>&nbsp;<span>%s</span> / month', true), $line_item['AccountLineItem']['current_cost'], $line_item['AccountLineItem']['customer_cost']); ?>
									<?php else: ?>
										<?php echo sprintf(__('<span>%s</span> / month', true), $line_item['AccountLineItem']['current_cost']); ?>
									<?php endif; ?>
								</div>
								<div class="feature_on">
									<?php if (!empty($line_item['AccountLineItem']['customer_cost'])): ?>
										<span class="icon-Success-01">&nbsp;</span>&nbsp;<?php echo sprintf(__('<span>(<span>%s</span> / month)</span>', true), $line_item['AccountLineItem']['customer_cost']); ?>
									<?php else: ?>
										<span class="icon-Success-01">&nbsp;</span>&nbsp;<?php echo sprintf(__('<span>(<span>%s</span> / month)</span>', true), $line_item['AccountLineItem']['current_cost']); ?>
									<?php endif; ?>
								</div>
								<div class="cancel_pending"><?php echo __('Cancel Pending', true); ?></div>
							</td>
							<td class="last table_actions custom_ui">
								<div class="rightborder"></div>
								<div class="feature_pending"><a href="#finalize_changes"><?php echo __('Add Pending', true); ?></a></div>
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
	<br />
	<br />
	<br />
	<a name="what_comes_free" class="anchor_tag"></a>
	<h1><?php echo __('What Comes With Your Free Account?', true); ?></h1>
	<p><?php echo __('<b>&bull;&nbsp;&nbsp; Theme and Website Tools</b> &nbsp;&mdash;&nbsp; choose from any of our amazing themes, upload your logo, create a website menu etc. Essentially a photography website for free!', true); ?></p>
	<p><?php echo sprintf(__('<b>&bull;&nbsp;&nbsp; %s GB Free</b> &nbsp;&mdash;&nbsp; use up to %s GB of space for free!', true), 100, 100); ?></p>
	<p><?php echo sprintf(__('<b>&bull;&nbsp;&nbsp; %s Free Photos</b> &nbsp;&mdash;&nbsp; upload up to %s photos in your free account.', true), LIMIT_MAX_FREE_PHOTOS, LIMIT_MAX_FREE_PHOTOS); ?></p>
	<p><?php echo __('<b>&bull;&nbsp;&nbsp; Unlimited Galleries</b> &nbsp;&mdash;&nbsp; create as many galleries as you need.', true); ?></p>
	<p><?php echo __('<b>&bull;&nbsp;&nbsp; Domains</b> &nbsp;&mdash;&nbsp; register a custom domain for your website.', true); ?></p>
	<br />
	
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