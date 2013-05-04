<?php $cart_items = $this->Cart->get_cart_items(); ?>

<?php //debug($cart_items); ?>

<?php if (!empty($cart_items)): ?>
	<script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery('#standard_checkout_button').click(function() {
				jQuery('#standard_checkout_form').submit();
			});
		});
	</script>


	<table id="cart_table">
		<thead>
			<tr>
				<th class="first"><?php __('Item'); ?></th>
				<th><?php __('Price'); ?></th>
				<?php /*<th><?php __('Shipping Price'); ?></th> */ ?>
				<th><?php __('Qty'); ?></th>
				<th class="last"><?php __('Total'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($cart_items as $key => $cart_data): ?>
				<tr>
					<td class="first">
						<?php $cart_img_data = $this->Photo->get_photo_path($cart_data['photo_id'], 100, 100, .4, true); ?>
						<img src="<?php echo $cart_img_data['url']; ?>" <?php echo $cart_img_data['tag_attributes']; ?> />
					</td>
					<td>
						$<?php echo $cart_data['price']; ?> <?php // DREW TODO - make the money format better ?>
					</td>
					<?php /*<td>
						$<?php echo $cart_data['shipping_price']; ?> <?php // DREW TODO - make the money format better ?>
					</td> */ ?>
					<td><?php echo $cart_data['qty']; ?></td>
					<td class="last">$<?php echo $this->Cart->get_cart_line_total($cart_data['qty'], $cart_data['price']); ?><?php // DREW TODO - make the money format better ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="5" style="text-align: right;">
					Shipping: $<?php echo $this->Cart->get_cart_shipping_total(); ?><br />
					Sub Total: $<?php echo $this->Cart->get_cart_subtotal(); ?><br />
					Total: $<?php echo $this->Cart->get_cart_total(); ?><br />
					
					<?php if (!isset($hide_checkout) || $hide_checkout !== true): ?>
						<form id="standard_checkout_form" action="/ecommerces/checkout_login_or_guest" method="post">
							<button id="standard_checkout_button">Checkout</button>
						</form>
					<?php endif; ?>
				</td>
			</tr>
		</tfoot>
	</table>
<?php else: ?>
	The cart is empty (DREW TODO - finish this)
<?php endif; ?>
