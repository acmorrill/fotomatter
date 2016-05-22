<script type="text/javascript">
	var pano_option;
	var non_pano_option;
	var short_side_select;
	
	function set_format_options_enable_disabled() {
		var selected_option = jQuery('option:selected', short_side_select);
		var pano_available = selected_option.data('pano-available');
		var non_pano_available = selected_option.data('non-pano-available');
		pano_option.prop('disabled', pano_available);
		non_pano_option.prop('disabled', non_pano_available);
	}
	
	jQuery(document).ready(function() {
		pano_option = jQuery('#format_options .pano');
		non_pano_option = jQuery('#format_options .non_pano');
		short_side_select = jQuery('#short_side_select');
		set_format_options_enable_disabled();
		short_side_select.change(function() {
			set_format_options_enable_disabled();
		});
	});
</script>
<h1><?php echo __('Add/Edit Print Sizes', true); ?>
	<div id="help_tour_button" class="custom_ui"><?php //echo $this->Element('/admin/get_help_button'); ?></div>
</h1>
<p><?php echo __('Add new dimensions to your available print sizes and select the format you want the size applied to.'); ?></p>
<div class="page_content_header">
	<?php echo $this->Element('admin/back_button'); ?>
	<h2>Select a Dimension and Format</h2>
	<div style="clear: both;"></div>
</div>
	<form action="/admin/ecommerces/add_print_size/<?php echo $photo_avail_size_id; ?>" method="post">
		<?php if (isset($this->data['PhotoAvailSize']['id'])): ?>
			<input type="hidden" name="data[PhotoAvailSize][id]" value="<?php echo $this->data['PhotoAvailSize']['id']; ?>" />
		<?php endif; ?>
		<div class="generic_palette_container" data-step="1" data-intro="<?php echo __('This pages aids in the creation of print sizes. Now get at it and make some print sizes.', true); ?>" data-position="top">
			<div class="fade_background_top"></div>
			<div class="basic_setting_cont">
				<label><?php __('Print Short Side Dimension'); ?></label>
				<div class="theme_setting_inputs_container">
					<select id="short_side_select" name="data[PhotoAvailSize][short_side_length]">
						<?php foreach ($short_side_values as $short_side_value): ?>
							<option 
								value="<?php echo $short_side_value; ?>" 
								<?php if (isset($this->data['PhotoAvailSize']['short_side_length']) && $this->data['PhotoAvailSize']['short_side_length'] == $short_side_value): ?>selected="selected"<?php endif; ?>
								<?php if (!empty($used_short_side_dimensions['short_side_used'][(string)$short_side_value]['pano']) && !empty($used_short_side_dimensions['short_side_used'][(string)$short_side_value]['non_pano'])): ?> disabled="disabled"<?php endif; ?>
								data-pano-available="<?php echo !empty($used_short_side_dimensions['short_side_used'][(string)$short_side_value]['pano']) ? "true" : "false"; ?>" 
								data-non-pano-available="<?php echo !empty($used_short_side_dimensions['short_side_used'][(string)$short_side_value]['non_pano']) ? "true" : "false"; ?>"
							><?php echo $short_side_value; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="theme_setting_description">
					<?php __('The "short" side is the dimension of the shorter side of an image depending on the format. For example, the "short" side of a landscape is the height while the "short" side of a vertical panoramic is the width. The "long" side will be calculated based on the actual image depending on the format.'); ?>
				</div>
			</div>
			<div class="basic_setting_cont">
				<label><?php __('Photo Orientation'); ?></label>
				<div class="theme_setting_inputs_container">
					<select id="format_options" name="data[PhotoAvailSize][photo_format_ids]" style="max-width: 280px;">
						<option 
							class="non_pano" 
							value='1,2,3'
							<?php if (isset($this->data['PhotoAvailSize']['photo_format_ids']) && $this->data['PhotoAvailSize']['photo_format_ids'] == '1,2,3'): ?>selected="selected"<?php endif; ?>
						>Landscape / Portrait / Square</option>
						<option 
							class="pano" 
							value="4,5"
							<?php if (isset($this->data['PhotoAvailSize']['photo_format_ids']) && $this->data['PhotoAvailSize']['photo_format_ids'] == '4,5'): ?>selected="selected"<?php endif; ?>
						>Panoramic / Vertical Panoramic</option>
					</select>
				</div>
				<div class="theme_setting_description">
					<?php __('Required. Choose the orientations that this dimension will be available on. For example, choosing a short side dimension with orientations "Landscape / Portrait / Square" will make those sizes available as options when you are creating prices for different print types.'); ?>
				</div>
			</div>
			<div style="clear: both"></div>
			<script type="text/javascript">
				jQuery(document).ready(function() {
					jQuery('#save_print_size_button').click(function() {
						jQuery(this).closest('form').submit();
					});
				});
			</script>
			<div class="basic_setting_cont last button">
				<div id="save_print_size_button" class="save_button">
					<div class="content"><?php echo __('Save', true); ?></div>
				</div>
			</div>
		</div>
	</form>


<?php ob_start(); ?>
<ol>
	<li>This page is where the user can add or edit an available print size	</li>
	<li>Things to remember
		<ol>
			<li>For the print short side dimension drop down we need a "taken" state for sizes already used</li>
			<li>This page needs a flash message</li>
		</ol>
	</li>
</ol>
<?php
$html = ob_get_contents();
ob_end_clean();
	echo $this->Element('admin/richard_notes', array(
	'html' => $html
)); ?>