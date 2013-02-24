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
<div class="basic_settings">
	<div class="basic_setting_cont">
		<label>&nbsp;</label>
		<div class="theme_setting_inputs_container">
			<?php echo $this->Session->flash(); ?>
		</div>
	</div>
	<form action="/admin/ecommerces/add_print_size" method="post">
		<?php if (isset($this->data['PhotoAvailSize']['id'])): ?>
			<input type="hidden" name="data[PhotoAvailSize][id]" value="<?php echo $this->data['PhotoAvailSize']['id']; ?>" />
		<?php endif; ?>
<!--		<h2 class="group_list_name">Add Dimension</h2>-->
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
				<input type="checkbox" <?php if ($this->Ecommerce->print_size_has_non_pano($this->data)): ?>checked="checked"<?php endif; ?> name="data[PhotoAvailSize][photo_format_ids][]" value="1,2,3" />Landscape / Portrait / Square<br/>
				<input type="checkbox" <?php if ($this->Ecommerce->print_size_has_pano($this->data)): ?>checked="checked"<?php endif; ?> name="data[PhotoAvailSize][photo_format_ids][]" value="4,5" />Panoramic / Vertical Panoramic<br/>
			</div>
			<div class="theme_setting_description">
				<?php __('Required. Choose the formats that this dimension will be available to. For example, choosing short side dimension with formats "Landscape / Portrait / Square" will make those sizes available as options when you are creating prices for different print types.'); ?>
			</div>
		</div>
		<div style="clear: both"></div>
		<div class="basic_setting_cont">
			<label>&nbsp;</label>
			<div class="theme_setting_inputs_container">
				<input type="submit" value="Save" <?php if ($all_used == true): ?>disabled="disabled"<?php endif; ?> />
			</div>
		</div>
	</form>
</div>

