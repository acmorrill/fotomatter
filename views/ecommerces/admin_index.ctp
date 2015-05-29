<h1><?php echo __('E-commerce Settings', true); ?>
	<div id="help_tour_button" class="custom_ui"><?php echo $this->Element('/admin/get_help_button'); ?></div>
</h1>
<p><?php echo __('This is the amount of sales tax (in US dollars) that will be collected from your users if their shipping address matches your home state and country. If this field is empty, no sales tax will be charged. Valid values are decimals between 0 and 1. Tax settings currently only utilized in the U.S.', true); ?></p>


<div class="page_content_header generic_basic_settings">
	<p><?php echo __('modify settings below', true); ?></p>
	<div style="clear: both;"></div>
</div>
<div class="generic_palette_container">
	<div class="fade_background_top"></div>
	<form method='post' action='/admin/ecommerces/index'>
		<div class="generic_inner_container">
			<div class="generic_dark_cont fotomatter_form">
				<div style="display:none;">
					<input type="hidden" name="_method" value="PUT">
				</div>
				
				<div class="select country">
					<?php $countries = $this->Ecommerce->get_available_countries(); ?>
					<label for="EcommerceHomeCountry"><?php echo __('Your Home Country', true); ?></label>
					<select class="country_select" name="data[site_country_id]" id='EcommerceHomeCountry' data-step="1" data-intro="Your country is used to determine sales tax. For now sales tax is only calculated in US dollars." data-position="top">
						<option class="empty_option" value=""><?php __('Choose Country'); ?></option>
						<?php foreach ($countries as $country): ?>
							<option <?php if (isset($this->data['site_country_id']) && $this->data['site_country_id'] == $country['GlobalCountry']['id']): ?>selected="selected"<?php endif; ?> value="<?php echo $country['GlobalCountry']['id']; ?>"><?php echo $country['GlobalCountry']['country_name']; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				
				<div class="select state">
					<label for="EcommerceHomeState"><?php __('Your Home State'); ?></label>
					<select class="state_select" name="data[site_state_id]" first_load_id="<?php echo isset($this->data['site_state_id']) ? $this->data['site_state_id'] : ''; ?>" id='EcommerceHomeState' data-step="2" data-intro="Your state is also used to determine sales tax. For now sales tax is only calculated in US dollars." data-position="top">
						<option value="no_state">&nbsp;</option>
					</select>
				</div>
				
				<div class="input text">
					<label for="EcommercSalesTax"><?php __('Sales Tax Percentage (as a decimal between 0 and 1)'); ?></label>
					<input name="data[site_sales_tax_percentage]" type="text" maxlength="100" value="<?php if (!empty($this->data['site_sales_tax_percentage'])) { echo $this->data['site_sales_tax_percentage']; } ?>" id="EcommercSalesTax" data-step="3" data-intro="After selecting your home country and state from the dropdown list, enter the amount of sales tax your state charges. Enter it in decimal form between 0 and 1. For example, Utah’s state sales tax is 4.70%; it would be entered .047. " data-position="right">
				</div>
				
			</div>
		</div>
		<div class="submit save_button javascript_submit" data-step="4" data-intro="Don’t forget to save. " data-position="right">
			<div class="content"><?php echo __('Save', true); ?></div>
		</div>
	</form>
</div>



