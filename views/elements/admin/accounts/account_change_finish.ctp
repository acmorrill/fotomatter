<script type="text/javascript">
       function finishChange() {
            $.ajax({
               type: 'POST',
               url: "/admin/accounts/ajax_finish_account_change",
               success: function(data) {
                   window.location.reload();
               },
               dataType: 'json'
            });
       }
       
       function changePaymentData() {
            $.ajax({
               type: 'GET',
               url: '/admin/accounts/ajax_update_payment/closeWhenDone:false',
               success: function(data) {
                   $(".ui-dialog-content").html(data.html);
               },
               dataType: 'json'
            });
       }
	   
	   setTimeout(function() {
			$('button.finalize_change').button();
			<?php if($bill_today === false): ?>
					$('button.finalize_change').button('option', 'disabled', true);
		    <?php endif; ?>
	   }, 20);
   </script>
<div id="finish_account_change">
	<?php if ($bill_today === false): ?>
		<?php echo $this->element('admin/flashMessage/error', array('message'=>__('Our system is down right now and we can\'t continue your order, please contact us at support@fotomatter.net.', true))); ?>
	<?php endif; ?>
	<div class='payment_item current_bill'>
		<span class='label'><?php echo __('Current Bill'); ?>:</span><span class='value'><?php echo $this->Number->currency($current_bill); ?></span>
	</div>
	<div class='pending_change'>
		<p><?php echo __('Pending Additions:'); ?></p>	
	</div>
	<div class='change_summary rounded-corners'>
		<?php $amount_to_add = 0; ?>
		<?php foreach ($account_changes['checked'] as $id => $change): ?>
			<?php $amount_to_add += $account_info['items'][$id]['AccountLineItem']['current_cost']; ?>
			<div class='item_to_add'>
				<div class='item_name'><?php echo $account_info['items'][$id]['AccountLineItem']['name']; ?></div>
				<div class='item_cost'><?php echo $this->Number->currency($account_info['items'][$id]['AccountLineItem']['current_cost']); ?></div>
			</div>
		<?php endforeach; ?>
	</div>
	<div class='payment_item due_today'>
		<span class='label'><?php echo __('Due Today'); ?>:</span><span class='value'><?php echo $this->Number->currency($bill_today); ?><span class='cc_source'> (<?php echo $bill_today < $account_info['Account']['promo_credit_balance'] || empty($payment_profile['data']) ? __('Will be subtracted for fotomatter credit.', true) : sprintf(__('Will bill credit card ending in %s.', true), $payment_profile['data']['AuthnetProfile']['payment_cc_last_four']); ?><br><a href='#' onClick='changePaymentData()'>Change/Add Credit Card</a>)</span>
	</div>
	<div class='payment_item new_bill'>
		<span class='label'><?php echo __('New bill'); ?></span><span class='value'><?php echo $this->Number->currency($current_bill + $amount_to_add); ?></span>
	</div>
	<button class="finalize_change" onClick='finishChange()'><?php __('Finalize Change'); ?></button>
	
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
   
   
</div>