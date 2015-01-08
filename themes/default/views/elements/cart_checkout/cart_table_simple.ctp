<?php $cart_items = $this->Cart->get_cart_items(); ?>

<?php //debug($cart_items); ?>

	<script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery('#standard_checkout_button').click(function(e) {
				jQuery('#standard_checkout_form').submit();
			});
			
			jQuery('#update_cart_button').click(function(e) {
				jQuery('#update_cart_form .update_cart_hidden_field').remove();
				jQuery('#cart_table tbody tr').each(function() {
					var cart_item_key = jQuery(this).attr('data-cart_item_key');
					var cart_item_qty = jQuery('.qty input', this).val();
					jQuery('#update_cart_form').prepend('<input class="update_cart_hidden_field" type="hidden" value="' + cart_item_qty + '" name="data[cart_items][' + cart_item_key + ']" />');
				});
				jQuery('#update_cart_form').submit();
			});
		});
	</script>


	<table id="cart_table">
		<thead>
			<tr>
				<th class="first image"><?php echo __('Item',true); ?></th>
				<th class='price'><?php echo __('Price',true); ?></th>
				<?php /*<th><?php __('Shipping Price'); ?></th> */ ?>
				<th class='qty'><?php echo __('Qty',true); ?></th>
				<th class="total"><?php echo __('Total',true); ?></th>
				<th class="last total">&nbsp;</th>
			</tr>
		</thead>
		<tbody>
			<?php if (!empty($cart_items)): ?>
				<?php $count = 0; foreach ($cart_items as $key => $cart_data): ?>
					<tr data-cart_item_key="<?php echo $key; ?>">
						<td class="first image">
							<?php $cart_img_data = $this->Photo->get_photo_path($cart_data['photo_id'], 100, 100, .4, true); ?>
							<img src="<?php echo $cart_img_data['url']; ?>" <?php echo $cart_img_data['tag_attributes']; ?> alt="" />
						</td>
						<td class='price'>
							$<?php echo $cart_data['price']; ?> <?php // DREW TODO - make the money format better ?>
						</td>
						<?php /*<td>
							$<?php echo $cart_data['shipping_price']; ?> <?php // DREW TODO - make the money format better ?>
						</td> */ ?>
						<td class='qty'><input type="text" value="<?php echo $cart_data['qty']; ?>" /></td>
						<td class="last total">$<?php echo $this->Cart->get_cart_line_total($cart_data['qty'], $cart_data['price']); ?><?php // DREW TODO - make the money format better ?></td>
						<td class='delete'><a class="icon-trash-01" href="/ecommerces/remove_cart_item_by_index/<?php echo $count; ?>"></a></td>
					</tr>
				<?php $count++; endforeach; ?>
			<?php else: ?>
				<tr><td colspan="6" style="text-align: center; height: 60px;"><?php echo __('Cart Empty', true); ?></td></tr>
			<?php endif; ?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="2" style="text-align: left; vertical-align: top;">
					<?php if (!empty($cart_items)): ?>
						<form id="update_cart_form" action="/ecommerces/update_cart_qty" method="post"></form>
						<button id="update_cart_button"><?php echo __('Update', true); ?></button>
					<?php endif; ?>
				</td>
				<td colspan="4" style="text-align: right;">
						Shipping: $<?php echo $this->Cart->get_cart_shipping_total(); ?><br />
						Sub Total: $<?php echo $this->Cart->get_cart_subtotal(); ?><br />
						Total: $<?php echo $this->Cart->get_cart_total(); ?><br />

					<?php if (!empty($cart_items)): ?>
						<?php if (!isset($hide_checkout) || $hide_checkout !== true): ?>
							<form id="standard_checkout_form" action="/ecommerces/checkout_login_or_guest" method="post"></form>
							<button id="standard_checkout_button"><?php echo __('Checkout', true); ?></button>
						<?php endif; ?>
					<?php endif; ?>
				</td>
			</tr>
		</tfoot>
	</table>
