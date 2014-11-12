<h1><?php echo __('Order Management', true); ?>
	<?php echo $this->Element('/admin/get_help_button'); ?>
</h1>
<p> 
	<?php echo __('Here is where you will see all your orders. You will still need to approve the order by using the fulfill button. Once it has been approved it will be 18 to 44 hours until you receive your reimbursement.', true); ?>
</p>

<?php $sort_dir = $this->Paginator->sortDir('AuthnetOrder'); ?>
<div class="table_container" data-step="1" data-intro="<?php echo __('Here you will see all the orders that have been approved or are waiting approval.', true); ?>" data-position="top">
	<div class="fade_background_top"></div>
	<div class="table_top"></div>		
	<table class="list">
		<thead>
			<tr>
				<th class="first <?php if ($this->Paginator->sortKey('AuthnetOrder') == 'AuthnetOrder.id'): ?> curr <?php echo $sort_dir; ?><?php endif; ?>"  data-step="2" data-intro="<?php echo __('You may display the orders by Order Number, Order Status, and so on. Indicated by the blue line and arrow.', true); ?>">
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
			<tr class="spacer"><td colspan="6"></td></tr>

			<?php if (empty($authnet_orders)): ?>
				<tr class="first last">
					<td class="first last" colspan="6">
						<div class="rightborder"></div>
						<span><?php echo __('You do not have any orders yet.', true); ?></span>
					</td>
				</tr>
			<?php endif; ?>

			<?php $count = 0; foreach($authnet_orders as $authnet_order): ?> 

				<?php 
					$status_help_code = '';
					$reimbursement_help_code = '';
					$fulfill_help_code = '';
					if ($count === 0) {
						$status_help_code = 'data-step="3" data-intro="'.__('You need to view all orders and approve or void them using the fullfill button.', true).'" data-position="left"';
						$reimbursement_help_code = 'data-step="4" data-intro="'.__('Here you can see the status of reimbursement from Fotomatter.', true).'" data-position="left"';
						$fulfill_help_code = 'data-step="5" data-intro="'.__('Using the fulfill button you can approve or void orders', true).'" data-position="left"';
					}
				?>
				<tr class="<?php if ($authnet_order['AuthnetOrder']['order_status'] === 'new') { echo 'new'; } ?>">
					<td class="order_id first <?php if ($this->Paginator->sortKey('AuthnetOrder') == 'AuthnetOrder.id'): ?> curr<?php endif; ?>" >
						<div class="rightborder"></div>
						<span><?php echo $authnet_order['AuthnetOrder']['id']; ?></span>
					</td> 
					<td class="order_status <?php if ($this->Paginator->sortKey('AuthnetOrder') == 'AuthnetOrder.order_status'): ?> curr<?php endif; ?>" <?php echo $status_help_code; ?>>
						<div class="rightborder"></div>
						<span><?php echo $authnet_order['AuthnetOrder']['order_status']; ?></span>
					</td> 
					<td class="order_status <?php if ($this->Paginator->sortKey('AuthnetOrder') == 'AuthnetOrder.pay_out_status'): ?> curr<?php endif; ?>" <?php echo $reimbursement_help_code; ?>>
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
							<a href="/admin/ecommerces/fulfill_order/<?php echo $authnet_order['AuthnetOrder']['id']; ?>/"><div class="add_button" <?php echo $fulfill_help_code; ?>><div class="content"><?php __('Fulfill'); ?></div><div class="right_arrow_lines icon-arrow-01"><div></div></div></div></a>
						</span>
					</td>
				</tr>
			<?php $count++; endforeach; ?> 
		</tbody>
		<?php if (!empty($authnet_orders)): ?>
			<tfoot>
				<tr>
					<td colspan="8">
						<?php echo $this->Paginator->prev(__('Previous', true), null, null, array('class' => 'disabled')); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<?php echo $this->Paginator->numbers(array(
							'modulus' => 2,
							'first' => 2,
							'last' => 2,
//								'before' => '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
//								'after' => '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
							'separator' => '<div class="paginator_divider"></div>',
						)); ?>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->Paginator->next(__('Next', true), null, null, array('class' => 'disabled')); ?> 
					</td>
				</tr>
			</tfoot>
		<?php endif; ?>
	</table>
</div>


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