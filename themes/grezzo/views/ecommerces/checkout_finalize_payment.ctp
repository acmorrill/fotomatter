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

<script type="text/javascript">
	jQuery(document).ready(function() {
		function country_select_reset(context, country_id, first_load) {
			if (country_id !== 'empty_option') {
				var state_cont = jQuery(context).closest('.address_cont').find('.state');
				var state_select = jQuery('.state_select', state_cont);
				var url = '/ecommerces/get_available_states_for_country_options/' + country_id + '/';
				if (first_load) {
					var start_state_id = state_select.attr('first_load_id');
					url += start_state_id;
				}

				jQuery.ajax({
					type: 'post',
					url: url,
					data: {},
					success: function(state_data) {
						console.log("success");
						console.log(state_data.count);
						if (state_data.count == 0) {
							state_cont.hide();
							state_select.html(state_data.html);
						} else {
							state_select.html(state_data.html);
							state_cont.show();
						}
					},
					complete: function() {
						console.log("complete");
					},
					error: function(jqXHR, textStatus, errorThrown) {
						console.log("error");
//						console.log (textStatus);
//						console.log (errorThrown);
					},
					dataType: 'json'
				});
			}
		}

		jQuery('.country_select').each(function() {
			var context = this;
			var country_id = jQuery(context).val();

			country_select_reset(context, country_id, true);
		});
		jQuery('.country_select').change(function() {
			var context = this;
			var country_id = jQuery(context).val();

			country_select_reset(context, country_id, false);
		});

//		var shipping_data_cont = jQuery('.shipping_data_cont');
//		var same_as_billing_callback = function() {
//			if (jQuery(this).prop('checked')) {
//				shipping_data_cont.hide();
//			} else {
//				shipping_data_cont.show();
//			}
//		};
//		jQuery('.same_as_billing input').each(same_as_billing_callback);
//		jQuery('.same_as_billing input').change(same_as_billing_callback);
	});
</script>


<?php $billing_address = $this->Ecommerce->get_cart_billing_address(); ?>

