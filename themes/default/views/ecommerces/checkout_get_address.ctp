<div style='clear: both;'></div>
<?php //$billing_address = $this->Ecommerce->get_cart_billing_address(); ?>
<?php $shipping_address = $this->Ecommerce->get_cart_shipping_address(); ?>

<div id="shipping_address_container" class="address_cont">
	<form action="/ecommerces/checkout_get_address" method="post">
		<h1><?php __('Shipping Address'); ?></h1>
		<?php /*<div class="input same_as_billing">
			<label><?php __('Same as Billing'); ?></label> <input type="checkbox" name="data[ShippingAddress][same_as_billing]" <?php if (isset($shipping_address['same_as_billing']) && $shipping_address['same_as_billing'] == '1'): ?>checked="checked"<?php endif; ?> /><br/>
		</div> */ ?>
		<?php echo $this->Session->flash(); ?>
		<div class="shipping_data_cont">
			<div class="input first_name">
				<label><?php __('First Name'); ?></label> <input type="text" name="data[ShippingAddress][firstname]" value="<?php if (isset($shipping_address['firstname'])): ?><?php echo $shipping_address['firstname']; ?><?php endif; ?>" /><br/>
			</div>
			<div class="input last_name">
				<label><?php __('Last Name'); ?></label> <input type="text" name="data[ShippingAddress][lastname]"  value="<?php if (isset($shipping_address['lastname'])): ?><?php echo $shipping_address['lastname']; ?><?php endif; ?>" /><br/>
			</div>
			<div class="input address1">
				<label><?php __('Address'); ?></label> <input type="text" name="data[ShippingAddress][address1]"  value="<?php if (isset($shipping_address['address1'])): ?><?php echo $shipping_address['address1']; ?><?php endif; ?>" /><br/>
			</div>
			<div class="input address2">
				<label>&nbsp;</label> <input type="text" name="data[ShippingAddress][address2]"  value="<?php if (isset($shipping_address['address2'])): ?><?php echo $shipping_address['address2']; ?><?php endif; ?>" /> (optional)
			</div>
			<div class="input city">
				<label><?php __('City'); ?></label> <input type="text" name="data[ShippingAddress][city]"  value="<?php if (isset($shipping_address['city'])): ?><?php echo $shipping_address['city']; ?><?php endif; ?>" />
			</div>
			<div class="input zip">
				<label><?php __('Zip Code'); ?></label> <input type="text" name="data[ShippingAddress][zip]"  value="<?php if (isset($shipping_address['zip'])): ?><?php echo $shipping_address['zip']; ?><?php endif; ?>" />
			</div>
			<div class="select country">
				<?php $countries = $this->Ecommerce->get_available_countries(); ?>
				<label><?php __('Country'); ?></label> 
				<select class="country_select" name="data[ShippingAddress][country_id]">
					<option class="empty_option" value=""><?php __('Choose a Country'); ?></option>
					<?php foreach ($countries as $country): ?>
						<option value="<?php echo $country['GlobalCountry']['id']; ?>" <?php if (isset($shipping_address['country_id']) && $shipping_address['country_id'] == $country['GlobalCountry']['id']): ?>selected="selected"<?php endif; ?>><?php echo $country['GlobalCountry']['country_name']; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="select state">
				<label><?php __('State'); ?></label>
				<select class="state_select" name="data[ShippingAddress][state_id]" first_load_id="<?php echo isset($shipping_address['state_id']) ? $shipping_address['state_id'] : ''; ?>" >
					<option value="no_state">&nbsp;</option>
				</select>
			</div>
			<div class="submit">
				<div class="frontend_form_submit_button submit_button"><span class='content'><?php echo __('Next', true); ?></span><span class='extra'></span></div>
			</div>
		</div>
	</form>
	<hr />
</div>


