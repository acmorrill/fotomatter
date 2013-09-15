<?php //debug($payable_orders); ?>


<h1><?php __('Payable Orders'); ?></h1>
<?php if (!empty($payable_orders)): ?>
<table id="payable_orders_table">
		<thead>
			<tr>
				<th><?php __('Order Number'); ?></th>
				<th><?php __('Order Total'); ?></th>
				<th><?php __('Order Date'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php $order_total = 0; ?>
			<?php foreach ($payable_orders as $payable_order): ?>
				<?php $order_total += $payable_order['AuthnetOrder']['total']; ?>
				<tr>
					<td><?php echo $payable_order['AuthnetOrder']['id']; ?></td>
					<td><?php echo $payable_order['AuthnetOrder']['total']; ?></td>
					<?php $created_date = $this->Util->get_formatted_created_date($payable_order['AuthnetOrder']['created']); ?>
					<td><?php echo $created_date; ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="3">
					<div class="total_orders">Orders Count: <?php echo count($payable_orders); ?></div>
					<div class="can_get_paid_total">Total Payable Amount: $<?php echo $order_total; ?></div>
					<div class="get_paid_via_paypay_button">
						<form id="payout_orders_via_paypal_form" METHOD="POST" action="/admin/ecommerces/payout_orders/">
							<?php foreach ($payable_orders as $payable_order): ?>
								<input type="hidden" name="data[payout_order_ids][]" value="<?php echo $payable_order['AuthnetOrder']['id']; ?>" />
							<?php endforeach; ?>
							<input type="submit" value="Get Paid Via Paypal" />
						</form>
					</div>
					<h4>NOTE: Orders are payable at 6:00 PM Mountain Time the day after they have been approved by you. (richard - work in this note somehow)</h4>
					<h4>NOTE: You will be sent an email to <b stlye="font-weight: bold;"><?php echo $payable_paypal_email_address; ?></b> from paypal. Follow the email instructions to receive payment via paypal (richard - work a note like this into your design somehow!).</h4><br/><br/>
				</td>
			</tr>
		</tfoot>
</table> 
<?php else: ?>
	There are currently no payable orders.
	<!--DREW TODO - improve this section-->
<?php endif; ?>








