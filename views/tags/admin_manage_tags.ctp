<h1><?php echo __('Tags', true); ?>
	<div id="help_tour_button" class="custom_ui"><?php echo $this->Element('/admin/get_help_button'); ?></div>
</h1>
<p>
	This is the tags page
</p>


<div class="table_container" ng-controller="TagListCtrl">
	<div class="fade_background_top"></div>
	<div class="table_top"></div>
	<table class="list">
		<thead>
			<tr> 
				<th class="first">
					<div class="content one_line">
						<?php echo __('Tag Name', true); ?>
					</div>
				</th> 
				<th class="last">
					<div class="content one_line">
						<?php echo __('Connected Photos', true); ?>
					</div>
				</th> 
			</tr> 
		</thead>
		<tbody>
			<tr class="spacer"><td colspan="3"></td></tr>

<!--			<tr class="first last">
				<td class="first last" colspan="6">
					<div class="rightborder"></div>
					<span>You don't have any tags yet</span>
				</td>
			</tr>-->

			
			<tr ng-repeat="tag in tags">
				<td class="first">
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
					Actions
				</td>
			</tr>
		</tbody>
	</table>
</div>