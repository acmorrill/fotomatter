<?php //debug($galleries); ?>


<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('.list tbody').sortable(jQuery.extend(verticle_sortable_defaults, {
			items : 'tr',
			handle : '.reorder_page_grabber',
			axis : 'y',
			update : function(event, ui) {
				var context = this;
				jQuery(context).sortable('disable');
				
				// figure the the now position of the dragged element
				var pageId = jQuery(ui.item).attr('page_id');
				var newPosition = ui.item.index() + 1;// TODO - this must always be set - fail otherwise -- not sure if it will be from jquery ui
				
				jQuery.ajax({
					type: 'post',
					url: '/admin/site_pages/ajax_set_page_order/'+pageId+'/'+newPosition+'/',
					data: {},
					success: function(data) {
						if (data.code != 1) {
							// TODO - maybe revert the draggable back to its start position here
						}
					},
					complete: function() {
						jQuery(context).sortable('enable');
					},
					dataType: 'json'
				});
			}
		})).disableSelection();
	});
</script>


<h1><?php __('Pages'); ?></h1>
<?php if (!empty($site_pages)): ?>
	<div class="table_header">
		<label class="inline"><?php __('Pages:'); ?></label> 
	</div>
	<table class="list">
		<thead>
			<tr> 
				<?php /* <?php if ($this->Paginator->sortKey('Photo') == 'Photo.id'): ?> curr <?php echo $sort_dir; ?><?php endif; ?> */ ?>
				<?php /* <?php echo $this->Paginator->sort(__('Photo ID', true), 'Photo.id'); ?> */ ?>
				<th class="first"></th> 
				<th class=""><?php __('Title'); ?></th> 
				<th class=""><?php __('Modified'); ?></th> 
				<th class=""><?php __('Created'); ?></th>
				<th class="last"><?php __('Actions'); ?></th>
			</tr> 
		</thead>
		<tbody>
			<?php foreach($site_pages as $curr_page): ?> 
				<tr page_id="<?php echo $curr_page['SitePage']['id']; ?>">
					<td class="page_id first"><div class="reorder_page_grabber reorder_grabber" /> </td> 
					<td class="page_name "><?php echo $curr_page['SitePage']['title']; ?> </td> 
					<?php 
						if (date("Y", strtotime($curr_page['SitePage']['modified'])) == date('Y')) {
							$modified_format = "F j, g:i A";
						} else {
							$modified_format = "F j Y, g:i A";
						}
						if (date("Y", strtotime($curr_page['SitePage']['created'])) == date('Y')) {
							$created_format = "F j, g:i A";
						} else {
							$created_format = "F j Y, g:i A";
						}
					?>
					<td class="page_modified"><?php echo date($modified_format, strtotime($curr_page['SitePage']['modified'])); ?> </td> 
					<td class="page_created"><?php echo date($created_format, strtotime($curr_page['SitePage']['created'])); ?> </td> 
					<td class="page_action last">
						<a href="/admin/site_pages/edit_page/<?php echo $curr_page['SitePage']['id']; ?>/"><?php __('Edit'); ?></a>
						<a href="/admin/site_pages/configure_page/<?php echo $curr_page['SitePage']['id']; ?>/"><?php __('Configure'); ?></a>
					</td>
				</tr>
			<?php endforeach; ?> 
		</tbody>
	</table>
<?php else: ?>
	<?php __('You do not have any pages yet.'); ?>
<?php endif; ?>

