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

<div class="add_gallery_element add_element custom_ui" style="margin: 5px; margin-bottom: 15px;">
	<select class="add_gallery_select">
		<option value="standard"><?php echo __('Add Standard Gallery', true); ?></option>
		<option value="smart"><?php echo __('Add Smart Gallery', true); ?></option>
	</select>
	<input id="add_new_gallery_button" class="add_button" type="submit" value="<?php echo __('Go', true); ?>" />
</div>
