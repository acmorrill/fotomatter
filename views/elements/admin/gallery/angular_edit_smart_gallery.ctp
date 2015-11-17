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


<h1><span onbeforesave="edit_gallery_name($data, open_smart_gallery.PhotoGallery.id)" editable-text="open_smart_gallery.PhotoGallery.display_name">{{open_smart_gallery.PhotoGallery.display_name}}</span>
	<div id="help_tour_button" class="custom_ui"><?php echo $this->Element('/admin/get_help_button'); ?></div>
</h1>
<p onbeforesave="edit_gallery_description($data, open_smart_gallery.PhotoGallery.id)" editable-text="open_smart_gallery.PhotoGallery.description">{{open_smart_gallery.PhotoGallery.description || "Gallery Description Empty" }}</p>


<script type="text/javascript">
//	jQuery(document).ready(function() {
//		jQuery('#date_added_from, #date_added_to, #date_taken_from, #date_taken_to').datepicker({
//			onSelect: function(dateText, inst) {
//				jQuery(this).removeClass('defaultTextActive');
//			}
//		});
//		
//		jQuery("#filter_photo_by_format").buttonset();
//	});
</script>



<div class="page_content_header generic_basic_settings">
	<p><?php echo __('modify settings below', true); ?></p>
	<div style="clear: both;"></div>
</div>
<div class="generic_palette_container" data-step="1" data-intro="<?php echo __('Smart galleries allow photos to be easily added to galleries by selecting photos in batches.', true); ?>" data-position="top">
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
	
	<!--<form action="/admin/photo_galleries/edit_smart_gallery//" method="post" >-->
		<div class="generic_inner_container">
			<div class="generic_dark_cont fotomatter_form">
				<div class="input text">
					<label><?php echo __('Photo has tag', true); ?></label>
					<select ng-model="open_smart_gallery.PhotoGallery.smart_settings.tags" ng-options="tag as tag.Tag.name for tag in tags track by tag.Tag.name" class="gallery_tags" style="width: 300px;" name="data[smart_settings][tags][]" multiple="multiple" data-placeholder="Find Tags ..." ng-change="change_smart_gallery_setting()"></select>
				</div>
				<div class="input text" data-step="2" data-intro="<?php echo __('Add photos to the gallery that were uploaded from selected date to selected date.', true); ?>" data-position="top">
					<label><?php echo __('Photo Added to Site', true); ?></label>

					<?php $date_added_from_default = 'Beginning of Time'; ?>
					<?php $date_added_to_default = 'End of Time'; ?>
					<span><?php echo __('From', true); ?></span>
					<input id="date_added_from" class="defaultText" title="<?php echo $date_added_from_default; ?>" name="data[smart_settings][date_added_from]" type="text" ng-model="open_smart_gallery.PhotoGallery.smart_settings.date_added_from" value="open_smart_gallery.PhotoGallery.smart_settings.date_added_from" ng-change="change_smart_gallery_setting()" />
					<span><?php echo __('To', true); ?></span>
					<input id="date_added_to" class="defaultText" title="<?php echo $date_added_to_default; ?>" name="data[smart_settings][date_added_to]" type="text" ng-model="open_smart_gallery.PhotoGallery.smart_settings.date_added_to" value="open_smart_gallery.PhotoGallery.smart_settings.date_added_from" ng-change="change_smart_gallery_setting()" />
				</div>
				<div class="input text" data-step="3" data-intro="<?php echo __('Add photos to the gallery that were taken by you on selected dates.', true); ?>" data-position="top">
					<label><?php echo __('Photo Taken', true); ?></label>

					<?php $date_taken_from_default = 'Beginning of Time'; ?>
					<?php $date_taken_to_default = 'End of Time'; ?>
					<span><?php echo __('From', true); ?></span>
					<input id="date_taken_from" class="defaultText" title="<?php echo $date_taken_from_default; ?>" name="data[smart_settings][date_taken_from]" type="text" ng-model="open_smart_gallery.PhotoGallery.smart_settings.date_taken_from" value="{{open_smart_gallery.PhotoGallery.smart_settings.date_taken_from}}" ng-change="change_smart_gallery_setting()" />
					<span><?php echo __('To', true); ?></span>
					<input id="date_taken_to" class="defaultText" title="<?php echo $date_taken_to_default; ?>" name="data[smart_settings][date_taken_to]" type="text" ng-model="open_smart_gallery.PhotoGallery.smart_settings.date_taken_to" value="{{open_smart_gallery.PhotoGallery.smart_settings.date_taken_to}}" ng-change="change_smart_gallery_setting()" />
				</div>
				<div class="input custom_ui" data-step="4" data-intro="<?php echo __('Format all the photos by choosing one or more of the following.', true); ?>" data-position="top">
					<label><?php echo __('Format of Photo', true); ?></label>
					<div id="smart_filter_photo_by_format">
						<input type="checkbox" ng-model="open_smart_gallery_photo_formats.landscape" ng-change="change_smart_gallery_setting()" value="landscape" id="check6" />
						<label class="add_button" for="check6"><div class="content"><?php echo __('Landscape', true); ?></div></label>
						<input type="checkbox" ng-model="open_smart_gallery_photo_formats.portrait" ng-change="change_smart_gallery_setting()" value="portrait" id="check7" />
						<label class="add_button" for="check7"><div class="content"><?php echo __('Portrait', true); ?></div></label>
						<input type="checkbox" ng-model="open_smart_gallery_photo_formats.square" ng-change="change_smart_gallery_setting()" value="square" id="check8" />
						<label class="add_button" for="check8"><div class="content"><?php echo __('Square', true); ?></div></label>
						<input type="checkbox" ng-model="open_smart_gallery_photo_formats.panoramic" ng-change="change_smart_gallery_setting()" value="panoramic" id="check9" />
						<label class="add_button" for="check9"><div class="content"><?php echo __('Panoramic', true); ?></div></label>
						<input type="checkbox" ng-model="open_smart_gallery_photo_formats.vertical_panoramic" ng-change="change_smart_gallery_setting()" value="vertical_panoramic" id="check10" />
						<label class="add_button" for="check10"><div class="content"><?php echo __('Vertical Panoramic', true); ?></div></label>
					</div>
				</div>
				<div style="clear: both"></div>
				<div class="input text">
					<label><?php echo __('Order/Sort By', true); ?></label>
					<select name="data[smart_settings][order_by]" ng-model="open_smart_gallery.PhotoGallery.smart_settings.order_by" ng-change="change_smart_gallery_setting()">
						<option value="created">Date Added</option>
						<option value="date_taken">Date Taken</option>
						<option value="display_title">Photo Name</option>
					</select>
				</div>
				<div class="input text">
					<label><?php echo __('Order Direction', true); ?></label>
					<select name="data[smart_settings][order_direction]"  ng-model="open_smart_gallery.PhotoGallery.smart_settings.order_direction" ng-change="change_smart_gallery_setting()">
						<option value="desc">Descending</option>
						<option value="asc">Ascending</option>
					</select>
				</div>
			</div>
		</div>
	<!--</form>-->
</div>
