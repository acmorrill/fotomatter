<?php $cart_items = $this->Cart->get_cart_items(); ?>

	<?php //debug($cart_items); ?>

	<?php echo $this->Session->flash(); ?>

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
				<?php $total_cart_items = count($cart_items); ?>
				<?php $count = 0; foreach ($cart_items as $key => $cart_data): ?>
					<tr class=" <?php echo $this->Util->get_count_class(($count + 1), $total_cart_items); ?> " data-cart_item_key="<?php echo $key; ?>">
						<td class="first image">
							<?php $cart_img_data = $this->Photo->get_photo_path($cart_data['photo_id'], 100, 100, .4, true); ?>
							<img src="<?php echo $cart_img_data['url']; ?>" <?php echo $cart_img_data['tag_attributes']; ?> alt="" />
							<div id='cart_photo_item_data_container'>
								<h2><?php echo $cart_data['photo_print_type_name']; ?></h2>
								<span><?php echo $cart_data['short_side_inches']; ?> x <?php echo $cart_data['long_side_inches']; ?></span>
							</div>
						</td>
						<td class='price'>
							<?php echo $this->Number->currency($cart_data['price']); ?>
						</td>
						<td class='qty'><input type="text" value="<?php echo $cart_data['qty']; ?>" /></td>
						<td class="total"><?php echo $this->Number->currency($this->Cart->get_cart_line_total($cart_data['qty'], $cart_data['price'])); ?><?php // DREW TODO - make the money format better ?></td>
						<td class='last delete'><a class="icon-bin" href="/ecommerces/remove_cart_item_by_index/<?php echo $count; ?>"></a></td>
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
						<form id="update_cart_button_form" action="/ecommerces/update_cart_qty" method="post">
							<div id="update_cart_button" class="submit_button"><span class='content'><?php echo __('Update', true); ?></span><span class='extra'></span></div>
						</form>
						
						<?php 
							$this->Cart->get_cart_shipping_estimate(); 
						?>
					
					<?php endif; ?>
				</td>
				<td colspan="4" style="text-align: right;">
					<label>Items</label> <span class='price_summary_item'><?php echo $this->Number->currency($this->Cart->get_cart_subtotal()); ?></span><br />
					<label>Shipping & Handling</label> <span class='price_summary_item'><?php echo $this->Number->currency($this->Cart->get_cart_shipping_total()); ?></span><br />
					<?php $cart_tax = $this->Cart->get_cart_tax(); ?>
					<?php if (!empty($cart_tax)): ?>
						<label>Tax</label> <span class='price_summary_item'><?php echo $this->Number->currency($cart_tax); ?></span><br />
					<?php endif; ?>
					<label class='cart_total'>Total</label> <span class='price_summary_item cart_total'><?php echo $this->Number->currency($this->Cart->get_cart_total()); ?></span><br />
					<?php if (!empty($cart_items)): ?>
						<?php if (!isset($hide_checkout) || $hide_checkout !== true): ?>
							<form id="standard_checkout_button_form" action="https://<?php echo $system_url; ?>/ecommerces/checkout_login_or_guest" method="post">
								<?php if ($this->Session->check('Cart')): ?>
									<input type='hidden' name='data[Cart]' value='<?php echo base64_encode(serialize($this->Session->read('Cart'))); ?>' />
								<?php endif; ?>
								<div id="standard_checkout_button" class="frontend_form_submit_button submit_button"><span class='content'><?php echo __('Checkout', true); ?></span><span class='extra'></span></div>
							</form>
						<?php endif; ?>
					<?php endif; ?>
					<div id="privacy_and_tos_notice">
						<span><span>View the <a href="https://fotomatter.net/pages/privacy_policy" target="privacy_policy">privacy policy</a><br /> and <a href="https://fotomatter.net/pages/terms_and_conditions" target="terms_and_conditions">terms of service</a></span></span>
					</div>
				</td>
			</tr>
		</tfoot>
	</table>
