<?php echo $this->Session->flash(); ?>
<?php //debug($authnet_orders); ?>

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
			<th class="<?php if ($this->Paginator->sortKey('AuthnetOrder') == 'AuthnetOrder.total'): ?> curr <?php echo $sort_dir; ?><?php endif; ?>"><?php echo $this->Paginator->sort(__('Format', true), 'AuthnetOrder.total'); ?></th> 
			<th class="<?php if ($this->Paginator->sortKey('AuthnetOrder') == 'AuthnetOrder.created'): ?> curr <?php echo $sort_dir; ?><?php endif; ?>"><?php echo $this->Paginator->sort(__('Order Date', true), 'AuthnetOrder.created'); ?></th> 
			<th class="last"><?php __('Actions'); ?></th>
		</tr> 
	   <?php foreach($authnet_orders as $authnet_order): ?> 
			<tr>
				<td class="order_id first <?php if ($this->Paginator->sortKey('AuthnetOrder') == 'AuthnetOrder.id'): ?> curr<?php endif; ?>"><?php echo $authnet_order['AuthnetOrder']['id']; ?> </td> 
				<td class="order_status <?php if ($this->Paginator->sortKey('AuthnetOrder') == 'AuthnetOrder.order_status'): ?> curr<?php endif; ?>"><?php echo $authnet_order['AuthnetOrder']['order_status']; ?> </td> 
				<td class="order_total <?php if ($this->Paginator->sortKey('AuthnetOrder') == 'AuthnetOrder.total'): ?> curr<?php endif; ?>"><?php echo $authnet_order['AuthnetOrder']['total']; ?> </td> 
				<?php 
					if (date("Y", strtotime($authnet_order['AuthnetOrder']['created'])) == date('Y')) {
						$created_format = "F j, g:i A";
					} else {
						$created_format = "F j Y, g:i A";
					}
				?>
				<td class="order_created_date <?php if ($this->Paginator->sortKey('AuthnetOrder') == 'AuthnetOrder.created'): ?> curr<?php endif; ?>"><?php echo date($created_format, strtotime($authnet_order['AuthnetOrder']['created'])); ?> </td> 
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