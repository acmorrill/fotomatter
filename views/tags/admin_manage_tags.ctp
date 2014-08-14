<h1><?php echo __('Tags', true); ?>
	<div id="help_tour_button" class="custom_ui"><?php echo $this->Element('/admin/get_help_button'); ?></div>
</h1>
<p>
	This is the tags page
</p>

<div ng-controller="TagListCtrl">
	<div class="custom_ui">
		<input type='text' ng-model='new_tag' placeholder="new tag name" />
		<div ng-click='add_tag()' class="add_button">
			<div ng-show='adding_tag == false' class="content"><?php echo __('Add Tag', true); ?></div>
			<div ng-show='adding_tag == true' class="content"><?php echo __('Adding ...', true); ?></div>
		</div>
	</div>


	<div class="table_container">
		<div class="fade_background_top"></div>
		<div class="table_top"></div>
		<table class="list">
			<thead>
				<tr> 
					<th ng-class="{ 'first': true, 'curr asc': orderProp == 'Tag.weight', 'curr desc': orderProp == '-Tag.weight' }" >
						<div class="content one_line" ng-click="change_sort('Tag.weight')">
							<div class="direction_arrow"></div>
							<?php echo __('Add Order', true); ?>
						</div>
					</th> 
					<th ng-class="{ 'curr asc': orderProp == 'Tag.name', 'curr desc': orderProp == '-Tag.name'}" >
						<div class="content one_line" ng-click="change_sort('Tag.name')">
							<div class="direction_arrow"></div>
							<?php echo __('Tag Name', true); ?>
						</div>
					</th> 
					<th ng-class="{ 'last': true, 'curr asc': orderProp == 'Photo.length', 'curr desc': orderProp == '-Photo.length' }" >
						<div class="content one_line" ng-click="change_sort('Photo.length')">
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
						<span>Loading ...</span>
					</td>
				</tr>

				<tr class="first last" ng-show="tags.length == 0">
					<td class="first last" colspan="4">
						<div class="rightborder"></div>
						<span>You don't have any tags yet</span>
					</td>
				</tr>

				<tr ng-repeat="tag in tags | orderBy:orderProp">
					<td class="first">
						<div class="rightborder"></div>
						<span>{{tag.Tag.weight}}</span>
					</td> 
					<td>
						<div class="rightborder"></div>
						<span>{{tag.Tag.name}}</span>
					</td> 
					<td>
						<div class="rightborder"></div>
						<span>{{tag.Photo.length}}</span>
					</td> 
					<td class='last'>
						<span class="custom_ui" ng-click="delete_tag($index)">
							<div class="add_button icon"><div class="content">X</div></div>
						</span>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>