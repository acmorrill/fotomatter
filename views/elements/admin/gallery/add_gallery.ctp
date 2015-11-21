<?php // DREW TODO - remove this file  - no longer used ?>

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

<div class="add_gallery_element add_element custom_ui">
	<select class="add_gallery_select" data-step="3" data-intro="<?php echo __('Standard Galleries allow you to choose photos to put into the gallery manually. Smart galleries automatically add photos based on settings such as upload date, tags and photo orientation.', true); ?>" data-position="left">
		<option value="standard"><?php echo __('Add Standard Gallery', true); ?></option>
		<option value="smart"><?php echo __('Add Smart Gallery', true); ?></option>
	</select>
	<input id="add_new_gallery_button" class="add_button" type="submit" value="<?php echo __('Go', true); ?>" />
</div>
