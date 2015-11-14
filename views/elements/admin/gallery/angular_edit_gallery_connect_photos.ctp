<div id="connect_gallery_photos_cont">
	<h1><span onbeforesave="edit_gallery_name($data, open_gallery.PhotoGallery.id)" editable-text="open_gallery.PhotoGallery.display_name">{{open_gallery.PhotoGallery.display_name}}</span>
		<div id="help_tour_button" class="custom_ui"><?php echo $this->Element('/admin/get_help_button'); ?></div>
	</h1>
	<p onbeforesave="edit_gallery_description($data, open_gallery.PhotoGallery.id)" editable-text="open_gallery.PhotoGallery.description">{{open_gallery.PhotoGallery.description || "Gallery Description Empty" }}</p>
	
	<?php /*
	 * 
	 * <a onbeforesave="edit_tag($data, tag.Tag.id)" href="#" editable-text="tag.Tag.name">{{ tag.Tag.name || "empty" }}</a>
	 * 
	 */ ?>
	<?php /*
	<p><?php echo __('Easily manage your photos by adding and deleting uploaded photos to this gallery below.', true); ?></p>
	<br />*/ ?>
	<div style="clear: both;"></div>	
	
	<div class="page_content_header custom_ui">
		<div id='add_gallery_filters_cont' data-step="3" data-intro="<?php echo __('Filter the uploaded images by viewing only photos that have not been added to a gallery yet.', true); ?>" data-position="left">
			<div id="photos_not_in_a_gallery_cont" class="custom_ui_radio">
				<input ng-model="open_gallery_photos_not_in_gallery" name="open_gallery_photos_not_in_gallery" ng-change="change_filters_sort()" type="checkbox" id="photos_not_in_a_gallery" />
				<label class='add_button' for="photos_not_in_a_gallery"><div class='content'><?php echo __('Photos Not In A Gallery', true); ?></div></label>
			</div>
		</div><?php /*
		*/ ?><div class="generic_sort_and_filters" data-step="4" data-intro="<?php echo __('Filter by the photo orientation you’ve assigned your image in Photo Details.', true); ?>" data-position="bottom"><?php /*
			*/ ?><div id="filter_photo_by_format"><?php /*
				*/ ?><input type="checkbox" ng-model="open_gallery_photo_formats.vertical_panoramic" name="open_gallery_photo_formats" ng-change="change_filters_sort()" value="vertical_panoramic" id="check5" /><?php /*
				*/ ?><label class='add_button' for="check5"><div class='content'><?php echo __('Vertical Panoramic', true); ?></div></label><?php /*
				*/ ?><input type="checkbox" ng-model="open_gallery_photo_formats.panoramic" name="open_gallery_photo_formats" ng-change="change_filters_sort()" value="panoramic" id="check4" /><?php /*
				*/ ?><label class='add_button' for="check4"><div class='content'><?php echo __('Panoramic', true); ?></div></label><?php /*
				*/ ?><input type="checkbox" ng-model="open_gallery_photo_formats.square" name="open_gallery_photo_formats" ng-change="change_filters_sort()" value="square" id="check3" /><?php /*
				*/ ?><label class='add_button' for="check3"><div class='content'><?php echo __('Square', true); ?></div></label><?php /*
				*/ ?><input type="checkbox" ng-model="open_gallery_photo_formats.portrait" name="open_gallery_photo_formats" ng-change="change_filters_sort()" value="portrait" id="check2" /><?php /*
				*/ ?><label class='add_button' for="check2"><div class='content'><?php echo __('Portrait', true); ?></div></label><?php /*
				*/ ?><input type="checkbox" ng-model="open_gallery_photo_formats.landscape" name="open_gallery_photo_formats"  ng-change="change_filters_sort()" value="landscape" id="check1" /><?php /*
				*/ ?><label class='add_button' for="check1"><div class='content'><?php echo __('Landscape', true); ?></div></label><?php /*
				*/ ?><div style="clear: both;"></div><?php /*
			*/ ?></div><?php /*
		*/ ?></div><?php /*
		*/ ?><div id="not_in_gallery_icon_size" class="box_icon_size custom_ui" data-step="5" data-intro="<?php echo __('Change the viewing size of your photos below to easily arrange.', true); ?>" data-position="bottom"><?php /*
			*/ ?><div id="small_icon" size="small" ng-class="{ 'add_button': true, 'selected': open_gallery_image_size == 'small'}" ng-click="change_image_size('small')"><?php /*
					*/ ?><div class="content">S</div><?php /*
				*/ ?></div><?php /*
				*/ ?><div id="medium_icon" size="medium" ng-class="{ 'add_button': true, 'selected': open_gallery_image_size == 'medium'}" ng-click="change_image_size('medium')"><?php /*
					*/ ?><div class="content">M</div><?php /*
				*/ ?></div><?php /*
				*/ ?><div id="large_icon" size="large" ng-class="{ 'add_button': true, 'selected': open_gallery_image_size == 'large'}" ng-click="change_image_size('large')"><?php /*
					*/ ?><div class="content">L</div><?php /*
				*/ ?></div><?php /*
			*/ ?>
			</div>
		<div style="clear: both;"></div>
	</div>
	
	<div class='table_container custom_ui'>
		<div class="fade_background_top"></div>
		<div class="in_gallery_main_cont" data-step="6" data-intro="<?php echo __('Change the order you’d like your images to appear on your site by dragging the arrows on the image and moving the photo. To remove the photo from the gallery (but not from uploaded photos), simply click the X on the photo.', true); ?>" data-position="top">
			<div class="image_container_header">
				<h2>{{open_gallery.PhotoGallery.display_name}}</h2>
				<div class="actions" data-step="7" data-intro="<?php echo __('To remove all photos in the gallery (but not from uploaded photos), click the trash.', true); ?>" data-position="bottom"><img id="remove_all_gallery_photos" src="/img/admin/icons/grey_delete_all_icon.png" alt="" ng-click="remove_all_photos_from_gallery()" /></div>
				<div style="clear: both;"></div>
			</div>

			<?php /*<div class="empty_help_content" ng-hide="open_gallery_connected_photos != null" style="display: block;">
				<?php echo __('Loading', true); ?>
			</div>*/ ?>
			<div class="empty_help_content" ng-show="open_gallery_connected_photos.length == 0" style="display: block;">
				<?php echo __('Add images to this gallery using the box at right', true); ?>&nbsp;►
			</div>
			
			<div id="in_gallery_photos_cont" class="in_gallery_photos_cont" ui-sortable="inGalleryPhotosSortableOptions" ng-model="open_gallery_connected_photos">
				<?php echo $this->Element('/admin/photo/angular_photo_connect_in_gallery_photo_cont'); ?>
			</div>
		</div>
		<div class="not_in_gallery_main_cont" data-step="1" data-intro="<?php echo __('Here are all of your uploaded photos. To add them to the current gallery, simply click the plus symbol (on the bottom right of each image).', true); ?>" data-position="top">
			<div class="image_container_header">
				<h2 data-step="8" data-intro="<?php echo __('Now that you’ve created a gallery, upload more photos directly to the gallery by selecting the gallery name on the upload photos page before uploading.', true); ?>" data-position="top"><?php echo __('Uploaded Photos', true); ?></h2>
				<div class="actions" style="float: right;"><img id="refresh_not_in_gallery_photos_button" src="/img/admin/icons/grey_refresh.png" alt="" ng-click="refresh_not_in_gallery_photos()" /></div>
				<div id="sort_photo_radio" data-step="2" data-intro="<?php echo __('You may sort your uploaded photos by most (or least) recently added.', true); ?>" data-position="left"><?php /*
					*/ ?><input type="radio" id="radio1" ng-model="open_gallery_not_connected_sort_dir" name="sort_photo_radio"  value="desc" ng-change="change_filters_sort()" /><?php /*
					*/ ?><label class='add_button' for="radio1"><div class='content'><?php echo __('Newest', true); ?></div></label><?php /*
					*/ ?><input type="radio" id="radio2" ng-model="open_gallery_not_connected_sort_dir" name="sort_photo_radio" value="asc" ng-change="change_filters_sort()" /><?php /*
					*/ ?><label class='add_button' for="radio2"><div class='content'><?php echo __('Oldest', true); ?></div></label><?php /*
				*/ ?></div>
				<div style="clear: both;"></div>
			</div>
			
			
			<div class="empty_help_content">
				<?php echo __('No photos found <br/> Add photos <a href="/admin/photos">on the photo page</a>', true); ?>
			</div>
			<div id="endless_scroll_div" class="not_in_gallery_photos_cont">
				<?php echo $this->Element('/admin/photo/angular_photo_connect_not_in_gallery_photo_cont'); ?>
			</div>
			<div style="clear: both;"></div>
		</div>
	</div>
	</div>




