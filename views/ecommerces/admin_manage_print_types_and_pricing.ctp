<script src="/js/angular_1.2.22/app/js/app.js"></script>
<script src="/js/angular_1.2.22/app/js/controllers/avail_print_types.js"></script>
<script src="/js/angular_1.2.22/app/js/services/resources.js"></script>
<script src="/js/angular_1.2.22/app/js/services/print_types_service.js"></script>
<script src="/js/angular_1.2.22/app/js/directives.js"></script>

<div ng-app="fotomatterApp" ng-controller="AvailPrintTypesCtrl" ng-model-options="{ debounce: { 'default': 750, 'blur': 0 } }">
	<div class="custom_ui right">
		<a href="/admin/ecommerces/manage_print_sizes">
			<div class="add_button">
				<div class="content"><?php echo __('Manage Default Print Sizes', true); ?></div><div class="right_arrow_lines icon-arrow-01"><div></div></div>
			</div>
		</a>
	</div>
	<?php /*<h1><?php echo __('Add/Edit Print Types', true); ?>
		<div id="help_tour_button" class="custom_ui"><?php //echo $this->Element('/admin/get_help_button'); ?></div>
	</h1>*/ ?>
	<?php /*<p><?php echo __('Create print types, add sizes available to those print types, and set default pricing and shipping. To change pricing from the default structure on one specific photo, go to &ldquo;Pricing Override&rdquo; under the Photos tab.', true); ?></p>*/ ?>

	
	<br /><br /><br />
	<div class="gallery_view ng-hide" ng-show="open_print_type.photo_print_type == false" style="position: relative; min-height: 500px;">
		<div class="empty_help_content" style="display: block;">
			&#9668;&nbsp;<?php echo __('Choose a Print Type at Left', true); ?>
			<br /><span class="help_para"><?php echo __('Create print types, add sizes available to those print types, and set default pricing and shipping. To change pricing from the default structure on one specific photo, go to &ldquo;Pricing Override&rdquo; under the Photos tab.', true); ?></span>
		</div>
	</div>
	
	<div class="ng-hide" ng-show="open_print_type.photo_print_type != false">
		<?php echo $this->Element('admin/ecommerce/angular_add_print_type_and_pricing'); ?>
	</div>
	
	
	<div class="clear"></div>
	<div class="dynamic_list">
		<div id="list_tools">
			<div id="list_tools_inner" class="custom_ui" <?php /*ng-controller="ModalInstanceCtrl"*/ ?>>
				<script type="text/ng-template" id="myModalContent.html">
					<section>
						<div class="fade_background_top"></div>
						<div class="ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix ui-draggable-handle">
							<span id="ui-id-1" class="ui-dialog-title"><?php echo __('Add Print Type', true); ?></span>
							<button type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-icon-only ui-dialog-titlebar-close" role="button" title="Close" ng-click="cancel()">
								<span class="ui-button-icon-primary ui-icon ui-icon-closethick"></span>
								<span class="ui-button-text"><?php echo __('Close', true); ?></span>
							</button>
						</div>

						<div id="error_and_content_cont" class="error_and_content_cont">
							<p ng-show="errorMessage != undefined &amp;&amp; errorMessage != ''" class="warning flashMessage" style="display: none;"><i class="icon-warning-01"></i><span class="ng-binding"></span></p>
							<div class="ui-dialog-content ui-widget-content fotomatter_form short" style="width: auto; min-height: 0px;">
								<?php echo $this->Element('admin/ecommerce/angular_add_print_type', array('print_fulfillers' => $overlord_account_info['print_fulfillers'])); ?>
							</div> 
						</div>

						<div class="ui-dialog-buttonpane ui-widget-content ui-helper-clearfix">
							<div class="ui-dialog-buttonset">
								<button ng-click="cancel()" type="button" class="ui-button ui-widget ui-state-default ui-corner-all ng-scope ui-button-text-only" role="button" aria-disabled="false">
									<span class="ui-button-text"><?php echo __('Cancel' , true); ?></span>
								</button>
								<button ng-click="create_print_type()" type="button" class="ui-button ui-widget ui-state-default ui-corner-all ng-scope ui-button-text-only" role="button" aria-disabled="false">
									<span class="ui-button-text"><?php echo __('Add Print Type', true); ?></span>
								</button>
							</div>
						</div>
					</section>
				</script>
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
					
					<tr ng-repeat="photo_print_type in photo_print_types" class="sortable" ng-class="{'current': open_print_type.photo_print_type.PhotoPrintType.id == photo_print_type.PhotoPrintType.id}" item_id="{{photo_print_type.PhotoPrintType.id}}">
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
													ng-class="{'selected': open_print_type.photo_print_type.PhotoPrintType.id == photo_print_type.PhotoPrintType.id }"
													class="add_button icon" 
													ng-click="editPrintType(photo_print_type)"
												>
													<div class="content icon-cogWheel"></div>
												</div>
												<span>
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
			</table>
		</div>
	</div>
</div>