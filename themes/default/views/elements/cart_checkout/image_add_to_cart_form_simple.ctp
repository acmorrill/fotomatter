<?php if (!empty($current_on_off_features['basic_shopping_cart'])): ?>
	<?php
		if (empty($photo_sellable_prints)) {
			$photo_sellable_prints = $this->Photo->get_enabled_photo_sellable_prints($photo_id);
		}
		
		if (empty($beforeHtml)) {
			$beforeHtml = '';
		}
	?>
	<div id="add_to_cart_buttons_cont">
			<?php if (empty($photo_sellable_prints)): ?>
				<!-- <?php __('The add to cart buttons have not been fully setup'); ?> -->
			<?php else: ?>
				<?php echo $beforeHtml; ?>
				<div id="add_to_cart_buttons_inner_cont">
					<h1 id='print_types_heading'><?php echo $this->Util->get_not_empty_theme_setting_or($theme_custom_settings, 'global_photo_page_add_to_cart_text') ?></h1>
					<?php foreach ($photo_sellable_prints as $print_type_name => $print_type_sizes): ?>
						<h2><?php echo $print_type_name; ?></h2>
						<form action="/ecommerces/add_to_cart/" method="post">
							<input type='hidden' name='data[redirect_url]' value='<?php echo isset($redirect_url)? $redirect_url: ''; ?>' />
							<input type="hidden" name="data[Photo][id]" value="<?php echo $photo_id; ?>" />
							<input type="hidden" name="data[PhotoPrintType][id]" value="<?php echo $print_type_sizes['print_type_id']; ?>" />
				<!--			<input type="hidden" name="data[Photo][price]" value="<?php //echo $print_type_sizes['price']; ?>" />
							<input type="hidden" name="data[Photo][shipping_price]" value="<?php //echo $print_type_sizes['shipping_price']; ?>" />
							<input type="hidden" name="data[Photo][long_side_inches]" value="<?php //echo $print_type_sizes['long_side_feet_inches']; ?>" />-->
							<select name="data[Photo][short_side_inches]">
								<?php foreach ($print_type_sizes['items'] as $print_type_size): ?>
									<option value="<?php echo $print_type_size['short_side_inches']; ?>"><?php echo $print_type_size['short_side_inches']; ?> x <?php echo $print_type_size['long_side_feet_inches']; ?> --- <?php echo $this->Number->currency($print_type_size['price']); ?></option>  
								<?php endforeach; ?>
							</select> 
							<div class="submit_button_cont">
								<div class="frontend_form_submit_button submit_button"><span class='content'><?php echo __('Add to Cart', true); ?></span><span class='extra'></span></div>
							</div>
						</form>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
	</div>
<?php endif; ?>


