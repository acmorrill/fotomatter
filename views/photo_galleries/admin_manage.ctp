<script src="/js/angular_1.2.22/app/js/app.js"></script>
<script src="/js/angular_1.2.22/app/js/controllers.js"></script>
<script src="/js/angular_1.2.22/app/js/services.js"></script>
<!--<script src="/js/angular_1.2.22/app/js/directives.js"></script>-->
<!--<script src="/js/angular_1.2.22/bower_components/checklist-model/checklist-model.js"></script>-->

<?php
	$last_open_gallery_id_str = '';
	if (!empty($_COOKIE['last_open_gallery_id']) && !empty($_COOKIE['last_open_gallery_type'])) {
		$last_open_gallery_id_str = "ng-init='last_open_gallery_id={$_COOKIE['last_open_gallery_id']};last_open_gallery_type=\"{$_COOKIE['last_open_gallery_type']}\"'";
	} else {
		$first_gallery = $this->Gallery->get_first_gallery_by_weight();
		if (!empty($first_gallery['PhotoGallery']['id'])) {
			$last_open_gallery_id_str = "ng-init='last_open_gallery_id={$first_gallery['PhotoGallery']['id']};last_open_gallery_type=\"{$first_gallery['PhotoGallery']['type']}\"'";
		}
	}
?>


<div ng-app="fotomatterApp" ng-controller="GalleriesCtrl" <?php echo $last_open_gallery_id_str; ?>>
	<?php /*<h1><?php echo __('Galleries', true); ?>
		<div id="help_tour_button" class="custom_ui"><?php echo $this->Element('/admin/get_help_button'); ?></div>
	</h1>
	<p>
		<?php echo __('Add/delete galleries and manage the photos inside your galleries.', true); ?>
	</p>*/ ?>
	<div style="clear: both;"></div>

	<div class="right" data-step="2" data-intro="<?php echo __('To create a new gallery, choose from two options: standard or smart.', true); ?>" data-position="left">
		<?php echo $this->Element('admin/gallery/add_gallery'); ?>
	</div>
	<div class="clear"></div>


	<div class="gallery_view" ng-show="open_gallery != null">
		<?php echo $this->Element('admin/gallery/angular_edit_gallery_connect_photos'); ?>
	</div>
	<div class="gallery_view" ng-show="open_smart_gallery != null">
		<?php echo $this->Element('admin/gallery/angular_edit_smart_gallery'); ?>
	</div>
	<?php /*<div class="gallery_view" ng-show="open_gallery == null">
		<h1>Gallery Loading</h1>
	</div>*/ ?>
	<div class="dynamic_list">
		<div id="photo_gallery_list" class="table_container">
			<div class="fade_background_top"></div>
			<div class="table_top"></div>
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
											<span>{{photo_gallery.PhotoGallery.display_name}} - {{photo_gallery.PhotoGallery.type}}</span>
										</td>
									</tr>
									<tr>
										<td colspan="2">
											<span class="custom_ui">
												<?php /*<div class="add_button" ng-click="view_gallery(photo_gallery.PhotoGallery.id)">
													<div class="content"><?php echo __('Edit', true); ?></div>
													<div class="right_arrow_lines icon-arrow-01"><div></div></div>
												</div>*/ ?>
												<div ng-class="{'selected': last_open_gallery_id == photo_gallery.PhotoGallery.id}" class="add_button icon" ng-click="view_gallery(photo_gallery.PhotoGallery.id, 0, photo_gallery.PhotoGallery.type)">
													<div class="content icon-picture"></div>
												</div>
												<div class="add_button icon" ng-click="upload_to_gallery()">
													<div class="content icon-pictureUpload-01"></div>
												</div>
												<a class="delete_link" href="/admin/photo_galleries/delete_gallery//"><div class="add_button icon icon_close"><div class="content icon-close-01"></div></div></a>
											</span>
										</td>
									</tr>
								</tbody>
							</table>
						</td> 
						<?php /*<td class="gallery_action last table_actions">
							<span class="custom_ui">
								<div class="add_button" ng-click="view_gallery(photo_gallery.PhotoGallery.id)">
									<div class="content"><?php echo __('Edit', true); ?></div>
									<div class="right_arrow_lines icon-arrow-01"><div></div></div>
								</div>
								<a class="delete_link" href="/admin/photo_galleries/delete_gallery//"><div class="add_button icon icon_close"><div class="content icon-close-01"></div></div></a>
							</span>
						</td> */ ?>
					</tr>

					<?php /*
					<?php foreach($galleries as $curr_gallery): ?> 
						<tr gallery_id="<?php echo $curr_gallery['PhotoGallery']['id']; ?>">
							<td class="gallery_id first">
								<div class="rightborder"></div>
								<div class="reorder_gallery_grabber reorder_grabber icon-position-01" />
							</td> 
							<td class="gallery_name ">
								<div class="rightborder"></div>
								<span><?php echo $curr_gallery['PhotoGallery']['display_name']; ?></span>
							</td> 
							<td class="gallery_action last table_actions">
								<span class="custom_ui">
									<a href="/admin/photo_galleries/edit_gallery/<?php echo $curr_gallery['PhotoGallery']['id']; ?>/">
										<div class="add_button" <?php echo $edit_button_help; ?>>
											<div class="content"><?php echo __('Edit', true); ?></div>
											<div class="right_arrow_lines icon-arrow-01"><div></div></div>
										</div>
									</a>
									<?php if ($curr_gallery['PhotoGallery']['type'] == 'smart'): ?>
										<a href="/admin/photo_galleries/edit_smart_gallery/<?php echo $curr_gallery['PhotoGallery']['id']; ?>/">
											<div class="add_button" <?php echo $configure_button_help; ?>>
												<div class="content"><?php echo __('Configure', true); ?></div>
												<div class="right_arrow_lines icon-arrow-01"><div></div></div>
											</div>
										</a>
									<?php else: ?>
										<a href="/admin/photo_galleries/edit_gallery_connect_photos/<?php echo $curr_gallery['PhotoGallery']['id']; ?>/">
											<div class="add_button" <?php echo $manage_button_help; ?>>
												<div class="content"><?php echo __('Manage Photos', true); ?></div>
												<div class="right_arrow_lines icon-arrow-01"><div></div></div>
											</div>
										</a>
									<?php endif; ?>
									<a class="delete_link" href="/admin/photo_galleries/delete_gallery/<?php echo $curr_gallery['PhotoGallery']['id']; ?>/"><div class="add_button icon icon_close" <?php echo $x_button_help; ?>><div class="content icon-close-01"></div></div></a>
								</span>
							</td>
						</tr>
					<?php endforeach; ?> 
					 * 
					 */ ?>
				</tbody>
			</table>
		</div>
	</div>
</div>



						