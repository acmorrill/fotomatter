<script src="/js/angular_1.2.22/app/js/app.js"></script>
<script src="/js/angular_1.2.22/app/js/controllers/avail_print_types.js"></script>
<script src="/js/angular_1.2.22/app/js/services.js"></script>
<script src="/js/angular_1.2.22/app/js/directives.js"></script>

<div ng-app="fotomatterApp" ng-controller="AvailPrintTypesCtrl" ng-model-options="{ debounce: { 'default': 750, 'blur': 0 } }">
	<h1><?php echo __('Add/Edit Print Types', true); ?>
		<div id="help_tour_button" class="custom_ui"><?php //echo $this->Element('/admin/get_help_button'); ?></div>
		<div class="custom_ui right">
			<a href="/admin/ecommerces/manage_print_sizes">
				<div class="add_button">
					<div class="content"><?php echo __('Manage Default Print Sizes', true); ?></div><div class="right_arrow_lines icon-arrow-01"><div></div></div>
				</div>
			</a>
		</div>
	</h1>
	<p><?php echo __('Create print types, add sizes available to those print types, and set default pricing and shipping. To change pricing from the default structure on one specific photo, go to &ldquo;Pricing Override&rdquo; under the Photos tab.', true); ?></p>

	
	<br /><br /><br />
	<div class="ng-hide" ng-show="open_print_type != undefined">
		<?php echo $this->Element('admin/ecommerce/angular_add_print_type_and_pricing'); ?>
	</div>
	
	
	<div class="clear"></div>
	<div class="dynamic_list">
		<div id="list_tools">
			<div id="list_tools_inner" class="custom_ui" ng-controller="ModalInstanceCtrl">
				<script type="text/ng-template" id="myModalContent.html">
					<div class="modal-header">
						<h3 class="modal-title">I'm a modal!</h3>
					</div>
					<div class="modal-body">
						<ul>
							<li ng-repeat="item in items">
								<a href="#" ng-click="$event.preventDefault(); selected.item = item">{{ item }}</a>
							</li>
						</ul>
						Selected: <b>{{ selected.item }}</b>
					</div>
					<div class="modal-footer">
						<button class="btn btn-primary" type="button" ng-click="ok()">OK</button>
						<button class="btn btn-warning" type="button" ng-click="cancel()">Cancel</button>
					</div>
				</script>
				<?php echo $this->Element('admin/ecommerce/angular_add_print_type', array('print_fulfillers' => $overlord_account_info['print_fulfillers'])); ?>
				<div class="add_button print_type" ng-click="addNewPrintType()">
					<div class="content"><?php echo __('Add Print Type', true); ?></div>
					<div class="plus_icon_lines icon-_button-01"><div class="one"></div><div class="two"></div></div>
				</div>
				<div style="clear: both;"></div>
			</div>
		</div>
		<div class="table_container" data-step="1" data-intro="<?php echo __('This area shows all the print types that have been created.', true); ?>" data-position="top">
			<div class="fade_background_top"></div>
			<div class="table_top"></div>
			<table id="print_types_list" class="list" ui-sortable="printTypeSortableOptions">
				<tbody>
					<tr class="spacer"><td colspan="1"></td></tr>
					<tr class="first last ng-hide" ng-show="photo_print_types == undefined">
						<td class="first last" colspan="1" style="text-align: center;">
							<span>LOADING</span>
						</td>
					</tr>

					<tr class="first last ng-hide" ng-show="photo_print_types.length == 0">
						<td class="first last" colspan="1">
							<span>You don't have any print types</span>
						</td>
					</tr>
					
					<tr ng-repeat="photo_print_type in photo_print_types" class="sortable" <?php /* ng-class="{'current': last_open_gallery_id == photo_gallery.PhotoGallery.id}" */ ?> item_id="{{photo_print_type.PhotoPrintType.id}}">
						<td class="gallery_name gallery_id first last">
							<table>
								<tbody>
									<tr>
										<td class="first">
											<div class="reorder_grabber icon-position-01" />
										</td>
										<td class="last">
											<span ng-click="editPrintType(photo_print_type)">{{photo_print_type.PhotoPrintType.print_name}}</span>
										</td>
									</tr>
									<tr>
										<td colspan="2">
											<span class="custom_ui">
												<div 
													<?php /*ng-class="{'selected': last_open_gallery_id == photo_gallery.PhotoGallery.id && (upload_to_gallery == null || upload_to_gallery == 'empty'), 'disabled': uploading_photos == true}" */ ?>
													class="add_button icon" 
													ng-click="editPrintType(photo_print_type)"
													<?php /*ng-click="view_gallery(photo_gallery.PhotoGallery.id, 0, photo_gallery.PhotoGallery.type)" */ ?>
												>
													<div class="content icon-cogWheel" <?php /*ng-show="photo_gallery.PhotoGallery.type == 'standard'"*/ ?>></div>
												</div>
												<span <?php /*ng-class="{'disabled': uploading_photos == true}"*/ ?>>
													<span 
														ng-click="deletePrintType(photo_print_type)"
														confirm-delete confirm-message="Do you really want to delete the print type?" 
														confirm-title="Really delete print type?" 
														confirm-button-title="Delete"
													>
														<div class="add_button icon icon_close"><div class="content icon-close-01"></div></div>
													</span>
												</span>
											</span>
										</td>
									</tr>
								</tbody>
							</table>
						</td>
					</tr>
				</tbody>
				
				
				<?php /*
				<tbody>
					<tr class="spacer"><td colspan="3"></td></tr>

					<?php if (empty($photo_print_types)): ?>
						<tr class="first last">
							<td class="first last" colspan="3">
								<div class="rightborder"></div>
								<span><?php echo __('You have not added any print types yet.', true); ?></span>
							</td>
						</tr>
					<?php endif; ?>
					<?php // KENT TODO - fix the below as they are in a foreach ?>
					<?php foreach($photo_print_types as $photo_print_type): ?> 
						<tr class="photo_print_type_item" photo_print_type_id=" <?php echo $photo_print_type['PhotoPrintType']['id']; ?>">
							<td class="print_type_id first table_width_reorder_icon"><div class="reorder_print_type_grabber reorder_grabber icon-position-01"/> </td> 
							<td class="print_type">
								<div class="rightborder"></div>
								<span><?php echo $photo_print_type['PhotoPrintType']['print_name']; ?></span>
							</td>
							<td class="table_actions last">
								<span class="custom_ui">
										<a href="/admin/ecommerces/add_print_type_and_pricing/<?php echo $photo_print_type['PhotoPrintType']['id']; ?>/"><div class="add_button"><div class="content"><?php echo __('Edit', true); ?></div><div class="right_arrow_lines icon-arrow-01"><div></div></div></div></a>
									<a class="delete_link" href="/admin/ecommerces/delete_print_type/<?php echo $photo_print_type['PhotoPrintType']['id']; ?>/"><div class="add_button icon icon_close"><div class="content icon-close-01"></div></div></a>
								</span>
							</td>
						</tr>
					<?php endforeach; ?> 
				</tbody>
				*/ ?>
				
				
			</table>
		</div>
	</div>
</div>