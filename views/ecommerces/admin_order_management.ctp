<h1>Order Management
	<?php echo $this->Element('/admin/get_help_button'); ?>
</h1>
<p> Here is where you will see all your orders. You will still need to approve the order by using the fulfill button. Once it has been approved it will be 18 hours or if approved after 6pm the payment will post the next day.<br><br>
	
	Instructions for receiving payment go here so all that mumbo jumbo on the screenshot below the Paypal button will go here. Cool beans? Cool.
IPlaceholder info for getting pade. Instructions will go here. Trty and keep it to two lines. But if more, that’s fine. Instructions for receiving payment go here so all that mumbo jumbo on the screenshot below the Paypal button will go here. Cool beans? Cool.
</p>
<?php if (!empty($authnet_orders)): ?>
	<?php $sort_dir = $this->Paginator->sortDir('AuthnetOrder'); ?>
	<div class="table_container" data-step="1" data-intro="<?php echo __('Here you will see all the orders that have been approved or are waiting approval.', true); ?>" data-position="left">
		<div class="fade_background_top"></div>
		<div class="table_top"></div>
		<table class="list">
			<thead>
				<tr data-step="2" data-intro="<?php echo __('Placed here are the categories that can be sorted.', true); ?>" data-position="left">
					<th class="first <?php if ($this->Paginator->sortKey('AuthnetOrder') == 'AuthnetOrder.id'): ?> curr <?php echo $sort_dir; ?><?php endif; ?>" data-step="3" data-intro="<?php echo __('You may display the orders by Order Number, Order Status, and so on.', true); ?>" data-position="bottom">
						<div class="content one_line">
							<div class="direction_arrow"></div>
							<?php echo $this->Paginator->sort(__('Order Number', true), 'AuthnetOrder.id'); ?>
						</div>
					</th>
					<th class="<?php if ($this->Paginator->sortKey('AuthnetOrder') == 'AuthnetOrder.order_status'): ?> curr <?php echo $sort_dir; ?><?php endif; ?>">
						<div class="content one_line">
							<div class="direction_arrow"></div>
							<?php echo $this->Paginator->sort(__('Order Status', true), 'AuthnetOrder.order_status'); ?>
						</div>
					</th>
					<th class="<?php if ($this->Paginator->sortKey('AuthnetOrder') == 'AuthnetOrder.pay_out_status'): ?> curr <?php echo $sort_dir; ?><?php endif; ?>">
						<div class="content one_line">
							<div class="direction_arrow"></div>
							<?php echo $this->Paginator->sort(__('Reimbursement Status', true), 'AuthnetOrder.pay_out_status'); ?>
						</div>
					</th>
					<th class="<?php if ($this->Paginator->sortKey('AuthnetOrder') == 'AuthnetOrder.total'): ?> curr <?php echo $sort_dir; ?><?php endif; ?>">
						<div class="content one_line">
							<div class="direction_arrow"></div>
							<?php echo $this->Paginator->sort(__('Order Total', true), 'AuthnetOrder.total'); ?></th>
						</div>
					<th class="<?php if ($this->Paginator->sortKey('AuthnetOrder') == 'AuthnetOrder.created'): ?> curr <?php echo $sort_dir; ?><?php endif; ?>">
						<div class="content one_line">
							<div class="direction_arrow"></div>
							<?php echo $this->Paginator->sort(__('Order Date', true), 'AuthnetOrder.created'); ?>
						</div>
					</th>
					<th class="last">
						<div class="content one_line">
							<?php __('Actions'); ?>
						</div>
					</th>
				</tr> 
			</thead>
			<tbody>
				<tr class="spacer"><td colspan="3"></td></tr>
				<?php foreach($authnet_orders as $authnet_order): ?> 
					<tr class="<?php if ($authnet_order['AuthnetOrder']['order_status'] === 'new') { echo 'new'; } ?>" data-step="4" data-intro="<?php echo __('The orders are displayed here and their status.', true); ?>" data-position="left">
						<td class="order_id first <?php if ($this->Paginator->sortKey('AuthnetOrder') == 'AuthnetOrder.id'): ?> curr<?php endif; ?>">
							<div class="rightborder"></div>
							<span><?php echo $authnet_order['AuthnetOrder']['id']; ?></span>
						</td> 
						<td class="order_status <?php if ($this->Paginator->sortKey('AuthnetOrder') == 'AuthnetOrder.order_status'): ?> curr<?php endif; ?>">
							<div class="rightborder"></div>
							<span><?php echo $authnet_order['AuthnetOrder']['order_status']; ?></span>
						</td> 
						<td class="order_status <?php if ($this->Paginator->sortKey('AuthnetOrder') == 'AuthnetOrder.pay_out_status'): ?> curr<?php endif; ?>">
							<div class="rightborder"></div>
							<span><?php echo $authnet_order['AuthnetOrder']['pay_out_status']; ?></span>
						</td> 
						<td class="order_total <?php if ($this->Paginator->sortKey('AuthnetOrder') == 'AuthnetOrder.total'): ?> curr<?php endif; ?>">
							<div class="rightborder"></div>
							<span>$<?php echo $authnet_order['AuthnetOrder']['total']; ?></span> <?php // DREW TODO - do money formatting here ?>
						</td> 
						<?php $created_date = $this->Util->get_formatted_created_date($authnet_order['AuthnetOrder']['created']); ?>
						<td class="order_created_date <?php if ($this->Paginator->sortKey('AuthnetOrder') == 'AuthnetOrder.created'): ?> curr<?php endif; ?>">
							<div class="rightborder"></div>
							<span><?php echo $created_date; ?></span>
						</td> 
						<td class="photo_action last">
							<span class="custom_ui">
								<a href="/admin/ecommerces/fulfill_order/<?php echo $authnet_order['AuthnetOrder']['id']; ?>/"><div class="add_button" data-step="5" data-intro="<?php echo __('This is where you will finish the orders and approve them.', true); ?>" data-position="left"><div class="content"><?php __('Fulfill'); ?></div><div class="right_arrow_lines"><div></div></div></div></a>
							</span>
						</td>
					</tr>
				<?php endforeach; ?> 
			</tbody>
			<tfoot>
				<tr>
					<td colspan="8">
						<?php echo $this->Paginator->prev(__('Previous', true), null, null, array('class' => 'disabled')); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<?php echo $this->Paginator->numbers(array(
							'modulus' => 2,
							'first' => 2,
							'last' => 2,
//							'before' => '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
//							'after' => '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
							'separator' => '<div class="paginator_divider"></div>',
						)); ?>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->Paginator->next(__('Next', true), null, null, array('class' => 'disabled')); ?> 
					</td>
				</tr>
			</tfoot>
		</table>
	</div>
<?php else: ?>
	<h1><?php __('You do not have any orders yet.'); ?></h1>
<?php endif; ?>

<?php ob_start(); ?>
<ol>
	<li>This page just lists your orders and their current status - you can fullfill orders from this page</li>
	<li><a href="/img/admin_screenshots/order_management.jpg" target="_blank">screenshot</a></li>
	<li>Things to remember
		<ol>
			<li>This page needs a flash message</li>
			<li>We need a design for the pagination</li>
			<li>We need a design for the column that is currently sorting</li>
			<li>We need a design for the sort direction</li>
			<li>Don't forget a design for the fulfill page</li>
			<li>If you don't have fake orders ask me and I'll run a script to add some</li>
			<li>We need a design for before the user has any orders</li>
			<li>If the user has not purchased ecommerce the whole page should be grayed out and there should be an upsell type graphic
				<ol>
					<li>We need a similar up sell type graphic on other pages as well</li>
					<li>The upsell graphic is basically just something you click that immediatly goes to the page to add the feature and ask you for a credit card if we don't already have it)</li>
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