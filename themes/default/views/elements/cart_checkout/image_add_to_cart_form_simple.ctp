<?php if (!empty($current_on_off_features['basic_shopping_cart'])): ?>
	<?php
		if (empty($photo_sellable_prints)) {
			$photo_sellable_prints = $this->Photo->get_enabled_photo_sellable_prints($photo_id);
		}
	?>
	<script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery('#add_to_cart_buttons_cont .submit_button_cont').click(function() { 
				jQuery(this).closest('form').submit();
			});
		});
	</script>

	<div id="add_to_cart_buttons_cont">
	<?php if (empty($photo_sellable_prints)): ?>
		<!-- <?php __('The add to cart buttons have not been fully setup'); ?> -->
	<?php else: ?>
		<?php //debug($photo_sellable_prints); ?>
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
					<button class="submit_inner"><?php echo isset($submit_button_text) ? $submit_button_text : __('Submit', true); ?></button>
				</div>
			</form>
		<?php endforeach; ?>
	<?php endif; ?>
	</div>
<?php endif; ?>


