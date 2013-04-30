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

<form action="/ecommerces/checkout_finalize_payment" method="post">

	<div id="create_account">
		<h1><?php __('Create Account'); ?> (Optional)</h1>
		<div class="input email">
			<label><?php __('Email Address'); ?>:</label> 
			<input type="text" name="data[CreateAccount][email_address]" value="<?php if (isset($CreateAccount['email_address'])): ?><?php echo $CreateAccount['email_address']; ?><?php endif; ?>" />
		</div>
		<div class="input password">
			<label><?php __('Password'); ?>:</label> 
			<input type="password" name="data[CreateAccount][password]" value="<?php if (isset($CreateAccount['password'])): ?><?php echo $CreateAccount['password']; ?><?php endif; ?>" />
		</div>
		<div class="input password_repeat">
			<label><?php __('Repeat'); ?></label> 
			<input type="password" name="data[CreateAccount][repeat_password]" value="<?php if (isset($CreateAccount['repeat_password'])): ?><?php echo $CreateAccount['repeat_password']; ?><?php endif; ?>" />
		</div>
	</div>

	<div id="final_payment_info">
		<h1><?php __('Payment Info'); ?></h1>
		<div class="input name_on_card">
			<label><?php __('Name On Card'); ?>:</label> 
			<input type="text" name="data[Payment][name_on_card]" value="<?php if (isset($Payment['name_on_card'])): ?><?php echo $Payment['name_on_card']; ?><?php endif; ?>" />
		</div>
		<div class="select credit_card_type">
			<label><?php __('Payment Method'); ?>:</label> 
			<select name="data[Payment][credit_card_method]">
				<option value="visa" <?php if (isset($Payment['credit_card_method']) && $Payment['credit_card_method'] === 'visa'): ?>selected="selected"<?php endif; ?> >Visa</option>
				<option value="mastercard" <?php if (isset($Payment['credit_card_method']) && $Payment['credit_card_method'] === 'mastercard'): ?>selected="selected"<?php endif; ?> >Mastercard</option>
				<option value="discover" <?php if (isset($Payment['credit_card_method']) && $Payment['credit_card_method'] === 'discover'): ?>selected="selected"<?php endif; ?> >Discover</option>
				<option value="amex" <?php if (isset($Payment['credit_card_method']) && $Payment['credit_card_method'] === 'amex'): ?>selected="selected"<?php endif; ?> >American Express</option>
			</select>
		</div>
		<div class="input card_number">
			<label><?php __('Card Number'); ?>:</label>
			<input type="text" name="data[Payment][card_number]" value="<?php if (isset($Payment['card_number'])): ?><?php echo $Payment['card_number']; ?><?php endif; ?>" />
		</div>
		<div class="input card_expiration">
			<label><?php __('Expiration'); ?>:</label>
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
			<label><?php __('Security Code'); ?>:</label>
			<input type="text" name="data[Payment][security_code]" value="<?php if (isset($Payment['security_code'])): ?><?php echo $Payment['security_code']; ?><?php endif; ?>" />
		</div>
	</div>

	<br />
	<input type="submit" value="<?php __('Pay Now'); ?>" />
	
	
</form>

<br />
<hr />
<h1><?php __('Order Summary'); ?></h1>
<?php echo $this->Element('cart_checkout/cart_table_summary', array(
	'hide_checkout' => true
)); ?>

<?php echo $this->Element('cart_checkout/billing_address_summary'); ?>	
<?php echo $this->Element('cart_checkout/shipping_address_summary'); ?>	
	
	
	