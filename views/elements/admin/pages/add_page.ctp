<script type="text/javascript">
	jQuery(document).ready(function() { 
		jQuery('#add_new_page_button').click(function() { 
			var select_box = jQuery(this).parent().find('.add_page_select');
			
			switch (select_box.val()) {
				case 'custom':
					window.location = '/admin/site_pages/add_custom_page';
					break;
				case 'external':
					window.location = '/admin/site_pages/add_external_page';
					break;
				case 'contact_us':
					window.location = '/admin/site_pages/add_contact_us_page';
					break;
			}
		});
	});
</script>

<div class="add_page_element add_element custom_ui" style="margin-bottom: 15px;">
	<select class="add_page_select">
		<option value="custom"><?php echo __('Add Custom Page', true); ?></option>
		<option value="external"><?php echo __('Add External Page', true); ?></option>
		<?php $contact_us_count = $this->Page->count_pages_of_type("contact_us"); ?>
		<option value="contact_us" <?php if ($contact_us_count > 0): ?>disabled="disabled"<?php endif; ?>><?php echo __('Add Contact Us Page', true); ?></option>
	</select>
	<input id="add_new_page_button" class="add_button" type="submit" value="<?php echo __('Go', true); ?>" />
</div>
