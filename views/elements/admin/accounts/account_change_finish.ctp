<div id="account_change_finish" class='popup_content_with_table' >
	
	<?php 
		if (!empty($_SESSION['finalize_features_error'])) {
			echo $this->element('admin/flashMessage/warning', array('message' => $_SESSION['finalize_features_error']));
			unset($_SESSION['finalize_features_error']);
		}
	?>
	
	<script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery('#account_change_finish').dialog({
				title: '<?php echo __('Confirm Features', true); ?>',
				dialogClass: "highlight_buttons wide_dialog",
				buttons: [ 
					{
						text: "<?php echo __('Finish', true); ?>", 
						click: function() { 
							show_universal_save();
							
							$.ajax({
								type: 'POST',
								url: "/admin/accounts/ajax_finish_account_change",
								success: function(data) {
									window.location.href = "/admin/accounts/index";
								},
								complete: function() {
									hide_universal_save();
								},
								error: function(jqXHR, textStatus, errorThrown) {

								},
								dataType: 'json'
							});
						} 
					} 
				],
				open: function(event, ui) {
				},
				close: function(event, ui) {
					$(this).dialog('destroy').remove();
				},
				modal: true
			});
		});
	</script>
	<table class="list">
		<tbody>
			<?php $amount_to_add = 0; ?>
			<?php //debug($account_info['is_free_account']); ?>
			<?php foreach ($account_changes['checked'] as $id => $change): ?>
				<?php $amount_to_add += $account_info['items'][$id]['AccountLineItem']['current_cost']; ?>
				<tr>
					<td class="first">
						<div class="rightborder"></div>
						<span><?php echo $account_info['items'][$id]['AccountLineItem']['name']; ?></span>
					</td>
					<td class="last table_actions">
						<div class="rightborder"></div>
						<span><?php echo $this->Number->currency($account_info['items'][$id]['AccountLineItem']['current_cost']); ?></span>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
		<tfoot>
			<tr>
				<td class="first">
					<div class='cc_info'>
						<?php
							$billing_message = '';
							if (!empty($account_info['is_free_account'])) {
								$billing_message = sprintf(__('Free account until %s', true), date("M j", strtotime($account_info['Account']['free_until'])));
							} else if (empty($total) || empty($payment_profile['data'])) {
								$billing_message = __('Total amount is from fotomatter credit', true);
							} else {
								$billing_message = sprintf(__('Using card ending in %s', true), $payment_profile['data']['AuthnetProfile']['payment_cc_last_four']);
							}
						?>
						<p><?php echo $billing_message; ?></p>
						<p>(<a href='#' onClick='show_universal_load(); open_add_profile_popup()'>Change/Add Credit Card</a>)</p>
					</div>
				</td>
				<td class="last">
					<div class='table_summary'>
						<div class='payment_item current_bill <?php if (!empty($account_info['is_free_account'])): ?> strike <?php endif; ?>'>
							<label><?php echo __('Previous Monthly Bill', true); ?></label>
							<span class='value'><?php echo $this->Number->currency($current_bill); ?></span>
						</div>
						<div class='payment_item new_bill <?php if (!empty($account_info['is_free_account'])): ?> strike <?php endif; ?> '>
							<label><?php echo __('New Monthly Bill', true); ?></label>
							<span class='value'><?php echo $this->Number->currency($current_bill + $amount_to_add); ?></span>
						</div>
						<div class="hr"></div>
						
						<?php if (!empty($account_info['is_free_account'])): ?>
							<div class='payment_item due_today'>
								<label><?php echo __('Total', true); ?></label>
								<span class='value'><?php echo $this->Number->currency(0); ?></span>
							</div>
						<?php else: ?>
							<div class='payment_item due_today'>
								<label><?php echo __('Due Today', true); ?></label>
								<span class='value'><?php echo $this->Number->currency($bill_today); ?></span>
							</div>
							<?php if (!empty($bill_today_promo)): ?>
								<div class='payment_item due_today_promo'>
									<label><?php echo __('Fotomatter Credit', true); ?></label>
									<span class='value'>(<?php echo $this->Number->currency($bill_today_promo); ?>)</span>
								</div>
								<div class='payment_item due_today'>
									<label><?php echo __('Total', true); ?></label>
									<span class='value'><?php echo $this->Number->currency($total); ?></span>
								</div>
							<?php endif; ?>
						<?php endif; ?>
					</div>
				</td>
			</tr>
		</tfoot>
	</table>
	<div style='clear:both'></div>
</div>
   
   