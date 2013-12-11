<script type='text/javascript'>
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
   
   function finishAccountChange() {
		$.ajax({
			type: 'GET',
			url: '/admin/accounts/ajax_finishLineChange/noCCPromoConfirm:true',
			success: function(data) {
			   var content = $(data.html);
			   $(".ui-dialog-content").html(content);
		   },
		   dataType: 'json'
		});
   }
   
</script>
<div class='promo_notify'>
	<h3><?php echo __('Using Available Promotional Balance.'); ?></h3>
	<p><?php echo __('Good News! You have enough fotomatter credit to add these premium features without adding a credit card. You may however choose
		to add your card today so that your new premium features will continue without interuption. '); ?></p>
	<p>
		<span class='title'><?php echo __('Current Promotional Balance:'); ?></span><span class='promo_credit_balance'><?php echo $this->Number->currency($account_info['Account']['promo_credit_balance']); ?></span>
	</p>
	<p>
		<span class='title'><?php echo __('Total Due Today:'); ?></span><span class='due_today'><?php echo $this->Number->currency($bill_today); ?></span>
	</p>
	<div class='actions'>
		<button onClick='changePaymentData()'><?php echo __('Add Credit Card'); ?></button>
		<button onClick='finishAccountChange()'><?php echo __('Continue Without Adding'); ?></button>
	</div>
</div>
<script type="text/javascript">
