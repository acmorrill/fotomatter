<script type='text/javascript'>
    $(document).ready(function() {
		$("#edit-billing").click(function() {
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
						title: '<?php echo __('Edit Payment Details', true); ?>'
					});
				}
			});
		});
    });
</script>

<h1><?php echo __('Account Details', true); ?>
	<div id="help_tour_button" class="custom_ui"><?php echo $this->Element('/admin/get_help_button'); ?></div>
</h1>

<div id="account_details_landing">
	<div class="page_content_header generic_basic_settings">
		<p><?php echo __('Change Password', true); ?></p>
		<div style="clear: both;"></div>
	</div>
	<div class="generic_palette_container">
		<div class="fade_background_top"></div>
		<form name='change_password' action='/admin/users/change_password' method='POST'>
			<div class="generic_inner_container">
				<div class="generic_dark_cont fotomatter_form">
					<div class='input'>
						<label for='password_one'><?php echo __('Password', true); ?></label>
						<input type='password' name='password' required />
					</div>
					<div class='input'>
						<label for='password_two'><?php echo __('Confirm Password', true); ?></label>
						<input type='password' name='password_confirm' required />
					</div>
				</div>
			</div>
			<div class="submit save_button javascript_submit">
				<div class="content"><?php echo __('Change Password', true); ?></div>
			</div>
		</form>
	</div>


	<div class="page_content_header generic_basic_settings" style="margin-top: 50px;">
		<p><?php echo __('Billing Details', true); ?></p>
		<div style="clear: both;"></div>
	</div>
	<div class="generic_palette_container custom_ui">
		<div class="fade_background_top"></div>
		<div class="generic_inner_container">
			<div class="generic_dark_cont fotomatter_form thinner">
				<div class="input">
					<label><?php echo __('Monthly Bill', true); ?></label>
					<span class='value'><?php echo $this->Number->currency($accountDetails['monthlyBill']); ?></span>
				</div>

				<?php if ($accountDetails['Account']['next_bill_date'] != null): ?>
					<div class="input">
						<label><?php echo __('Next Billing Date', true); ?></label>
						<span class='value'><?php echo date('M d, Y', strtotime($accountDetails['Account']['next_bill_date'])); ?></span>
					</div>
				<?php endif; ?>

				<div class="input">
					<label><?php echo __('Total Photos', true); ?></label>
					<span class='value'><?php echo $photo_count; ?></span>
				</div>

<!--				<div class="input">
					<label><?php //echo __('Total Pages', true); ?></label>
					<span class='value'><?php echo $site_page_count; ?></span>
				</div>

				<div class="input">
					<label><?php //echo __('Total Galleries', true); ?></label>
					<span class='value'><?php echo $photo_gallery_count; ?></span>
				</div>-->
			</div>
		</div>
		<?php if (empty($accountDetails['AuthnetProfile']) == false && $accountDetails['Account']['next_bill_date'] != null): ?>
			<div id='edit-billing' class="submit save_button add_button">
				<div class="content"><?php echo __('Edit Billing Details', true); ?></div>
				<div class="right_arrow_lines"><div></div></div>
			</div>
		<?php endif; ?>
	</div>

	<h1 style="margin-top: 50px;"><?php echo __('Payment History', true); ?></h1>
	<div id="photo_gallery_list" class="table_container">
		<div class="fade_background_top"></div>
		<div class="table_top"></div>
		<table class='list'>
			<thead>
				<tr>
					<th class="first">
						<div class="content one_line">
							<?php echo __('Payment Amount', true); ?>
						</div>
					</th>
					<th>
						<div class="content one_line">
							<?php echo __('Promo Amount', true); ?>
						</div>
					</th>
					<th>
						<div class="content one_line">
							<?php echo __('Date Processed', true); ?>
						</div>
					</th>
				</tr>
			</thead>
			<tbody>
				<tr class="spacer"><td colspan="6"></td></tr>
				<?php foreach ($accountDetails['orderHistory'] as $order): ?>
					<tr>
						<td class="first">
							<div class="rightborder"></div>
							<span><?php echo $this->Number->currency($order['AuthnetOrder']['total']); ?></span>
						</td>
						<td>
							<div class="rightborder"></div>
							<span><?php echo $this->Number->currency($order['AuthnetOrder']['promo_total']); ?></span>
						</td>
						<td class="last">
							<div class="rightborder"></div>
							<span><?php echo date('M d, Y', strtotime($order['AuthnetOrder']['created'])); ?></span>
						</td>
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