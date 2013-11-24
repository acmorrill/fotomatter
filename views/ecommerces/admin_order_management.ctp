<?php if (!empty($authnet_orders)): ?>
	<div class="table_header">
		<label class="inline"><?php __('Page:'); ?></label> <?php echo $this->Paginator->counter(); ?>
		<div class="right">
			<?php echo $this->Paginator->prev(__('Prev', true), null, null, array('class' => 'disabled')); ?>&nbsp;
			<?php echo $this->Paginator->numbers(array(
				'modulus' => 2,
				'first' => 2,
				'last' => 2
			)); ?>&nbsp;
			<?php echo $this->Paginator->next(__('Next', true), null, null, array('class' => 'disabled')); ?> 
		</div>
	</div>
	<?php $sort_dir = $this->Paginator->sortDir('AuthnetOrder'); ?>
	<table class="list">
		<tr> 
			<th class="first <?php if ($this->Paginator->sortKey('AuthnetOrder') == 'AuthnetOrder.id'): ?> curr <?php echo $sort_dir; ?><?php endif; ?>"><?php echo $this->Paginator->sort(__('Order Number', true), 'AuthnetOrder.id'); ?></th> 
			<th class="<?php if ($this->Paginator->sortKey('AuthnetOrder') == 'AuthnetOrder.order_status'): ?> curr <?php echo $sort_dir; ?><?php endif; ?>"><?php echo $this->Paginator->sort(__('Order Status', true), 'AuthnetOrder.order_status'); ?></th> 
			<th class="<?php if ($this->Paginator->sortKey('AuthnetOrder') == 'AuthnetOrder.pay_out_status'): ?> curr <?php echo $sort_dir; ?><?php endif; ?>"><?php echo $this->Paginator->sort(__('Reimbursement Status', true), 'AuthnetOrder.pay_out_status'); ?></th> 
			<th class="<?php if ($this->Paginator->sortKey('AuthnetOrder') == 'AuthnetOrder.total'): ?> curr <?php echo $sort_dir; ?><?php endif; ?>"><?php echo $this->Paginator->sort(__('Order Total', true), 'AuthnetOrder.total'); ?></th>
			<th class="<?php if ($this->Paginator->sortKey('AuthnetOrder') == 'AuthnetOrder.created'): ?> curr <?php echo $sort_dir; ?><?php endif; ?>"><?php echo $this->Paginator->sort(__('Order Date', true), 'AuthnetOrder.created'); ?></th> 
			<th class="last"><?php __('Actions'); ?></th>
		</tr> 
	   <?php foreach($authnet_orders as $authnet_order): ?> 
			<tr>
				<td class="order_id first <?php if ($this->Paginator->sortKey('AuthnetOrder') == 'AuthnetOrder.id'): ?> curr<?php endif; ?>"><?php echo $authnet_order['AuthnetOrder']['id']; ?> </td> 
				<td class="order_status <?php if ($this->Paginator->sortKey('AuthnetOrder') == 'AuthnetOrder.order_status'): ?> curr<?php endif; ?>"><?php echo $authnet_order['AuthnetOrder']['order_status']; ?> </td> 
				<td class="order_status <?php if ($this->Paginator->sortKey('AuthnetOrder') == 'AuthnetOrder.pay_out_status'): ?> curr<?php endif; ?>"><?php echo $authnet_order['AuthnetOrder']['pay_out_status']; ?> </td> 
				<td class="order_total <?php if ($this->Paginator->sortKey('AuthnetOrder') == 'AuthnetOrder.total'): ?> curr<?php endif; ?>"><?php echo $authnet_order['AuthnetOrder']['total']; ?> </td> 
				<?php $created_date = $this->Util->get_formatted_created_date($authnet_order['AuthnetOrder']['created']); ?>
				<td class="order_created_date <?php if ($this->Paginator->sortKey('AuthnetOrder') == 'AuthnetOrder.created'): ?> curr<?php endif; ?>"><?php echo $created_date; ?> </td> 
				<td class="photo_action last">
					<a href="/admin/ecommerces/fulfill_order/<?php echo $authnet_order['AuthnetOrder']['id']; ?>/"><?php __('Fulfill'); ?></a>
				</td>
			</tr>
		<?php endforeach; ?> 
	</table>
	<?php echo $this->Paginator->prev(__('Prev', true), null, null, array('class' => 'disabled')); ?>&nbsp;
	<?php echo $this->Paginator->numbers(array(
		'modulus' => 2,
		'first' => 2,
		'last' => 2
	)); ?>&nbsp;
	<?php echo $this->Paginator->next(__('Next', true), null, null, array('class' => 'disabled')); ?> 

<?php else: ?>
	<?php __('You do not have any orders yet.'); ?>
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