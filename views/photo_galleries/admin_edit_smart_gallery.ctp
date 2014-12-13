<?php
// smart galleries we are going to support
//	by photo tag
//	date range
//	private (by invitation only)
//		// send invitation code
//		// how long is the invitation valid
//		// just a hash that is good for a certain amount of time
//	photos of format (portrait, panaramic)
//	photos in other galleries
//  by date photo taken
//  by distance from location
//  photo connected to client
//  order by
	// alphabetical
	// asc or desc
	// id
	// created
	// date taken
?>


<h1><?php echo __('Edit Smart Gallery', true); ?>
	<div id="help_tour_button" class="custom_ui"><?php echo $this->Element('/admin/get_help_button'); ?></div>
</h1>
<!--	<p>A smart gallery lets you find photos automatically based on a number of optional factors. The advantage over a standard gallery is that you can set the parameters for the smart gallery and new photos will automatically be added to the smart gallery.</p>-->


<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('#date_added_from, #date_added_to, #date_taken_from, #date_taken_to').datepicker({
			onSelect: function(dateText, inst) {
				jQuery(this).removeClass('defaultTextActive');
			}
		});
		
		jQuery("#filter_photo_by_format").buttonset();
	});
</script>



<div class="page_content_header generic_basic_settings">
	<p><?php echo __('modify settings below', true); ?></p>
	<div style="clear: both;"></div>
