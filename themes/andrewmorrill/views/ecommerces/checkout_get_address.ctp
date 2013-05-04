<?php echo $this->Session->flash(); ?>

<script type="text/javascript">
	jQuery(document).ready(function() {
		function country_select_reset(context, country_id, first_load) {
			if (country_id !== 'empty_option') {
				var state_cont = jQuery(context).closest('.address_cont').find('.state');
				var state_select = jQuery('.state_select', state_cont);
				var url = '/ecommerces/get_available_states_for_country_options/'+country_id+'/';
				if (first_load) {
					var start_state_id = state_select.attr('first_load_id');
					url += start_state_id;
				} 
				
				jQuery.ajax({
					type: 'post',
					url: url,
					data: {},
					success: function(state_data) {
						if (state_data.count == 0) {
							state_cont.hide();
							state_select.html(state_data.html);
						} else {
							state_select.html(state_data.html);
							state_cont.show();
						}
					},
					complete: function() {
//						console.log ("complete");
					},
					error: function(jqXHR, textStatus, errorThrown) {
//						console.log ("error");
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
		
		var shipping_data_cont = jQuery('.shipping_data_cont');
		var same_as_billing_callback = function() {
			if (jQuery(this).prop('checked')) {
				shipping_data_cont.hide();
			} else {
				shipping_data_cont.show();
			}
		};
		jQuery('.same_as_billing input').each(same_as_billing_callback);
		jQuery('.same_as_billing input').change(same_as_billing_callback);
	});
</script>

<?php $billing_address = $this->Ecommerce->get_cart_billing_address(); ?>
<?php $shipping_address = $this->Ecommerce->get_cart_shipping_address(); ?>

<form action="/ecommerces/checkout_get_address" method="post">
	<div id="billing_address_container" class="address_cont">
		<h1><?php __('Billing Address'); ?></h1>
		<div class="input firstname">
			<label><?php __('First Name'); ?>:</label> <input type="text" name="data[BillingAddress][firstname]" value="<?php if (isset($billing_address['firstname'])): ?><?php echo $billing_address['firstname']; ?><?php endif; ?>" /><br/>
		</div>
		<div class="input lastname">
			<label><?php __('Last Name'); ?>:</label> <input type="text" name="data[BillingAddress][lastname]" value="<?php if (isset($billing_address['lastname'])): ?><?php echo $billing_address['lastname']; ?><?php endif; ?>" /><br/>
		</div>
		<div class="input address1">
			<label><?php __('Address'); ?>:</label> <input type="text" name="data[BillingAddress][address1]" value="<?php if (isset($billing_address['address1'])): ?><?php echo $billing_address['address1']; ?><?php endif; ?>" /><br/>
		</div>
		<div class="input address2">
			<label>&nbsp;</label> <input type="text" name="data[BillingAddress][address2]" value="<?php if (isset($billing_address['address2'])): ?><?php echo $billing_address['address2']; ?><?php endif; ?>" /> (optional)
		</div>
		<div class="input city">
			<label><?php __('City'); ?>:</label> <input type="text" name="data[BillingAddress][city]" value="<?php if (isset($billing_address['city'])): ?><?php echo $billing_address['city']; ?><?php endif; ?>" />
		</div>
		<div class="input zip">
			<label><?php __('Zip Code'); ?>:</label> <input type="text" name="data[BillingAddress][zip]" value="<?php if (isset($billing_address['zip'])): ?><?php echo $billing_address['zip']; ?><?php endif; ?>" />
		</div>
		<div class="select country">
			<?php $countries = $this->Ecommerce->get_available_countries(); ?>
			<label><?php __('Country'); ?>:</label> 
			<select class="country_select" name="data[BillingAddress][country_id]">
				<option class="empty_option" value=""><?php __('Choose a Country'); ?></option>
				<?php foreach ($countries as $country): ?>
					<option value="<?php echo $country['GlobalCountry']['id']; ?>" <?php if (isset($billing_address['country_id']) && $billing_address['country_id'] == $country['GlobalCountry']['id']): ?>selected="selected"<?php endif; ?>><?php echo $country['GlobalCountry']['country_name']; ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="select state">
			<label><?php __('State'); ?>:</label>
			<select class="state_select" name="data[BillingAddress][state_id]" first_load_id="<?php echo isset($billing_address['state_id']) ? $billing_address['state_id'] : ''; ?>" >
				<option value="no_state">&nbsp;</option>
			</select>
		</div>
		<div class="input phone">
			<label><?php __('Phone Number'); ?>:</label> <input type="text" name="data[BillingAddress][phone]" value="<?php if (isset($billing_address['phone'])): ?><?php echo $billing_address['phone']; ?><?php endif; ?>" /> (optional)
		</div>
	</div>
	<div id="shipping_address_container" class="address_cont">
		<h1><?php __('Shipping Address'); ?></h1>
		<div class="input same_as_billing">
			<label><?php __('Same as Billing'); ?>:</label> <input type="checkbox" name="data[ShippingAddress][same_as_billing]" <?php if (isset($shipping_address['same_as_billing']) && $shipping_address['same_as_billing'] == '1'): ?>checked="checked"<?php endif; ?> /><br/>
		</div>
		<div class="shipping_data_cont">
			<div class="input first_name">
				<label><?php __('First Name'); ?>:</label> <input type="text" name="data[ShippingAddress][firstname]" value="<?php if (isset($shipping_address['firstname'])): ?><?php echo $shipping_address['firstname']; ?><?php endif; ?>" /><br/>
			</div>
			<div class="input last_name">
				<label><?php __('Last Name'); ?>:</label> <input type="text" name="data[ShippingAddress][lastname]"  value="<?php if (isset($shipping_address['lastname'])): ?><?php echo $shipping_address['lastname']; ?><?php endif; ?>" /><br/>
			</div>
			<div class="input address1">
				<label><?php __('Address'); ?>:</label> <input type="text" name="data[ShippingAddress][address1]"  value="<?php if (isset($shipping_address['address1'])): ?><?php echo $shipping_address['address1']; ?><?php endif; ?>" /><br/>
			</div>
			<div class="input address2">
				<label>&nbsp;</label> <input type="text" name="data[ShippingAddress][address2]"  value="<?php if (isset($shipping_address['address2'])): ?><?php echo $shipping_address['address2']; ?><?php endif; ?>" /> (optional)
			</div>
			<div class="input city">
				<label><?php __('City'); ?>:</label> <input type="text" name="data[ShippingAddress][city]"  value="<?php if (isset($shipping_address['city'])): ?><?php echo $shipping_address['city']; ?><?php endif; ?>" />
			</div>
			<div class="input zip">
				<label><?php __('Zip Code'); ?>:</label> <input type="text" name="data[ShippingAddress][zip]"  value="<?php if (isset($shipping_address['zip'])): ?><?php echo $shipping_address['zip']; ?><?php endif; ?>" />
			</div>
			<div class="select country">
				<?php $countries = $this->Ecommerce->get_available_countries(); ?>
				<label><?php __('Country'); ?>:</label> 
				<select class="country_select" name="data[ShippingAddress][country_id]">
					<option class="empty_option" value=""><?php __('Choose a Country'); ?></option>
					<?php foreach ($countries as $country): ?>
						<option value="<?php echo $country['GlobalCountry']['id']; ?>" <?php if (isset($shipping_address['country_id']) && $shipping_address['country_id'] == $country['GlobalCountry']['id']): ?>selected="selected"<?php endif; ?>><?php echo $country['GlobalCountry']['country_name']; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="select state">
				<label><?php __('State'); ?>:</label>
				<select class="state_select" name="data[ShippingAddress][state_id]" first_load_id="<?php echo isset($shipping_address['state_id']) ? $shipping_address['state_id'] : ''; ?>" >
					<option value="no_state">&nbsp;</option>
				</select>
			</div>
		</div>
	</div>
	
	<div class="no_label">
		<input type="submit" value="<?php __('Next'); ?>" />
	</div>
</form>


