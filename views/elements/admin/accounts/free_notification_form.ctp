<div id="account_change_finish" class='fotomatter_form short' >
	
	<?php 
		if (!empty($_SESSION['finalize_features_error'])) {
			echo $this->element('admin/flashMessage/warning', array('message' => $_SESSION['finalize_features_error']));
			unset($_SESSION['finalize_features_error']);
		}
	?>

	<script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery('#account_change_finish').dialog({
				title: '<?php echo __('Free Account', true); ?>',
				dialogClass: "highlight_buttons wide_dialog",
				buttons: [ 
					{
						text: "<?php echo __('Continue Without Credit Card'); ?>", 
						click: function() {
							show_universal_load();
							open_finish_account_change_nocc_confirm();
						} 
					},
					{
						text: "<?php echo __('Add Credit Card'); ?>", 
						click: function() {
							show_universal_load();
							open_add_profile_popup();
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


	<div class="input">
		<p>
			<?php echo __('Good News! You have a free account! You may, however, choose to add a credit card today so that your new features will continue without interruption in the future.', true); ?>
		</p>
	</div>
	<div class="input">
		<label><?php echo __('Free Until', true); ?></label>
		<span><?php echo date("M j", strtotime($account_info['Account']['free_until'])); ?></span>
	</div>
</div>

