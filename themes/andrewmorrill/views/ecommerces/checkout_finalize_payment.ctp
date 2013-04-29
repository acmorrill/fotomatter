<?php echo $this->Session->flash(); ?>


<form action="" method="post">

	<div id="create_account">
		<h1><?php __('Create Account'); ?> (Optional)</h1>
		<div class="input email">
			<label><?php __('Email Address'); ?>:</label> 
			<input type="text" name="data[User][email_address]" />
		</div>
		<div class="input password">
			<label><?php __('Password'); ?>:</label> 
			<input type="password" name="data[User][password]" />
		</div>
		<div class="input password_repeat">
			<label>&nbsp;</label> 
			<input type="password" name="data[User][repeat_password]" />
		</div>
	</div>

	<div id="final_payment_info">
		<h1><?php __('Payment Info'); ?></h1>
		<div class="input name_on_card">
			<label><?php __('Name On Card'); ?>:</label> 
			<input type="text" name="data[Payment][name_on_card]" />
		</div>
		<div class="select credit_card_type">
			<label><?php __('Payment Method'); ?>:</label> 
			<select name="data[Payment][credit_card_method]">
				<option type="visa">Visa</option>
				<option type="mastercard">Mastercard</option>
				<option type="discover">Discover</option>
				<option type="amex">American Express</option>
			</select>
		</div>
		<div class="input card_number">
			<label><?php __('Card Number'); ?>:</label>
			<input type="text" name="data[Payment][card_number]" />
		</div>
		<div class="input card_expiration">
			<label><?php __('Expiration'); ?>:</label>
			<select name="data[Payment][expiration_month]">
				<?php for ($m = 1; $m <= 12; $m++): ?>
					<?php $month_name = date("M", mktime(0, 0, 0, $m, 10)); ?>
					<option value="<?php echo $m; ?>"><?php echo $month_name; ?></option>
				<?php endfor; ?>
			</select>
			<select name="data[Payment][expiration_year]">
				<?php $curr_year = (int) date('Y'); for ($y = $curr_year; $y <= $curr_year + 20; $y++): ?>
					<option value="<?php echo $y; ?>"><?php echo $y; ?></option>
				<?php endfor; ?>
			</select>
		</div>
		<div class="input card_security_code">
			<label><?php __('Security Code'); ?>:</label>
			<input type="text" name="data[Payment][security_code]" />
		</div>
	</div>

	<br />
	<button><?php __('Pay Now'); ?></button>
	
	
</form>

<br />
<hr />
<h1><?php __('Order Summary'); ?></h1>
<?php echo $this->Element('cart_checkout/cart_table_summary', array(
	'hide_checkout' => true
)); ?>

<?php echo $this->Element('cart_checkout/billing_address_summary'); ?>	
<?php echo $this->Element('cart_checkout/shipping_address_summary'); ?>	
	
	
	