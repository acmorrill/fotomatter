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


<?php echo $this->Session->flash(); ?>
<br/>

<style type="text/css">
	/* DREW TODO - style on this page must be totatally redone */
	.test_container  {
		padding: 30px;
	}
	.test_container p {
		width: 600px;
		margin-bottom: 20px;
	}
	.test_container input label {
		vertical-align: top;
		margin-top: 7px;
		margin-bottom: -14px;
		display: block;
	}
	.test_container select.chzn-select {
		width: 300px;
		vertical-align: top;
	}
	.test_container .chzn-container, .test_container .chzn-drop {
		color: #333;
	}
	.test_container div.input {
		margin-bottom: 30px;
	}
</style>

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

<div class="test_container">
<!--	<p>A smart gallery lets you find photos automatically based on a number of optional factors. The advantage over a standard gallery is that you can set the parameters for the smart gallery and new photos will automatically be added to the smart gallery.</p>-->
	
	<?php //debug($this->data); ?>
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
	
	<?php //debug($this->data); ?>
	
	<form action="/admin/photo_galleries/edit_smart_gallery/<?php echo $id; ?>/" method="post" >
		<div class="input text">
			<label>Photo has tag</label><br style="clear: both;"/>
			<select name="data[smart_settings][tags][]" multiple="multiple" class="chzn-select" data-placeholder="Find Tags ...">
				<?php foreach ($tags as $tag): ?>
					<option value="<?php echo $tag['Tag']['id']; ?>" <?php if (in_array($tag['Tag']['id'], $start_settings['tags'])): ?>selected="selected"<?php endif; ?> ><?php echo $tag['Tag']['name']; ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="input text">
			<label>Photo Added to Site</label><br style="clear: both;"/>
			
			<?php $date_added_from_default = 'Beginning of Time'; ?>
			<?php $date_added_to_default = 'End of Time'; ?>
			<input type="hidden" name="data[smart_settings][date_added_from_default]" value="<?php echo $date_added_from_default; ?>" />
			<input type="hidden" name="data[smart_settings][date_added_to_default]" value="<?php echo $date_added_to_default; ?>" />
			From <input id="date_added_from" class="defaultText" title="<?php echo $date_added_from_default; ?>" name="data[smart_settings][date_added_from]" type="text" value="<?php echo $start_settings['date_added_from']; ?>" /> To <input id="date_added_to" class="defaultText" title="<?php echo $date_added_to_default; ?>" name="data[smart_settings][date_added_to]" type="text" value="<?php echo $start_settings['date_added_to']; ?>" />
		</div>
		<div class="input text">
			<label>Photo Taken</label><br style="clear: both;"/>
			
			<?php $date_taken_from_default = 'Beginning of Time'; ?>
			<?php $date_taken_to_default = 'End of Time'; ?>
			<input type="hidden" name="data[smart_settings][date_taken_from_default]" value="<?php echo $date_taken_from_default; ?>" />
			<input type="hidden" name="data[smart_settings][date_taken_to_default]" value="<?php echo $date_taken_to_default; ?>" />
			From <input id="date_taken_from" class="defaultText" title="<?php echo $date_taken_from_default; ?>" name="data[smart_settings][date_taken_from]" type="text" value="<?php echo $start_settings['date_taken_from']; ?>" /> To <input id="date_taken_to" class="defaultText" title="<?php echo $date_taken_to_default; ?>" name="data[smart_settings][date_taken_to]" type="text" value="<?php echo $start_settings['date_taken_to']; ?>" />
		</div>
		<div class="input custom_ui_radio">
			<label>Format of Photo</label><br style="clear: both;"/>
			<div id="filter_photo_by_format">
				<input type="checkbox" name="data[smart_settings][photo_format][]" value="landscape" <?php if (in_array('landscape', $start_settings['photo_format'])): ?>checked="checked"<?php endif; ?> id="check1" /><label for="check1"><?php __('Landscape'); ?></label>
				<input type="checkbox" name="data[smart_settings][photo_format][]" value="portrait" <?php if (in_array('portrait', $start_settings['photo_format'])): ?>checked="checked"<?php endif; ?> id="check2" /><label for="check2"><?php __('Portrait'); ?></label>
				<input type="checkbox" name="data[smart_settings][photo_format][]" value="square" <?php if (in_array('square', $start_settings['photo_format'])): ?>checked="checked"<?php endif; ?> id="check3" /><label for="check3"><?php __('Square'); ?></label>
				<input type="checkbox" name="data[smart_settings][photo_format][]" value="panoramic" <?php if (in_array('panoramic', $start_settings['photo_format'])): ?>checked="checked"<?php endif; ?> id="check4" /><label for="check4"><?php __('Panoramic'); ?></label>
				<input type="checkbox" name="data[smart_settings][photo_format][]" value="vertical_panoramic" <?php if (in_array('vertical_panoramic', $start_settings['photo_format'])): ?>checked="checked"<?php endif; ?> id="check5" /><label for="check5"><?php __('Vertical Panoramic'); ?></label>
			</div>
		</div>
		<div style="clear: both"></div>
		<div class="input text">
			<label>Order By</label><br style="clear: both;"/>
			<select name="data[smart_settings][order_by]">
				<option value="created" <?php if ($start_settings['order_by'] == 'created'): ?>selected="selected"<?php endif; ?> >Date Added</option>
				<option value="date_taken" <?php if ($start_settings['order_by'] == 'date_taken'): ?>selected="selected"<?php endif; ?> >Date Taken</option>
				<option value="display_title" <?php if ($start_settings['order_by'] == 'display_title'): ?>selected="selected"<?php endif; ?> >Photo Name</option>
			</select>
		</div>
		<div class="input text">
			<label>Order Direction</label><br style="clear: both;"/>
			<select name="data[smart_settings][order_direction]">
				<option value="desc" <?php if ($start_settings['order_direction'] == 'desc'): ?>selected="selected"<?php endif; ?> >Descending</option>
				<option value="asc" <?php if ($start_settings['order_direction'] == 'asc'): ?>selected="selected"<?php endif; ?> >Ascending</option>
			</select>
		</div>
	
		<input type="submit" value="save" />

	</form>
</div>