</div>
<div class="generic_palette_container" data-step="1" data-intro="<?php echo __('Smart galleries allow photos to be easily added to galleries by batch selecting them.', true); ?>" data-position="top">
	<div class="fade_background_top"></div>
	<?php 
		$start_settings = array();
		$start_settings['tags'] = isset($this->data['PhotoGallery']['smart_settings']['tags']) ? $this->data['PhotoGallery']['smart_settings']['tags'] : array();
		$start_settings['date_added_from'] = isset($this->data['PhotoGallery']['smart_settings']['date_added_from']) ? $this->data['PhotoGallery']['smart_settings']['date_added_from'] : '';
		$start_settings['date_added_to'] = isset($this->data['PhotoGallery']['smart_settings']['date_added_to']) ? $this->data['PhotoGallery']['smart_settings']['date_added_to'] : '';
		$start_settings['date_taken_from'] = isset($this->data['PhotoGallery']['smart_settings']['date_taken_from']) ? $this->data['PhotoGallery']['smart_settings']['date_taken_from'] : '';
		$start_settings['date_taken_to'] = isset($this->data['PhotoGallery']['smart_settings']['date_taken_to']) ? $this->data['PhotoGallery']['smart_settings']['date_taken_to'] : '';
		$start_settings['photo_format'] = isset($this->data['PhotoGallery']['smart_settings']['photo_format']) ? $this->data['PhotoGallery']['smart_settings']['photo_format'] : array();
		$start_settings['order_by'] = isset($this->data['PhotoGallery']['smart_settings']['order_by']) ? $this->data['PhotoGallery']['smart_settings']['order_by'] : '';
		$start_settings['order_direction'] = isset($this->data['PhotoGallery']['smart_settings']['order_direction']) ? $this->data['PhotoGallery']['smart_settings']['order_direction'] : '';
	?>
	
	<form action="/admin/photo_galleries/edit_smart_gallery/<?php echo $id; ?>/" method="post" >
		<div class="generic_inner_container">
			<div class="generic_dark_cont fotomatter_form">
				<div class="input text">
					<label><?php echo __('Photo has tag', true); ?></label>
					<select name="data[smart_settings][tags][]" multiple="multiple" class="chzn-select" data-placeholder="Find Tags ...">
						<?php foreach ($tags as $tag): ?>
							<option value="<?php echo $tag['Tag']['id']; ?>" <?php if (in_array($tag['Tag']['id'], $start_settings['tags'])): ?>selected="selected"<?php endif; ?> ><?php echo $tag['Tag']['name']; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="input text" data-step="2" data-intro="<?php echo __('Add photos to the gallery that were uploaded from selected date to selected date.', true); ?>" data-position="top">
					<label><?php echo __('Photo Added to Site', true); ?></label>

					<?php $date_added_from_default = 'Beginning of Time'; ?>
					<?php $date_added_to_default = 'End of Time'; ?>
					<input type="hidden" name="data[smart_settings][date_added_from_default]" value="<?php echo $date_added_from_default; ?>" />
					<input type="hidden" name="data[smart_settings][date_added_to_default]" value="<?php echo $date_added_to_default; ?>" />
					<span><?php echo __('From', true); ?></span>
					<input id="date_added_from" class="defaultText" title="<?php echo $date_added_from_default; ?>" name="data[smart_settings][date_added_from]" type="text" value="<?php echo $start_settings['date_added_from']; ?>" />
					<span><?php echo __('To', true); ?></span>
					<input id="date_added_to" class="defaultText" title="<?php echo $date_added_to_default; ?>" name="data[smart_settings][date_added_to]" type="text" value="<?php echo $start_settings['date_added_to']; ?>" />
				</div>
				<div class="input text" data-step="3" data-intro="<?php echo __('Add photos to the gallery that were taken by you on selected dates.', true); ?>" data-position="top">
					<label><?php echo __('Photo Taken', true); ?></label>

					<?php $date_taken_from_default = 'Beginning of Time'; ?>
					<?php $date_taken_to_default = 'End of Time'; ?>
					<input type="hidden" name="data[smart_settings][date_taken_from_default]" value="<?php echo $date_taken_from_default; ?>" />
					<input type="hidden" name="data[smart_settings][date_taken_to_default]" value="<?php echo $date_taken_to_default; ?>" />
					<span><?php echo __('From', true); ?></span>
					<input id="date_taken_from" class="defaultText" title="<?php echo $date_taken_from_default; ?>" name="data[smart_settings][date_taken_from]" type="text" value="<?php echo $start_settings['date_taken_from']; ?>" />
					<span><?php echo __('To', true); ?></span>
					<input id="date_taken_to" class="defaultText" title="<?php echo $date_taken_to_default; ?>" name="data[smart_settings][date_taken_to]" type="text" value="<?php echo $start_settings['date_taken_to']; ?>" />
				</div>
				<div class="input custom_ui" data-step="4" data-intro="<?php echo __('Format all the photos by choosing one or more of the following.', true); ?>" data-position="top">
					<label><?php echo __('Format of Photo', true); ?></label>
					<div id="filter_photo_by_format">
						<input type="checkbox" name="data[smart_settings][photo_format][]" value="landscape" <?php if (in_array('landscape', $start_settings['photo_format'])): ?>checked="checked"<?php endif; ?> id="check1" />
						<label class="add_button" for="check1"><div class="content"><?php echo __('Landscape', true); ?></div></label>
						<input type="checkbox" name="data[smart_settings][photo_format][]" value="portrait" <?php if (in_array('portrait', $start_settings['photo_format'])): ?>checked="checked"<?php endif; ?> id="check2" />
						<label class="add_button" for="check2"><div class="content"><?php echo __('Portrait', true); ?></div></label>
						<input type="checkbox" name="data[smart_settings][photo_format][]" value="square" <?php if (in_array('square', $start_settings['photo_format'])): ?>checked="checked"<?php endif; ?> id="check3" />
						<label class="add_button" for="check3"><div class="content"><?php echo __('Square', true); ?></div></label>
						<input type="checkbox" name="data[smart_settings][photo_format][]" value="panoramic" <?php if (in_array('panoramic', $start_settings['photo_format'])): ?>checked="checked"<?php endif; ?> id="check4" />
						<label class="add_button" for="check4"><div class="content"><?php echo __('Panoramic', true); ?></div></label>
						<input type="checkbox" name="data[smart_settings][photo_format][]" value="vertical_panoramic" <?php if (in_array('vertical_panoramic', $start_settings['photo_format'])): ?>checked="checked"<?php endif; ?> id="check5" />
						<label class="add_button" for="check5"><div class="content"><?php echo __('Vertical Panoramic', true); ?></div></label>
					</div>
				</div>
				<div style="clear: both"></div>
				<div class="input text">
					<label><?php echo __('Order/Sort By', true); ?></label>
					<select name="data[smart_settings][order_by]">
						<option value="created" <?php if ($start_settings['order_by'] == 'created'): ?>selected="selected"<?php endif; ?> >Date Added</option>
						<option value="date_taken" <?php if ($start_settings['order_by'] == 'date_taken'): ?>selected="selected"<?php endif; ?> >Date Taken</option>
						<option value="display_title" <?php if ($start_settings['order_by'] == 'display_title'): ?>selected="selected"<?php endif; ?> >Photo Name</option>
					</select>
				</div>
				<div class="input text">
					<label><?php echo __('Order Direction', true); ?></label>
					<select name="data[smart_settings][order_direction]">
						<option value="desc" <?php if ($start_settings['order_direction'] == 'desc'): ?>selected="selected"<?php endif; ?> >Descending</option>
						<option value="asc" <?php if ($start_settings['order_direction'] == 'asc'): ?>selected="selected"<?php endif; ?> >Ascending</option>
					</select>
				</div>
			</div>
		</div>
		<div class="submit save_button javascript_submit">
			<div class="content"><?php echo __('Save', true); ?></div>
		</div>
	</form>
</div>
