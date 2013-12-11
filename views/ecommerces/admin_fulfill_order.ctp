<?php 
//debug($is_voidable);
//debug($is_refundable);
?>

<?php //debug($authnet_order['AuthnetLineItem']); ?>
<div id="fulfill_order_container">
	<?php if (!empty($authnet_order['AuthnetLineItem'])): ?>
		<script type="text/javascript">
			jQuery(document).ready(function() {
				jQuery('#standard_checkout_button').click(function() {
					jQuery('#standard_checkout_form').submit();
				});
			});
		</script>

		
		<?php //debug($authnet_order['AuthnetLineItem']); ?>

		<table id="cart_table">
			<thead>
				<tr>
					<th class="first">&nbsp;</th>
					<th><?php __('Item'); ?></th>
					<th><?php __('Fulfillment Type'); ?></th>
					<th><?php __('Price'); ?></th>
					<?php /*<th><?php __('Shipping Price'); ?></th> */ ?>
					<th><?php __('Qty'); ?></th>
					<th><?php __('Turnaround Time'); ?></th>
					<th><?php __('Action Items'); ?></th>
					<th class="last"><?php __('Total'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($authnet_order['AuthnetLineItem'] as $key => $order_item_data): ?>
					<tr>
						<td class="first">
							<?php $order_item_img_data = $this->Photo->get_photo_path($order_item_data['foreign_key'], 100, 100, .4, true); ?>
							<img src="<?php echo $order_item_img_data['url']; ?>" <?php echo $order_item_img_data['tag_attributes']; ?> />
						</td>
						<td>
							<?php echo $order_item_data['extra_data']['PhotoPrintType']['print_name']; ?><br />
							<?php echo $order_item_data['extra_data']['CurrentPrintData']['short_side_inches']; ?> x <?php echo $order_item_data['extra_data']['CurrentPrintData']['long_side_feet_inches']; ?>
						</td>
						<td>
							<?php echo ucwords($order_item_data['extra_data']['PhotoPrintType']['print_fulfillment_type']); ?>
						</td>
						<td>
							$<?php echo $order_item_data['unit_cost']; ?> <?php // DREW TODO - make the money format better ?>
						</td>
						<?php /*<td>
							$<?php echo $order_item_data['shipping_price']; ?> <?php // DREW TODO - make the money format better ?>
						</td> */ ?>
						<td><?php echo $order_item_data['quantity']; ?></td>
						<td><?php echo $order_item_data['extra_data']['CurrentPrintData']['custom_turnaround']; ?></td>
						<td>cropping etc</td>
						<td class="last">$<?php echo $this->Cart->get_cart_line_total($order_item_data['quantity'], $order_item_data['unit_cost']); ?><?php // DREW TODO - make the money format better ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="3" style="text-align: left;">
						<?php if ($order_status === 'new'): ?>
							<?php if ($is_voided): ?>
<!--								DREW TODO - finish this-->
								This order was VOIDED
							<?php elseif ($is_refunded): ?>
<!--								DREW TODO - finish this-->
								This order was REFUNDED
							<?php else: ?>
								<a href="/admin/ecommerces/approve_order/<?php echo $authnet_order['AuthnetOrder']['id']; ?>/">Approve Order</a>
								<?php if ($is_voidable): ?>
									<a href="/admin/ecommerces/void_order/<?php echo $authnet_order['AuthnetOrder']['id']; ?>/">Void Order</a>
								<?php endif; ?>
								<?php if ($is_refundable): ?>
									<a href="/admin/ecommerces/refund_order/<?php echo $authnet_order['AuthnetOrder']['id']; ?>/">Refund Order</a>
								<?php endif; ?>
							<?php endif; ?>
						<?php else: ?>
							This order was APPROVED
							<?php // DREW TODO - setup the after approved options (mark shipped etc)  ?>
						<?php endif; ?>
					</td>
					<td colspan="5" style="text-align: right;">
						Shipping: $<?php echo $authnet_order['AuthnetOrder']['shipping']; ?><br />
						Sub Total: $<?php echo $authnet_order['AuthnetOrder']['total'] - $authnet_order['AuthnetOrder']['shipping']; ?><br />
						Total: $<?php echo $authnet_order['AuthnetOrder']['total']; ?><br />
					</td>
				</tr>
			</tfoot>
		</table>
	<?php else: ?>
		This is a major error (DREW TODO - finish this)
	<?php endif; ?>
</div>



<?php ob_start(); ?>
<ol>
	<li>This page shows the details of an order and lets you approve, void or refund it</li>
	<li>Let me know if you need fake orders for this page</li>
	<li>Things to remember
		<ol>
			<li>This page needs a flash message</li>
			<li>The void can only happen before an order has finished on authnet side</li>
			<li>After the void period only a refund can be done</li>
			<li>After an order has been approved it can be payed on</li>
			<li>An order can only be voided or refunded if it hasn't been approved</li>
			<li>We need a design for all the states
				<ol>
					<li>new order state</li>
					<li>voided state</li>
					<li>refunded state</li>
					<li>approved state</li>
				</ol>
			</li>
		</ol>
	</li>
</ol>
<?php
$html = ob_get_contents();
ob_end_clean();
	echo $this->Element('admin/richard_notes', array(
	'html' => $html
)); ?>