<?php 
//	debug($used_short_side_dimensions);
//	debug($short_side_values);
	$all_used = true;
	foreach ($short_side_values as $short_side_value) {
		if (!isset($used_short_side_dimensions[(string)$short_side_value])) {
			$all_used = false;
			break;
		}
	}
?>
<h1>
	<div id="help_tour_button" class="custom_ui"><?php echo $this->Element('/admin/get_help_button'); ?></div>
</h1>
<p>
	What is this page anyhow?
</p>
<div class="page_content_header">
	<?php echo $this->Element('admin/back_button'); ?>
	<h2>Select a Dimension and Format</h2>
</div>
	<form action="/admin/ecommerces/add_print_size" method="post">
		<?php if (isset($this->data['PhotoAvailSize']['id'])): ?>
			<input type="hidden" name="data[PhotoAvailSize][id]" value="<?php echo $this->data['PhotoAvailSize']['id']; ?>" />
		<?php endif; ?>
<!--		<h2 class="group_list_name">Add Dimension</h2>-->
		<div class="generic_palette_container">
			<div class="fade_background_top"></div>
			<div class="bg_effects_controls" style="margin-bottom: 40px;"></div>
			<div class="basic_setting_cont">
				<label><?php __('Print Short Side Dimension'); ?></label>
				<div class="theme_setting_inputs_container">
					<select name="data[PhotoAvailSize][short_side_length]" <?php if ($all_used == true): ?>disabled="disabled"<?php endif; ?>>
						<?php foreach ($short_side_values as $short_side_value): ?>
							<option value="<?php echo $short_side_value; ?>" <?php if (isset($this->data['PhotoAvailSize']['short_side_length']) && $this->data['PhotoAvailSize']['short_side_length'] == $short_side_value): ?>selected="selected"<?php endif; ?> <?php if (isset($used_short_side_dimensions[(string)$short_side_value])): ?> suck="suck" disabled="disabled"<?php endif; ?>><?php echo $short_side_value; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="theme_setting_description">
					<?php __('The "short" side is the dimension of the shorter side of an image depending on the format. For example, the "short" side of a landscape is the height while the "short" side of a vertical panoramic is the width. The "long" side will be calculated based on the actual image depending on the format.'); ?>
				</div>
			</div>
			<div class="basic_setting_cont">
				<label><?php __('Photo Formats'); ?></label>
				<div class="theme_setting_inputs_container">
					<input type="checkbox" <?php if ($this->Ecommerce->print_size_has_non_pano($this->data)): ?>checked="checked"<?php endif; ?> name="data[PhotoAvailSize][photo_format_ids][]" value="1,2,3" /><span>Landscape /<br />Portrait /<br />Square</span><br/>
					<input type="checkbox" <?php if ($this->Ecommerce->print_size_has_pano($this->data)): ?>checked="checked"<?php endif; ?> name="data[PhotoAvailSize][photo_format_ids][]" value="4,5" /><span>Panoramic /<br />Vertical Panoramic</span><br/>
				</div>
				<div class="theme_setting_description">
					<?php __('Required. Choose the formats that this dimension will be available to. For example, choosing short side dimension with formats "Landscape / Portrait / Square" will make those sizes available as options when you are creating prices for different print types.'); ?>
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