<?php $cart_items = $this->Cart->get_cart_items(); ?>

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
			<?php $total_cart_items = count($cart_items); ?>
			<?php $count = 0; foreach ($cart_items as $key => $cart_data): ?>
				<tr class="<?php echo $this->Util->get_count_class(($count + 1), $total_cart_items); ?>">
					<td class="first">
						<?php $cart_img_data = $this->Photo->get_photo_path($cart_data['photo_id'], 100, 100, .4, true); ?>
						<img src="<?php echo $cart_img_data['url']; ?>" <?php echo $cart_img_data['tag_attributes']; ?> alt="" />
						<div id='cart_photo_item_data_container'>
							<h2><?php echo $cart_data['photo_print_type_name']; ?></h2>
							<span><?php echo $cart_data['short_side_inches']; ?> x <?php echo $cart_data['long_side_inches']; ?></span>
						</div>
					</td>
					<td>
						<?php echo $this->Number->currency($cart_data['price']); ?>
					</td>
					<td><?php echo $cart_data['qty']; ?></td>
					<td class="last"><?php echo $this->Number->currency($this->Cart->get_cart_line_total($cart_data['qty'], $cart_data['price'])); ?></td>
				</tr>
			<?php $count++; endforeach; ?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="5" style="text-align: right;">
					<label>Items</label> <span class='price_summary_item'><?php echo $this->Number->currency($this->Cart->get_cart_subtotal()); ?></span><br />
					<label>Shipping & Handling</label> <span class='price_summary_item'><?php echo $this->Number->currency($this->Cart->get_cart_shipping_total()); ?></span><br />
					<label>Tax</label> <span class='price_summary_item'><?php echo $this->Number->currency($this->Cart->get_cart_tax()); ?></span><br />
					<label>Total</label> <span class='price_summary_item'><?php echo $this->Number->currency($this->Cart->get_cart_total()); ?></span><br />
					
					<?php if (!isset($hide_checkout) || $hide_checkout !== true): ?>
                                            <form id="standard_checkout_form" action="/ecommerces/checkout_login_or_guest" method="post">
                                                    <button id="standard_checkout_button">Checkout</button>
                                            </form>
					<?php endif; ?>
                                        
                    <?php
//                        $this->Cart->get_cart_shipping_estimate();
                    ?>
				</td>
			</tr>
		</tfoot>
	</table>
<?php endif; ?>
