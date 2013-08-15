<?php //debug($galleries); ?>


<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('.list tbody').sortable(jQuery.extend(verticle_sortable_defaults, {
			items : 'tr',
			handle : '.reorder_gallery_grabber',
			update : function(event, ui) {
				var context = this;
				jQuery(context).sortable('disable');
				
				// figure the the now position of the dragged element
				var photoGalleryId = jQuery(ui.item).attr('gallery_id');
				var newPosition = ui.item.index() + 1;// TODO - this must always be set - fail otherwise -- not sure if it will be from jquery ui
				// DREW TODO - change the above to use - var newPosition = position_of_element_among_siblings(jQuery('.page_element_cont', this), jQuery(ui.item));
				
				jQuery.ajax({
					type: 'post',
					url: '/admin/photo_galleries/ajax_set_photogallery_order/'+photoGalleryId+'/'+newPosition+'/',
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

<div class="right">
	<?php echo $this->Element('admin/gallery/add_gallery'); ?>
</div>
<div class="clear"></div>
<?php if (!empty($galleries)): ?>
	<div class="table_header">
		<label class="inline"><?php __('Gallery:'); ?></label> 
	</div>
	<table class="list">
		<thead>
			<tr> 
				<?php /* <?php if ($this->Paginator->sortKey('Photo') == 'Photo.id'): ?> curr <?php echo $sort_dir; ?><?php endif; ?> */ ?>
				<?php /* <?php echo $this->Paginator->sort(__('Photo ID', true), 'Photo.id'); ?> */ ?>
				<th class="first"></th> 
				<th class=""><?php __('Display Name'); ?></th> 
				<th class=""><?php __('Description'); ?></th> 
				<th class=""><?php __('Modified'); ?></th> 
				<th class=""><?php __('Created'); ?></th>
				<th class="last"><?php __('Actions'); ?></th>
			</tr> 
		</thead>
		<tbody>
			<?php foreach($galleries as $curr_gallery): ?> 
				<tr gallery_id="<?php echo $curr_gallery['PhotoGallery']['id']; ?>">
					<td class="gallery_id first"><div class="reorder_gallery_grabber reorder_grabber" /> </td> 
					<td class="gallery_name "><?php echo $curr_gallery['PhotoGallery']['display_name']; ?> </td> 
					<td class="gallery_description"><?php echo $curr_gallery['PhotoGallery']['description']; ?> </td> 
					<?php $modified_date = $this->Util->get_formatted_created_date($curr_gallery['PhotoGallery']['modified']); ?>
					<?php $created_date = $this->Util->get_formatted_created_date($curr_gallery['PhotoGallery']['created']); ?>
					<td class="gallery_modified"><?php echo $modified_date; ?> </td> 
					<td class="gallery_created"><?php echo $created_date; ?> </td> 
					<td class="gallery_action last">
						<a href="/admin/photo_galleries/edit_gallery/<?php echo $curr_gallery['PhotoGallery']['id']; ?>/"><?php __('Edit'); ?></a>
						<a href="/admin/photo_galleries/edit_gallery_connect_photos/<?php echo $curr_gallery['PhotoGallery']['id']; ?>/"><?php __('Connect'); ?></a>
						<a href="/admin/photo_galleries/edit_gallery_arrange_photos/<?php echo $curr_gallery['PhotoGallery']['id']; ?>/"><?php __('Arrange'); ?></a>
					</td>
				</tr>
			<?php endforeach; ?> 
		</tbody>
	</table>
<?php else: ?>
	<?php __('You do not have any galleries yet.'); ?>
<?php endif; ?>

