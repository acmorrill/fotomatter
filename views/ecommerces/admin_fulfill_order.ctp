<?php 
//debug($is_voidable);
//debug($is_refundable);
?>
<h1><?php __('Manage Order'); ?>
	<?php echo $this->Element('/admin/get_help_button'); ?>
</h1>
<p>
	Some awesome text about managing this. Kent needs serious help.
</p>
<div style="clear: both;"></div> 
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
		<div class="table_container">
			<div class="fade_background_top"></div>
			<div class="table_top"></div>
			<table class="list">
				<thead>
					<tr>
						<th class="first"></th>
						<th>
							<div class="content one_line">
								<div class="direction_arrow"></div>
								<?php __('Item'); ?>
							</div>
						</th>
						<th>
							<div class="content one_line">
								<div class="direction_arrow"></div>
								<?php __('Fulfillment Type'); ?>
							</div>
						</th>
						<th>
							<div class="content one_line">
								<div class="direction_arrow"></div>
								<?php __('Price'); ?>
							</div>
						</th>
						<?php /*<th><?php __('Shipping Price'); ?></th> */ ?>
						<th>
							<div class="content one_line">
								<div class="direction_arrow"></div>
								<?php __('Qty'); ?>
							</div>
						</th>
						<th>
							<div class="content one_line">
								<div class="direction_arrow"></div>
								<?php __('Turnaround Time'); ?>
							</div>
						</th>
						<th>
							<div class="content one_line">
								<div class="direction_arrow"></div>
								<?php __('Action Items'); ?>
							</div>
						</th>
						<th class="last">
							<div class="content one_line">
								<div class="direction_arrow"></div>
								<?php __('Total'); ?>
							</div>
						</th>
					</tr>
				</thead>
				<tbody>
					<tr class="spacer"><td colspan="3"></td></tr>
					<?php foreach ($authnet_order['AuthnetLineItem'] as $key => $order_item_data): ?>
						<tr>
							<td class="first">
								<div class="rightborder"></div>
								<?php $order_item_img_data = $this->Photo->get_photo_path($order_item_data['foreign_key'], 70, 70, .4, true); ?>
								<img src="<?php echo $order_item_img_data['url']; ?>" <?php echo $order_item_img_data['tag_attributes']; ?> />
							</td>
							<td>
								<div class="rightborder"></div>
								<p>
									<?php echo $order_item_data['extra_data']['PhotoPrintType']['print_name']; ?><br />
									<?php echo $order_item_data['extra_data']['CurrentPrintData']['short_side_inches']; ?> x <?php echo $order_item_data['extra_data']['CurrentPrintData']['long_side_feet_inches']; ?>
								</p>
							</td>
							<td>
								<div class="rightborder"></div>
								<span><?php echo ucwords($order_item_data['extra_data']['PhotoPrintType']['print_fulfillment_type']); ?></span>
							</td>
							<td>
								<div class="rightborder"></div>
								<span>$<?php echo $order_item_data['unit_cost']; ?></span> <?php // DREW TODO - make the money format better ?>
							</td>
							<?php /*<td>
								$<?php echo $order_item_data['shipping_price']; ?> <?php // DREW TODO - make the money format better ?>
							</td> */ ?>
							<td>
								<div class="rightborder"></div>
								<span><?php echo $order_item_data['quantity']; ?></span>
							</td>
							<td>
								<div class="rightborder"></div>
								<span><?php echo $order_item_data['extra_data']['CurrentPrintData']['custom_turnaround']; ?></span>
							</td>
							<td>
								<div class="rightborder"></div>
								<span>cropping etc</span>
							</td>
							<td class="last">
								<div class="rightborder"></div>
								<span>$<?php echo $this->Cart->get_cart_line_total($order_item_data['quantity'], $order_item_data['unit_cost']); ?></span><?php // DREW TODO - make the money format better ?>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="3" style="text-align: left;">
							<?php //$is_voided = false; $is_refunded = false; $order_status = 'new'; $is_voidable = true; $is_refundable = true; // DREW TODO - remove this line ?>
							<?php if ($order_status === 'new'): ?>
								<?php if ($is_voided): ?>
	<!--								DREW TODO - finish this-->
									This order was VOIDED
								<?php elseif ($is_refunded): ?>
	<!--								DREW TODO - finish this-->
									This order was REFUNDED
								<?php else: ?>
									<span class="custom_ui">
										<a href="/admin/ecommerces/approve_order/<?php echo $authnet_order['AuthnetOrder']['id']; ?>/">
											<div class="add_button">
												<div class="content"><?php echo __('Approve Order', true); ?></div><div class="right_arrow_lines"><div></div></div>
											</div>
										</a>
									</span>
									<?php if ($is_voidable): ?>
										<span class="custom_ui">
											<a href="/admin/ecommerces/void_order/<?php echo $authnet_order['AuthnetOrder']['id']; ?>/">
												<div class="add_button">
													<div class="content"><?php echo __('Void Order', true); ?></div><div class="right_arrow_lines"><div></div></div>
												</div>
											</a>
										</span>
									<?php endif; ?>
									<?php if ($is_refundable): ?>
										<span class="custom_ui">
											<a href="/admin/ecommerces/refund_order/<?php echo $authnet_order['AuthnetOrder']['id']; ?>/">
												<div class="add_button">
													<div class="content"><?php echo __('Refund Order', true); ?></div><div class="right_arrow_lines"><div></div></div>
												</div>
											</a>
										</span>
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
		</div>
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