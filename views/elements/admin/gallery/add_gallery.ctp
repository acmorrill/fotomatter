<script type="text/javascript">
	jQuery(document).ready(function() { 
		jQuery('#add_new_gallery_button').click(function() { 
			var select_box = jQuery(this).parent().find('.add_gallery_select');
			
			switch (select_box.val()) {
				case 'standard':
					window.location = '/admin/photo_galleries/add_standard_gallery';
					break;
				case 'smart':
					window.location = '/admin/photo_galleries/add_smart_gallery';
					break;
				default:
					break;
			}
		});
	});
</script>

<div class="add_gallery_element custom_ui" style="margin: 5px; margin-bottom: 15px;">
	<span><?php __('Add New Gallery'); ?></span>
	<select class="add_gallery_select">
		<option value="standard"><?php __('Standard'); ?></option>
		<option value="smart"><?php __('Smart'); ?></option>
	</select>
	<input id="add_new_gallery_button" class="add_button" type="submit" value="<?php __('Go'); ?>" />
</div>
