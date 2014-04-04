<script type='text/javascript'>
    $(document).ready(function() {
		$('button,input[type=submit]').button();
        $(".edit-billing button").click(function() {
                $.ajax({
                   url: '/admin/accounts/ajax_update_payment/closeWhenDone:true',
                   dataType: 'json',
                   type: 'GET',
                   success: function(data) {
                                var div = $("<div></div>");
                                div.html(data.html);
                                div.dialog({
                                    width:'600',
                                    height:'500',
                                    title: '<?php echo __('Edit Payment Details'); ?>'
                                });
                            }
                });
        });
        
        $(".payment-summary .payment-details button").click(function() {
            
        });
    });
</script>

<div class="clear" id='account-details'>
	<?php echo $this->Element('/admin/get_help_button'); ?>
			<div style="clear: both;"></div> 
	<?php echo $this->Session->flash(); ?>
	<div class='fm_summary_cont small'>
		<div class='table_header_darker'>
			<h2><?php echo __('Change Password'); ?></h2>
		</div>
		<div class='container'>
			<form name='change_password' class='fm_form' action='/admin/users/change_password' method='POST'>
				<div class='input'>
					<label for='password_one'><?php echo __('Password'); ?></label>
					<input type='password' name='password' required />
				</div>
				<div class='input'>
					<label for='password_two'><?php echo __('Confirm Password'); ?></label>
					<input type='password' name='password_confirm' required />
				</div>
				<div class='input submit'>
					<label for='password_two'>&nbsp</label>
					<input type='submit' value='<?php echo __('Change Password'); ?>'>
				</div>
			</form>
		</div>
	</div>
	<div class='fm_summary_cont account_summary'>
		<div class='table_header_darker'>
			<h2><?php echo __('Account Summary'); ?></h2>
		</div>
		<div class='container'>
			<div class='info'>
				<span class='label'><?php echo __('Monthly Bill'); ?>:</span>
				<span class='value'><?php echo $this->Number->currency($accountDetails['monthlyBill']); ?></span>
			</div>
			<?php if ($accountDetails['Account']['next_bill_date'] != null): ?>
			<div class='info'>
				<span class='label'><?php echo __('Next Billing Date'); ?>:</span>
				<span class='value'><?php echo date('M d, Y', strtotime($accountDetails['Account']['next_bill_date'])); ?></span>
			</div>
			<?php endif; ?>
			<div class='info'>
				<span class='label'><?php echo __('Total Photos'); ?>:</span>
				<span class='value'><?php echo $photo_count; ?></span>
			</div>
			<div class='info'>
				<span class='label'><?php echo __('Total Pages'); ?>:</span>
				<span class='value'><?php echo $site_page_count; ?></span>
			</div>
			<div class='info'>
				<span class='label'><?php echo __('Total Galleries'); ?>:</span>
				<span class='value'><?php echo $photo_gallery_count; ?></span>
			</div>
			<?php if (empty($accountDetails['AuthnetProfile']) == false && $accountDetails['Account']['next_bill_date'] != null): ?>
				<div class='info edit-billing'>
					<span class='value'><button style='font-size:15px' class='rounded-corners-tiny'><?php echo __('Edit Billing Details'); ?></button></span>
				</div>
			<?php endif; ?>
		</div>
	</div>


	<div class='fm_summary_cont payment_history'>
		<div class='table_header_darker'>
			<h2><?php echo __('Payment History'); ?></h2>
		</div>
		<div class='container'>
			<table class='list'>
				<tbody>
					<tr>
						<th><?php echo __('Payment Amount'); ?></th>
						<th><?php echo __('Promo Amount'); ?></th>
						<th><?php echo __('Date Processed'); ?></th>
					</tr>
					<?php foreach ($accountDetails['orderHistory'] as $order): ?>
					<tr>
						<td><?php echo $this->Number->currency($order['AuthnetOrder']['total']); ?></td>
						<td><?php echo $this->Number->currency($order['AuthnetOrder']['promo_total']); ?></td>
						<td><?php echo date('M d, Y', strtotime($order['AuthnetOrder']['created'])); ?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>

		</div>
	</div>
		
  <?php echo '';/*  <fieldset class='payment'>
        <legend>Payment Details</legend>
            <?php if (empty($accountDetails['AuthnetProfile'])): ?>
            <div class='payment-summary'>
                <span>Free Account</span>
                <a class='free-link' href='/admin/accounts'>Add Premium Features</a>
            </div>
            <?php else: ?>
            <div class='payment-summary'>
                <div class='edit-details'>
                    <span>Card ending in <?php echo $accountDetails['AuthnetProfile']['payment_cc_last_four']; ?>.<button class='rounded-corners-tiny'>Edit Billing Details</button></span>
                </div>
                <div class='payment-details'>
                    <span>Last payment received January 12, 2013</span><button class='rounded-corners-tiny'>View Payment History</button>
                </div>
            </div>
            <?php endif; ?>
        
    </fieldset>
    <fieldset class='account-summary'>
        <legend>Account Summary</legend>
        
    </fieldset>
    <fieldset class='password-change'>
        <legend>Change Password</legend>
        
    </fieldset> */ ?>
</div>