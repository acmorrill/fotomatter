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

<div class="add_page_element custom_ui" style="margin: 5px; margin-bottom: 15px;">
	<span><?php __('Add New Page'); ?></span>
	<select class="add_page_select">
		<option value="custom"><?php __('Custom'); ?></option>
		<option value="external"><?php __('External'); ?></option>
		<option value="contact_us"><?php __('Contact Us'); ?></option>
	</select>
	<input id="add_new_page_button" class="add_button" type="submit" value="<?php __('Go'); ?>" />
</div>
