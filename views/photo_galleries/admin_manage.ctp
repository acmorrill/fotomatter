<script src="/js/angular_1.2.22/app/js/app.js"></script>
<script src="/js/angular_1.2.22/app/js/controllers.js"></script>
<script src="/js/angular_1.2.22/app/js/services.js"></script>
<!--<script src="/js/angular_1.2.22/app/js/directives.js"></script>-->
<!--<script src="/js/angular_1.2.22/bower_components/checklist-model/checklist-model.js"></script>-->

<?php
	$last_open_gallery_id_str = '';
	if (!empty($_COOKIE['last_open_gallery_id']) && !empty($_COOKIE['last_open_gallery_type'])) {
		$last_open_gallery_id_str = "last_open_gallery_id={$_COOKIE['last_open_gallery_id']};last_open_gallery_type=\"{$_COOKIE['last_open_gallery_type']}\";";
	}
	if (!empty($_COOKIE['last_on_photo_upload'])) {
		$last_open_gallery_id_str .= "last_on_photo_upload=\"true\";";
	}
	$last_open_gallery_id_str  = "ng-init='$last_open_gallery_id_str'";
?>


<div ng-app="fotomatterApp" ng-controller="GalleriesCtrl" <?php echo $last_open_gallery_id_str; ?>>
	<?php /*<h1><?php echo __('Galleries', true); ?>
		<div id="help_tour_button" class="custom_ui"><?php echo $this->Element('/admin/get_help_button'); ?></div>
	</h1>
	<p>
		<?php echo __('Add/delete galleries and manage the photos inside your galleries.', true); ?>
	</p>*/ ?>
	<div style="clear: both;"></div>

	<div class="clear"></div>

	<div class="gallery_view ng-hide" ng-show="open_gallery == 'empty' && open_smart_gallery == 'empty'" style="position: relative; min-height: 500px;">
		<div class="empty_help_content" style="display: block;">
			&#9668;&nbsp;<?php echo __('Choose a Gallery at Left', true); ?>
		</div>
	</div>

	<div class="gallery_view" ng-show="open_gallery != null && open_gallery != 'empty'">
		<?php echo $this->Element('admin/gallery/angular_edit_gallery_connect_photos'); ?>
	</div>
	<div class="gallery_view" ng-show="open_smart_gallery != null && open_smart_gallery != 'empty'">
		<?php echo $this->Element('admin/gallery/angular_edit_smart_gallery'); ?>
	</div>
	<div class="gallery_view" ng-show="upload_to_gallery != null && upload_to_gallery != 'empty'">
		<?php echo $this->Element('admin/gallery/angular_upload_to_gallery'); ?>
	</div>
	<div class="dynamic_list">
		<div id="gallery_list_tools">
			<div id="gallery_list_tools_inner" class="custom_ui">
				<select id="add_gallery_type">
					<option value="standard"><?php echo __('Standard', true); ?></option>
					<option value="smart"><?php echo __('Smart', true); ?></option>
				</select>
				<div class="add_button icon" ng-click="create_gallery()">
					<div class="icon-_button-01"></div>
				</div>
			</div>
		</div>
		<div id="photo_gallery_list" class="table_container">
			<table class="list" ui-sortable="gallerySortableOptions" data-step="1" data-intro="<?php echo __ ('Here you can view all of the galleries currently created. Edit the titles, manage the photos, or delete the gallery completely.', true); ?>" data-position="top">
				<thead>
					<tr> 
						<?php /* <?php if ($this->Paginator->sortKey('Photo') == 'Photo.id'): ?> curr <?php echo $sort_dir; ?><?php endif; ?> */ ?>
						<?php /* <?php echo $this->Paginator->sort(__('Photo ID', true), 'Photo.id'); ?> */ ?>
						<th class="first">
						</th> 
						<th class="last">
							<div class="content one_line">
								<?php echo __('Display Name', true); ?>
							</div>
						</th> 
						<?php /*
						<th class="mobile_hide">
							<div class="content one_line">
								<?php echo __('Description', true); ?>
							</div>
						</th> 
						 * 
						 */ ?>
	<!--					<th class="mobile_hide">
							<div class="content one_line">
								<?php echo __('Gallery Type', true); ?>
							</div>
						</th> -->
						<?php /*<th class="last">
							<div class="content one_line">
								<?php echo __('Actions', true); ?>
							</div>
						</th> */ ?>
					</tr> 
				</thead>
				<tbody>
					<tr class="spacer"><td colspan="1"></td></tr>

					<tr class="first last ng-hide" ng-show="loading == true">
						<td class="first last" colspan="1" style="text-align: center;">
							<span>LOADING</span>
						</td>
					</tr>

					<tr class="first last ng-hide" ng-show="photo_galleries.length == 0 && loading == false">
						<td class="first last" colspan="1">
							<span>You don't have any galleries</span>
						</td>
					</tr>


					<tr ng-repeat="photo_gallery in photo_galleries" ng-class="{'sortable': true, 'current': last_open_gallery_id == photo_gallery.PhotoGallery.id}" gallery_id="{{photo_gallery.PhotoGallery.id}}">
						<?php /*<td class="gallery_id first">
							<div class="rightborder"></div>
							<div class="reorder_gallery_grabber reorder_grabber icon-position-01" />
						</td> */ ?>
						<td class="gallery_name gallery_id first last">
							<table>
								<tbody>
									<tr>
										<td class="first">
											<div class="reorder_gallery_grabber reorder_grabber icon-position-01" />
										</td>
										<td class="last">
											<span>{{photo_gallery.PhotoGallery.display_name}}</span>
										</td>
									</tr>
									<tr>
										<td colspan="2">
											<span class="custom_ui">
												<div ng-class="{'selected': last_open_gallery_id == photo_gallery.PhotoGallery.id && (upload_to_gallery == null || upload_to_gallery == 'empty')}" class="add_button icon" ng-click="view_gallery(photo_gallery.PhotoGallery.id, 0, photo_gallery.PhotoGallery.type)">
													<div class="content icon-managePhotos-01 ng-hide" ng-show="photo_gallery.PhotoGallery.type == 'standard'"></div>
													<div class="content icon-gallerySettings-01 ng-hide" ng-show="photo_gallery.PhotoGallery.type == 'smart'"></div>
												</div>
												<div ng-class="{'selected': last_open_gallery_id == photo_gallery.PhotoGallery.id && upload_to_gallery != null && upload_to_gallery != 'empty'}" class="add_button icon ng-hide" ng-click="upload_photos_to_gallery(photo_gallery)" ng-show="photo_gallery.PhotoGallery.type == 'standard'">
													<div class="content icon-pictureUpload-01"></div>
												</div>
												<span ng-click="delete_gallery(photo_gallery)" confirm-delete >
													<div class="add_button icon icon_close"><div class="content icon-close-01"></div></div>
												</span>
											</span>
										</td>
									</tr>
								</tbody>
							</table>
						</td> 
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>



						