<form action="/ecommerces/checkout_finalize_payment" method="post">

	<?php if ($logged_in !== true): ?>
		<div id="create_account">
			<div id="title_name">
				<h1><?php echo __('Create Account',true); ?> <span>(Optional)</span></h1>
			</div>
			<div class="create_account_cont">				
				<div class="input email">
					<label><?php echo __('Email Address',true); ?>:</label> 
					<input autocomplete="off" type="text" name="data[CreateAccount][email_address]" value="<?php if (isset($CreateAccount['email_address'])): ?><?php echo $CreateAccount['email_address']; ?><?php endif; ?>" />
				</div>
				<div class="input password">
					<label><?php echo __('Password',true); ?>:</label> 
					<input autocomplete="off" type="password" name="data[CreateAccount][password]" value="<?php if (isset($CreateAccount['password'])): ?><?php echo $CreateAccount['password']; ?><?php endif; ?>" />
				</div>
				<div class="input password_repeat">
					<label><?php echo __('Repeat',true); ?></label> 
					<input autocomplete="off" type="password" name="data[CreateAccount][repeat_password]" value="<?php if (isset($CreateAccount['repeat_password'])): ?><?php echo $CreateAccount['repeat_password']; ?><?php endif; ?>" />
				</div>
			</div>
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
			<p>Your card ending in <?php echo $Payment['last_four']; ?> will be charged. (<a id="edit_payment_data" style="cursor: pointer;">edit</a>)</p>
			<h1><?php /* __('Payment Info'); ?></h1>
	  <?php if (!empty($billing_address['firstname'])): ?><?php echo $billing_address['firstname']; ?><?php endif; ?> <?php if (!empty($billing_address['lastname'])): ?><?php echo $billing_address['lastname']; ?><?php endif; ?><br />
	  <?php if (!empty($billing_address['address1'])): ?><?php echo $billing_address['address1']; ?><br /><?php endif; ?>
	  <?php if (!empty($billing_address['address2'])): ?><?php echo $billing_address['address2']; ?><br /><?php endif; ?>
	  <?php if (!empty($billing_address['city'])): ?><?php echo $billing_address['city']; ?><br /><?php endif; ?>
	  <?php if (!empty($billing_address['zip'])): ?><?php echo $billing_address['zip']; ?><br /><?php endif; ?>
	  <?php if (!empty($billing_address['country_id'])): ?><?php echo $this->Ecommerce->get_country_name_by_id($billing_address['country_id']); ?><br /><?php endif; ?>
	  <?php if (!empty($billing_address['state_id'])): ?><?php echo $this->Ecommerce->get_state_name_by_id($billing_address['state_id']); ?><br /><?php endif; ?>
	  <?php if (!empty($billing_address['phone'])): ?><?php echo $billing_address['phone']; ?><br /><?php endif; ?>
	  <?php if (!empty($Payment['credit_card_method'])): ?><?php echo $Payment['credit_card_method']; ?><br /><?php endif; */ ?>
		</div>
		<div class="payment_edit" <?php if ($logged_in === true): ?>style="display: none;"<?php endif; ?>>
			<div id="title_name">
				<h1><?php echo __('Payment Info',true); ?></h1>
			</div>
			<div class="form_outer">
				<div class="address_cont">
					<div class="input firstname">
						<label><?php echo __('First Name',true); ?>:</label> <input type="text" name="data[BillingAddress][firstname]" value="<?php if (isset($billing_address['firstname'])): ?><?php echo $billing_address['firstname']; ?><?php endif; ?>" /><br/>
					</div>
					<div class="input lastname">
						<label><?php echo __('Last Name',true); ?>:</label> <input type="text" name="data[BillingAddress][lastname]" value="<?php if (isset($billing_address['lastname'])): ?><?php echo $billing_address['lastname']; ?><?php endif; ?>" /><br/>
					</div>
					<div class="input address1">
						<label><?php echo __('Address',true); ?>:</label> <input type="text" name="data[BillingAddress][address1]" value="<?php if (isset($billing_address['address1'])): ?><?php echo $billing_address['address1']; ?><?php endif; ?>" /><br/>
					</div>
					<div class="input address2">
						<label>&nbsp;</label> <input type="text" name="data[BillingAddress][address2]" value="<?php if (isset($billing_address['address2'])): ?><?php echo $billing_address['address2']; ?><?php endif; ?>" /> (optional)
					</div>
					<div class="input city">
						<label><?php echo __('City',true); ?>:</label> <input type="text" name="data[BillingAddress][city]" value="<?php if (isset($billing_address['city'])): ?><?php echo $billing_address['city']; ?><?php endif; ?>" />
					</div>
					<div class="input zip">
						<label><?php echo __('Zip Code',true); ?>:</label> <input type="text" name="data[BillingAddress][zip]" value="<?php if (isset($billing_address['zip'])): ?><?php echo $billing_address['zip']; ?><?php endif; ?>" />
					</div>
					<div class="select country">
						<?php $countries = $this->Ecommerce->get_available_countries(); ?>
						<label><?php echo __('Country',true); ?>:</label> 
						<select class="country_select" name="data[BillingAddress][country_id]">
							<option class="empty_option" value=""><?php echo __('Choose a Country',true); ?></option>
							<?php foreach ($countries as $country): ?>
								<option value="<?php echo $country['GlobalCountry']['id']; ?>" <?php if (isset($billing_address['country_id']) && $billing_address['country_id'] == $country['GlobalCountry']['id']): ?>selected="selected"<?php endif; ?>><?php echo $country['GlobalCountry']['country_name']; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="select state">
						<label><?php echo __('State',true); ?>:</label>
						<select class="state_select" name="data[BillingAddress][state_id]" first_load_id="<?php echo isset($billing_address['state_id']) ? $billing_address['state_id'] : ''; ?>" >
							<option value="no_state">&nbsp;</option>
						</select>
					</div>
				</div>
				<div class="form_center">

					<div class="input phone">
						<label><?php echo __('Phone Number',true); ?>:</label> <input type="text" name="data[BillingAddress][phone]" value="<?php if (isset($billing_address['phone'])): ?><?php echo $billing_address['phone']; ?><?php endif; ?>" /> (optional)
					</div>

					<div class="select credit_card_type">
						<label><?php echo __('Payment Method',true); ?>:</label> 
						<select name="data[Payment][credit_card_method]">
							<option value="visa" <?php if (isset($Payment['credit_card_method']) && $Payment['credit_card_method'] === 'visa'): ?>selected="selected"<?php endif; ?> >Visa</option>
							<option value="mastercard" <?php if (isset($Payment['credit_card_method']) && $Payment['credit_card_method'] === 'mastercard'): ?>selected="selected"<?php endif; ?> >Mastercard</option>
							<option value="discover" <?php if (isset($Payment['credit_card_method']) && $Payment['credit_card_method'] === 'discover'): ?>selected="selected"<?php endif; ?> >Discover</option>
							<option value="amex" <?php if (isset($Payment['credit_card_method']) && $Payment['credit_card_method'] === 'amex'): ?>selected="selected"<?php endif; ?> >American Express</option>
						</select>
					</div>
					<div class="input card_number">
						<label><?php echo __('Card Number',true); ?>:</label>
						<input type="text" name="data[Payment][card_number]" value="" />
					</div>
					<div class="input card_expiration">
						<label><?php echo __('Expiration',true); ?>:</label>
						<select name="data[Payment][expiration_month]">
							<?php for ($m = 1; $m <= 12; $m++): ?>
								<?php $month_name = date("M", mktime(0, 0, 0, $m, 10)); ?>
								<option value="<?php echo $m; ?>" <?php if (isset($Payment['expiration_month']) && $Payment['expiration_month'] == $m): ?>selected="selected"<?php endif; ?> ><?php echo $month_name; ?></option>
							<?php endfor; ?>
						</select>
						<select name="data[Payment][expiration_year]">
							<?php $curr_year = (int) date('Y');
							for ($y = $curr_year; $y <= $curr_year + 20; $y++): ?>
								<option value="<?php echo $y; ?>" <?php if (isset($Payment['expiration_year']) && $Payment['expiration_year'] == $y): ?>selected="selected"<?php endif; ?> ><?php echo $y; ?></option>
							<?php endfor; ?>
						</select>
					</div>
					<div class="input card_security_code">
						<label><?php echo __('Security Code',true); ?>:</label>
						<input type="text" name="data[Payment][security_code]" value="<?php if (isset($Payment['security_code'])): ?><?php echo $Payment['security_code']; ?><?php endif; ?>" />
					</div>
				</div> <!--	End from_center-->
			</div> <!---End form--->
		</div>
	</div>

	<br />
	<input class="pay_now_button" type="submit" value="<?php echo __('Pay Now',true); ?>" />


</form>

<br />
<!--<hr />-->
<div id="title_name">
	<h1><?php echo __('Order Summary',true); ?></h1>
</div>
<?php
echo $this->Element('cart_checkout/cart_table_summary', array(
	'hide_checkout' => true
));
?>

<?php //echo $this->Element('cart_checkout/billing_address_summary');  ?>	
<?php echo $this->Element('cart_checkout/shipping_address_summary'); ?>	


