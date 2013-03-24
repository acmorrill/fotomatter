<?php if (!empty($cart_items)): ?>
	<table id="cart_table">
		<thead>
			<tr>
				<th class="first"><?php __('Item'); ?></th>
				<th><?php __('Price'); ?></th>
				<th><?php __('Shipping Price'); ?></th>
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
					<td>
						$<?php echo $cart_data['shipping_price']; ?> <?php // DREW TODO - make the money format better ?>
					</td>
					<td><?php echo $cart_data['qty']; ?></td>
					<td class="last">$<?php echo $this->Cart->get_cart_line_total($cart_data['qty'], $cart_data['price'], $cart_data['shipping_price']); ?><?php // DREW TODO - make the money format better ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="5" style="text-align: right;">$<?php echo $this->Cart->get_cart_total($cart_datas['items']); ?></td>
			</tr>
		</tfoot>
	</table>
<?php else: ?>
	The cart is empty (DREW TODO - finish this)
<?php endif; ?>
