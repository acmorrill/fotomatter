 <?php //debug($payable_orders); ?>

<h1><?php __('Receive Payment'); ?>
	<?php //echo $this->Element('/admin/get_help_button'); ?>
</h1>
<p><?php echo __('After you have clicked &ldquo;fulfill&rdquo; on the &ldquo;Manage Orders&rdquo; page, the order will be displayed here until you have requested payment.', true); ?></p>
<div style="clear: both;"></div> 

<div class="table_container" data-step="1" data-intro="<?php echo __('This pages displays all the orders that have been place to your account.', true); ?>" data-position="top">
	<div class="fade_background_top"></div>
	<div class="table_top"></div>
	<table class="list">
		<thead>
			<tr>
				<th class="first">
					<div class="content one_line">
						<div class="direction_arrow"></div>
						<?php __('Order Number'); ?>
					</div>
				</th>
				<th>
					<div class="content one_line">
						<div class="direction_arrow"></div>
						<?php __('Order Total'); ?>
					</div>
				</th>
				<th class="last">
					<div class="content one_line">
						<div class="direction_arrow"></div>
						<?php __('Order Date'); ?>
					</div>
				</th>
			</tr>
		</thead>
		<tbody>
			<tr class="spacer"><td colspan="3"></td></tr>
			
			<?php if (empty($payable_orders)): ?>
				<tr class="first last">
					<td class="first last" colspan="3">
						<div class="rightborder"></div>
						<span><?php echo __('There are no payable orders.', true); ?></span>
					</td>
				</tr>
			<?php endif; ?>
			
			<?php foreach ($payable_orders as $payable_order): ?>
				<tr>
					<td class="first">
						<div class="rightborder"></div>
						<span><?php echo $payable_order['AuthnetOrder']['id']; ?></span>
					</td>
					<td>
						<div class="rightborder"></div>
						<span><?php echo $payable_order['AuthnetOrder']['total']; ?></span>
					</td>
					<?php $created_date = $this->Util->get_formatted_created_date($payable_order['AuthnetOrder']['created']); ?>
					<td class="last">
						<div class="rightborder"></div>
						<span><?php echo $created_date; ?></span>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
			<tfoot>
				<tr>
					<td colspan="2" style="text-align: left;" class="first">
						<div style="width: 85%;">
							<p><?php echo __('Orders are payable at 6:00 PM Mountain Time the day after you have approved them. Fotomatter retains 5% of all orders for processing fees.', true); ?></p><br />
							<p><?php echo sprintf(__('An email will be sent to <b stlye="font-weight: bold;">%s</b> from PayPal. Follow the email instructions to receive payment via PayPal.', true), $payable_paypal_email_address); ?></p>
						</div>
					</td>
					<td style="min-width: 300px;" class="last">
						<?php if (!empty($payable_orders)): ?>
							<div class='table_summary'>
								<div class='payment_item total_orders'>
									<label><?php echo __('Orders Count', true); ?></label>
									<span class='value'><?php echo count($payable_orders); ?></span>
								</div>
								<?php if (round($order_total_data['fee'], 2) >= .01): ?>
									<div class='payment_item can_get_paid_total'>
										<label><?php echo __('Transaction Fee', true); ?></label>
										<span class='value'><?php echo $this->Number->currency($order_total_data['fee']); ?></span>
									</div>
								<?php endif; ?>
								<div class='payment_item can_get_paid_total'>
									<label><?php echo __('Total Payable', true); ?></label>
									<span class='value'><?php echo $this->Number->currency($order_total_data['total']); ?></span>
								</div>
								
								<br />
								<div class="get_paid_via_paypay_button">
									<form id="payout_orders_via_paypal_form" METHOD="POST" action="/admin/ecommerces/payout_orders/">
										<?php foreach ($payable_orders as $payable_order): ?>
											<input type="hidden" name="data[payout_order_ids][]" value="<?php echo $payable_order['AuthnetOrder']['id']; ?>" />
										<?php endforeach; ?>

										<script type="text/javascript">
											jQuery(document).ready(function() {
												jQuery('#get_paid_button').click(function() {
													jQuery(this).closest('form').submit();
												});
											});
										</script>
										<span id="get_paid_button" class="custom_ui">
											<div class="add_button">
												<div class="content"><?php echo __('Get Paid Via Paypal', true); ?></div><div class="right_arrow_lines icon-arrow-01"><div></div></div>
											</div>
										</span>
									</form>
								</div>
							</div>
						<?php endif; ?>
					</td>
				</tr>
			</tfoot>
	</table> 
</div>

	
<?php ob_start(); ?>
<ol>
	<li>This page lets you get paid on orders you have made</li>
	<li><a href="/img/admin_screenshots/get_paid.jpg" target="_blank">screenshot</a></li>
	<li>Things to remember
		<ol>
			<li>This page needs a flash message</li>
			<li>We need a design to contain the help message on the screenshot - to explain how getting paid works</li>
		</ol>
	</li>
</ol>
<?php
$html = ob_get_contents();
ob_end_clean();
	echo $this->Element('admin/richard_notes', array(
	'html' => $html
)); ?>








