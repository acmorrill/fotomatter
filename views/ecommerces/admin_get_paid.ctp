 <?php //debug($payable_orders); ?>

<h1><?php __('Payable Orders'); ?>
	<?php echo $this->Element('/admin/get_help_button'); ?>
</h1>
<p>
	<?php echo __('All payable orders appear below. Clap your hands and jump for joy. ', true); ?>
</p>
<div style="clear: both;"></div> 

<div class="table_container">
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
			
			<?php $order_total = 0; ?>
			<?php foreach ($payable_orders as $payable_order): ?>
				<?php $order_total += $payable_order['AuthnetOrder']['total']; ?>
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
		<?php if (!empty($payable_orders)): ?>
			<tfoot>
				<tr>
					<td colspan="2" style="text-align: left;">
						<div style="width: 85%;">
							<p>Orders are payable at 6:00 PM Mountain Time the day after they have been approved by you.</p><br />
							<p>You will be sent an email to <b stlye="font-weight: bold;"><?php echo $payable_paypal_email_address; ?></b> from paypal. Follow the email instructions to receive payment via paypal..</p>
						</div>
					</td>
					<td style="min-width: 300px;">
						<div class="total_orders">Orders Count: <?php echo count($payable_orders); ?></div>
						<div class="can_get_paid_total">Total Payable Amount: $<?php echo $order_total; ?></div>
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
										<div class="content"><?php echo __('Get Paid Via Paypal', true); ?></div><div class="right_arrow_lines"><div></div></div>
									</div>
								</span>
							</form>
						</div>
					</td>
				</tr>
			</tfoot>
		<?php endif; ?>
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








