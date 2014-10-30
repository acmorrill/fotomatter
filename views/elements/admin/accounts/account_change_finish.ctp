

	<script type="text/javascript">
		function finishChange() {
			$.ajax({
				type: 'POST',
				url: "/admin/accounts/ajax_finish_account_change",
				success: function(data) {
					window.location.href = "/admin/accounts/index";
				},
				dataType: 'json'
			});
		}

		function changePaymentData() {
			$.ajax({
				type: 'GET',
				url: '/admin/accounts/ajax_update_payment/closeWhenDone:false',
				success: function(data) {
					jQuery(".ui-dialog-content").removeClass('popup_content_with_table');
					jQuery('.ui-dialog-buttonpane').remove();
					jQuery(".ui-dialog-content").html(data.html);
					var button_pane_to_move = jQuery('.button_pane_to_move');
					var flash_to_move = jQuery('.ui-dialog-content .flashMessage');
					var button_pane_parent = button_pane_to_move.parent().parent();
					button_pane_to_move.detach();
					flash_to_move.detach();
					button_pane_parent.prepend(flash_to_move);
					button_pane_parent.append(button_pane_to_move);
				},
				dataType: 'json'
			});
		}
	</script>
	<table class="list">
		<tbody>
			<?php $amount_to_add = 0; ?>
			<?php foreach ($account_changes['checked'] as $id => $change): ?>
				<?php $amount_to_add += $account_info['items'][$id]['AccountLineItem']['current_cost']; ?>
				<tr>
					<td class="first">
						<div class="rightborder"></div>
						<span><?php echo $account_info['items'][$id]['AccountLineItem']['name']; ?></span>
					</td>
					<td class="last table_actions">
						<div class="rightborder"></div>
						<span><?php echo $this->Number->currency($account_info['items'][$id]['AccountLineItem']['current_cost']); ?></span>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
		<tfoot>
			<tr>
				<td class="first">
					<div class='cc_info'>
						<?php
							$billing_message = '';
							if ($bill_today < $account_info['Account']['promo_credit_balance'] || empty($payment_profile['data'])) {
								$billing_message = __('Total amount is from fotomatter credit', true);
							} else {
								$billing_message = sprintf(__('Using card ending in %s', true), $payment_profile['data']['AuthnetProfile']['payment_cc_last_four']);
							}
						?>
						<p><?php echo $billing_message; ?></p>
						<p>(<a href='#' onClick='changePaymentData()'>Change/Add Credit Card</a>)</p>
					</div>
				</td>
				<td class="last">
					<div class='table_summary'>
						<div class='payment_item current_bill'>
							<label><?php echo __('Current Bill', true); ?></label>
							<span class='value'><?php echo $this->Number->currency($current_bill); ?></span>
						</div>
						<div class='payment_item new_bill'>
							<label><?php echo __('New Bill', true); ?></label>
							<span class='value'><?php echo $this->Number->currency($current_bill + $amount_to_add); ?></span>
						</div>
						<div class='payment_item due_today'>
							<label><?php echo __('Due Today', true); ?></label>
							<span class='value'><?php echo $this->Number->currency($bill_today); ?></span>
						</div>
					</div>
				</td>
			</tr>
		</tfoot>
	</table>
	<div style='clear:both'></div>
	
	
	<div class="button_pane_to_move ui-dialog-buttonpane ui-widget-content ui-helper-clearfix">
		<div class="ui-dialog-buttonset">
			<button onClick='finishChange()' type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button">
				<span class="ui-button-text"><?php echo __('Finalize Change', true); ?></span>
			</button>
		</div>
	</div>
	
	
	<?php /*
    <a href='#' onClick='changePaymentData()'>Change Payment Details</a>
   <p>Your current bill is <?php echo $current_bill; ?>.</p>
   <?php $amount_to_add = 0; ?>
   <?php if(empty($account_changes['checked']) == false): ?>
   <p>------</p>
   <p>You are adding the following items.</p>
   <ul>
       <?php foreach ($account_changes['checked'] as $id => $change): ?>
       <?php $amount_to_add += $account_info['items'][$id]['AccountLineItem']['current_cost']; ?>
       <li><?php echo $account_info['items'][$id]['AccountLineItem']['name']; ?> - <?php echo $account_info['items'][$id]['AccountLineItem']['current_cost']; ?></li>
       <?php endforeach; ?>
   </ul>
   <p>This will increase your bill by <?php echo $amount_to_add; ?></p>
   <?php endif; ?>
   <?php $amount_to_remove = 0; ?>
   <?php if(empty($account_changes['unchecked']) == false): ?>
   <p>------</p>
   <ul>
       <?php foreach ($account_changes['unchecked'] as $id => $change): ?>
       <?php $amount_to_remove += $account_info['items'][$id]['AccountLineItem']['current_cost']; ?>
       <li><?php echo $account_info['items'][$id]['AccountLineItem']['name']; ?> - <?php echo $account_info['items'][$id]['AccountLineItem']['current_cost']; ?></li>
       <?php endforeach; ?>
   </ul>
   <p>This will decrease your bill by <?php echo $amount_to_remove; ?></p>
   
   <?php endif; ?>
   <p>-----------</p>
   <p>Due Today <?php echo $bill_today; ?></p>
   <p>Your new bill is <?php echo ($current_bill - $amount_to_remove) + $amount_to_add; ?></p>
   <button class="finalize_change" onClick='finishChange()'><?php __('Finalize Change'); ?></button> */ ?>
   
   