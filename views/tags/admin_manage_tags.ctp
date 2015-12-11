<script src="/js/angular_1.2.22/app/js/app.js"></script>
<script src="/js/angular_1.2.22/app/js/controllers.js"></script>
<script src="/js/angular_1.2.22/app/js/services.js"></script>
<script src="/js/angular_1.2.22/app/js/directives.js"></script>

<div class='show_on_mobile'>
	<h1><?php echo __('Tags', true); ?></h1>
	<p>
		<?php echo __('This page is only usable on a larger screen.', true); ?>
	</p>
</div>
<div class="hide_on_mobile" id="tag_manager_container" ng-app="fotomatterApp" ng-controller="TagListCtrl">
	<?php echo $this->Element('admin/flashMessage/error', array(
		'angular_code' => 'ng-show="tag_manager_error.length > 0"',
		'message' => '{{tag_manager_error}}'
	)); ?>
	<h1><?php echo __('Tags', true); ?>
		<div id="help_tour_button" class="custom_ui"><?php //echo $this->Element('/admin/get_help_button'); ?></div>
	</h1>
	<p><?php echo __('Create and manage tags connected with your photos to easily upload new photos by tag, using the &ldquo;smart galleries&rdquo; feature (under Galleries tab).', true); ?></p>
	
	<div style="clear: both;"></div>
	<div id="tag_tools_cont">
		<div id="filter_tag_input" class="left">
			<input ng-model="tag_query" type="text" placeholder="<?php echo __('Search Tags', true); ?>" />
		</div>
		<div id="add_tag_input" class="right" data-step="1" data-intro="<?php echo __('Add a new tag to your site.', true); ?>" data-position="left">
			<form ng-submit='add_tag()'>
				<div class="custom_ui">
					<input type='text' ng-model='new_tag' placeholder="<?php echo __('Tag Name', true); ?>" />
					<input ng-show='adding_tag == false' class="add_button" type="submit" value="<?php echo __('Add Tag', true); ?>" />
					<input ng-show='adding_tag == true' class="add_button" type="submit" value="<?php echo __('Adding ...', true); ?>" />
				</div>
			</form>
		</div>
	</div>

	<div style="clear: both;"></div>

	<div class="table_container">
		<div class="fade_background_top"></div>
		<div class="table_top"></div>
		<table class="list">
			<thead>
				<tr> 
					<th ng-class="{ 'first': true, 'curr asc': orderProp == 'Tag.id', 'curr desc': orderProp == '-Tag.id' }" data-step="2" data-intro="<?php echo __('Sort the tags by ID, Tag Name or Connected Photos. Indicated by the blue line and arrow.', true); ?>" data-position="top">
						<div class="content one_line" ng-click="change_sort('Tag.id')">
							<div class="direction_arrow"></div>
							<?php echo __('ID', true); ?>
						</div>
					</th> 
					<th ng-class="{ 'tag_name': true, 'curr asc': orderProp == 'Tag.name', 'curr desc': orderProp == '-Tag.name'}" >
						<div class="content one_line" ng-click="change_sort('Tag.name')">
							<div class="direction_arrow"></div>
							<?php echo __('Tag Name', true); ?>
						</div>
					</th> 
					<th ng-class="{ 'last': true, 'curr asc': orderProp == 'Tag.photos_count', 'curr desc': orderProp == '-Tag.photos_count' }" >
						<div class="content one_line" ng-click="change_sort('Tag.photos_count')">
							<div class="direction_arrow"></div>
							<?php echo __('Connected Photos', true); ?>
						</div>
					</th> 
				</tr> 
			</thead>
			<tbody>
				<tr class="spacer"><td colspan="4"></td></tr>

				<tr class="first last" ng-show="loading == true">
					<td class="first last" colspan="4" style="text-align: center;">
						<div class="rightborder"></div>
						<span>LOADING</span>
					</td>
				</tr>

				<tr class="first last" ng-show="tags.length == 0">
					<td class="first last" colspan="4">
						<div class="rightborder"></div>
						<span>You don't have any tags yet</span>
					</td>
				</tr>

				<tr ng-repeat="tag in tags | filter:tag_query | orderBy:orderProp">
					<td class="first">
						<div class="rightborder"></div>
						<span>{{tag.Tag.id}}</span>
					</td> 
					<td class="tag_name">
						<div class="rightborder"></div>
						<a onbeforesave="edit_tag($data, tag.Tag.id)" href="#" editable-text="tag.Tag.name">{{ tag.Tag.name || "empty" }}</a>
					</td> 
					<td>
						<div class="rightborder"></div>
						<span>{{tag.Tag.photos_count}}</span>
					</td> 
					<td class='last table_actions'>
						<span class="custom_ui" ng-click="delete_tag(tag.Tag.id)" confirm-delete confirm-message="Do you really want to delete the tag?" confirm-title="Really delete tag?" confirm-button-title="Delete" >
							<div class="add_button icon icon_close"><div class="content icon-close-01"></div></div>
						</span>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>