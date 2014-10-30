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

<div class="ui-dialog-content ui-widget-content" style="width: auto; min-height: 0px;">
	<h3><?php echo __('Using Available Promotional Balance.'); ?></h3>
	<p><?php echo __('Good News! You have enough fotomatter credit to add these premium features without adding a credit card. You may however choose
		to add your card today so that your new premium features will continue without interuption. '); ?></p>
	<p>
		<span class='title'><?php echo __('Current Promotional Balance', true); ?></span><span class='promo_credit_balance'><?php echo $this->Number->currency($account_info['Account']['promo_credit_balance']); ?></span>
	</p>
	<p>
		<span class='title'><?php echo __('Total Due Today', true); ?></span><span class='due_today'><?php echo $this->Number->currency($bill_today); ?></span>
	</p>
</div>
<div class="ui-dialog-buttonpane ui-widget-content ui-helper-clearfix">
	<div class="ui-dialog-buttonset">
		<button onClick='changePaymentData()' type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button">
			<span class="ui-button-text"><?php echo __('Add Credit Card'); ?></span>
		</button>
		<button type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button">
			<span onClick='finishAccountChange()' class="ui-button-text"><?php echo __('Continue Without Adding'); ?></span>
		</button>
	</div>
</div>

