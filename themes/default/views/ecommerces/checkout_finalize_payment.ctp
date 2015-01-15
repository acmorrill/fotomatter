<?php echo $this->Session->flash(); ?>

<?php 
	$CreateAccount = array();
	if (isset($this->data['CreateAccount'])) {
		$CreateAccount = $this->data['CreateAccount'];
	}
	
	$Payment = array();
	if (isset($this->data['Payment'])) {
		$Payment = $this->data['Payment'];
	}
?>




<?php $billing_address = $this->Ecommerce->get_cart_billing_address(); ?>

<form action="/ecommerces/checkout_finalize_payment" method="post">
	
	<?php if ($logged_in !== true): ?>
		<div id="create_account">
			<h1><?php echo __('Create Account',true); ?> (Optional)</h1>
			<div class="submit">
				<p>If you fill out the fields below an account will automatically be created when you checkout</p>
			</div>
			<div class="input email">
				<label><?php echo __('Email Address',true); ?></label> 
				<input autocomplete="off" type="text" name="data[CreateAccount][email_address]" value="<?php if (isset($CreateAccount['email_address'])): ?><?php echo $CreateAccount['email_address']; ?><?php endif; ?>" />
			</div>
			<div class="input password">
				<label><?php echo __('Password',true); ?></label> 
				<input autocomplete="off" type="password" name="data[CreateAccount][password]" value="<?php if (isset($CreateAccount['password'])): ?><?php echo $CreateAccount['password']; ?><?php endif; ?>" />
			</div>
			<div class="input password_repeat">
				<label><?php echo __('Repeat',true); ?></label> 
				<input autocomplete="off" type="password" name="data[CreateAccount][repeat_password]" value="<?php if (isset($CreateAccount['repeat_password'])): ?><?php echo $CreateAccount['repeat_password']; ?><?php endif; ?>" />
			</div>
			<hr />
		</div>
	<?php endif; ?>

	<script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery('#edit_payment_data').click(function(e) {
				e.preventDefault();
				
				jQuery('#final_payment_info .payment_display').hide();
				jQuery('#final_payment_info .payment_edit').show();
			});
		});
	</script>
	
	<div id="final_payment_info">
		<div class="payment_display" <?php if ($logged_in !== true): ?>style="display: none;"<?php endif; ?>>
			<div class="input">
				<div class="frontend_form_submit_button submit_button final_checkout_button"><span class='content'><?php echo __('Finish Purchase', true); ?></span><span class='extra'></span></div>
			</div>
			<hr />
			<h1><?php echo __('Payment Info', true); ?> (<a id="edit_payment_data" style="cursor: pointer;">edit</a>)</h1>
			<span>Your card ending in <?php echo $Payment['last_four']; ?> will be charged</span> <?php echo $this->Number->currency($this->Cart->get_cart_total()); ?><br />
			<?php if (!empty($billing_address['firstname'])): ?><span><?php echo $billing_address['firstname']; ?></span><?php endif; ?> <?php if (!empty($billing_address['lastname'])): ?><span><?php echo $billing_address['lastname']; ?></span><?php endif; ?><br />
			<?php if (!empty($billing_address['address1'])): ?><span><?php echo $billing_address['address1']; ?></span><br /><?php endif; ?>
			<?php if (!empty($billing_address['address2'])): ?><span><?php echo $billing_address['address2']; ?></span><br /><?php endif; ?>
			<?php if (!empty($billing_address['city'])): ?><span><?php echo $billing_address['city']; ?></span><br /><?php endif; ?>
			<?php if (!empty($billing_address['zip'])): ?><span><?php echo $billing_address['zip']; ?></span><br /><?php endif; ?>
			<?php if (!empty($billing_address['country_id'])): ?><span><?php echo $this->Ecommerce->get_country_name_by_id($billing_address['country_id']); ?></span><br /><?php endif; ?>
			<?php if (!empty($billing_address['state_id'])): ?><span><?php echo $this->Ecommerce->get_state_name_by_id($billing_address['state_id']); ?></span><br /><?php endif; ?>
			<?php if (!empty($billing_address['phone'])): ?><span><?php echo $billing_address['phone']; ?></span><br /><?php endif; ?>
			<?php if (!empty($Payment['credit_card_method'])): ?><span><?php echo ucfirst($Payment['credit_card_method']); ?></span><br /><?php endif; ?>
		</div>
		<div class="payment_edit" <?php if ($logged_in === true): ?>style="display: none;"<?php endif; ?>>
			<h1><?php echo __('Payment Info',true); ?></h1>
			<div class="address_cont">
				<div class="input firstname">
					<label><?php echo __('First Name',true); ?></label> <input type="text" name="data[BillingAddress][firstname]" value="<?php if (isset($billing_address['firstname'])): ?><?php echo $billing_address['firstname']; ?><?php endif; ?>" /><br/>
				</div>
				<div class="input lastname">
					<label><?php echo __('Last Name',true); ?></label> <input type="text" name="data[BillingAddress][lastname]" value="<?php if (isset($billing_address['lastname'])): ?><?php echo $billing_address['lastname']; ?><?php endif; ?>" /><br/>
				</div>
				<div class="input address1">
					<label><?php echo __('Address',true); ?></label> <input type="text" name="data[BillingAddress][address1]" value="<?php if (isset($billing_address['address1'])): ?><?php echo $billing_address['address1']; ?><?php endif; ?>" /><br/>
				</div>
				<div class="input address2">
					<label>&nbsp;</label> <input type="text" name="data[BillingAddress][address2]" value="<?php if (isset($billing_address['address2'])): ?><?php echo $billing_address['address2']; ?><?php endif; ?>" /> (optional)
				</div>
				<div class="input city">
					<label><?php echo __('City',true); ?></label> <input type="text" name="data[BillingAddress][city]" value="<?php if (isset($billing_address['city'])): ?><?php echo $billing_address['city']; ?><?php endif; ?>" />
				</div>
				<div class="input zip">
					<label><?php echo __('Zip Code',true); ?></label> <input type="text" name="data[BillingAddress][zip]" value="<?php if (isset($billing_address['zip'])): ?><?php echo $billing_address['zip']; ?><?php endif; ?>" />
				</div>
				<div class="select country">
					<?php $countries = $this->Ecommerce->get_available_countries(); ?>
					<label><?php echo __('Country',true); ?></label> 
					<select class="country_select" name="data[BillingAddress][country_id]">
						<option class="empty_option" value=""><?php echo __('Choose a Country',true); ?></option>
						<?php foreach ($countries as $country): ?>
							<option value="<?php echo $country['GlobalCountry']['id']; ?>" <?php if (isset($billing_address['country_id']) && $billing_address['country_id'] == $country['GlobalCountry']['id']): ?>selected="selected"<?php endif; ?>><?php echo substr($country['GlobalCountry']['country_name'], 0, 30); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="select state">
					<label><?php echo __('State',true); ?></label>
					<select class="state_select" name="data[BillingAddress][state_id]" first_load_id="<?php echo isset($billing_address['state_id']) ? $billing_address['state_id'] : ''; ?>" >
						<option value="no_state">&nbsp;</option>
					</select>
				</div>
			</div>
			<div class="input phone">
				<label><?php echo __('Phone Number',true); ?></label> <input type="text" name="data[BillingAddress][phone]" value="<?php if (isset($billing_address['phone'])): ?><?php echo $billing_address['phone']; ?><?php endif; ?>" /> (optional)
			</div>

			<div class="select credit_card_type">
				<label><?php echo __('Payment Method',true); ?></label> 
				<select name="data[Payment][credit_card_method]">
					<option value="visa" <?php if (isset($Payment['credit_card_method']) && $Payment['credit_card_method'] === 'visa'): ?>selected="selected"<?php endif; ?> >Visa</option>
					<option value="mastercard" <?php if (isset($Payment['credit_card_method']) && $Payment['credit_card_method'] === 'mastercard'): ?>selected="selected"<?php endif; ?> >Mastercard</option>
					<option value="discover" <?php if (isset($Payment['credit_card_method']) && $Payment['credit_card_method'] === 'discover'): ?>selected="selected"<?php endif; ?> >Discover</option>
					<option value="amex" <?php if (isset($Payment['credit_card_method']) && $Payment['credit_card_method'] === 'amex'): ?>selected="selected"<?php endif; ?> >American Express</option>
				</select>
			</div>
			<div class="input card_number">
				<label><?php echo __('Card Number',true); ?></label>
				<input type="text" name="data[Payment][card_number]" value="" />
			</div>
			<div class="input card_expiration">
				<label><?php echo __('Expiration',true); ?></label>
				<select name="data[Payment][expiration_month]">
					<?php for ($m = 1; $m <= 12; $m++): ?>
						<?php $month_name = date("M", mktime(0, 0, 0, $m, 10)); ?>
						<option value="<?php echo $m; ?>" <?php if (isset($Payment['expiration_month']) && $Payment['expiration_month'] == $m): ?>selected="selected"<?php endif; ?> ><?php echo $month_name; ?></option>
					<?php endfor; ?>
				</select>
				<select name="data[Payment][expiration_year]">
					<?php $curr_year = (int) date('Y'); for ($y = $curr_year; $y <= $curr_year + 20; $y++): ?>
						<option value="<?php echo $y; ?>" <?php if (isset($Payment['expiration_year']) && $Payment['expiration_year'] == $y): ?>selected="selected"<?php endif; ?> ><?php echo $y; ?></option>
					<?php endfor; ?>
				</select>
			</div>
			<div class="input card_security_code">
				<label><?php echo __('Security Code',true); ?></label>
				<input type="text" name="data[Payment][security_code]" value="<?php if (isset($Payment['security_code'])): ?><?php echo $Payment['security_code']; ?><?php endif; ?>" />
			</div>
			<div class="submit">
				<div class="frontend_form_submit_button submit_button final_checkout_button"><span class='content'><?php echo __('Finish Purchase', true); ?></span><span class='extra'></span></div>
			</div>
		</div>
	</div>
</form>

<div id="order_summary_container">
	<br />
	<hr />
	<h1><?php echo __('Order Summary',true); ?></h1>
	<?php echo $this->Element('cart_checkout/cart_table_summary', array(
		'hide_checkout' => true
	)); ?>
</div>

<?php //echo $this->Element('cart_checkout/billing_address_summary'); ?>	
<?php echo $this->Element('cart_checkout/shipping_address_summary'); ?>	
	
	
	