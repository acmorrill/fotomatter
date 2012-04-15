<?php //debug($galleries); ?>

<style type="text/css">
	h1 {
		font-size: 30px;
		margin-bottom: 10px;
	}
</style>

<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('.list tbody').sortable({
			items : 'tr',
			handle : '.reorder_gallery_grabber',
			axis : 'y',
			update : function(event, ui) {
				var context = this;
				jQuery(context).sortable('disable');
				
				// figure the the now position of the dragged element
				var photoGalleryId = jQuery(ui.item).attr('gallery_id');
				var newPosition = ui.item.index() + 1;// TODO - this must always be set - fail otherwise -- not sure if it will be from jquery ui
				
				jQuery.post('/admin/photo_galleries/ajax_set_photogallery_order/'+photoGalleryId+'/'+newPosition+'/', function(data) {
					if (data.code != 1) {
						// TODO - maybe revert the draggable back to its start position here
					}
					jQuery(context).sortable('enable');
				}, 'json');
			}
		}).disableSelection();
	});
</script>


<h1><?php __('Galleries'); ?></h1>
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
				<th class=""><?php __('display_name'); ?></th> 
				<th class=""><?php __('description'); ?></th> 
				<th class=""><?php __('Modified'); ?></th> 
				<th class=""><?php __('Created'); ?></th>
				<th class="last"><?php __('Actions'); ?></th>
			</tr> 
		</thead>
		<tbody>
			<?php foreach($galleries as $curr_gallery): ?> 
				<tr gallery_id="<?php echo $curr_gallery['PhotoGallery']['id']; ?>">
					<td class="gallery_id first"><img class="reorder_gallery_grabber" src="/img/admin/icons/green_move_arrow.png" alt="reorder galleries" /> </td> 
					<td class="gallery_name "><?php echo $curr_gallery['PhotoGallery']['display_name']; ?> </td> 
					<td class="gallery_description"><?php echo $curr_gallery['PhotoGallery']['description']; ?> </td> 
					<?php 
						if (date("Y", strtotime($curr_gallery['PhotoGallery']['modified'])) == date('Y')) {
							$modified_format = "F j, g:i A";
						} else {
							$modified_format = "F j Y, g:i A";
						}
						if (date("Y", strtotime($curr_gallery['PhotoGallery']['created'])) == date('Y')) {
							$created_format = "F j, g:i A";
						} else {
							$created_format = "F j Y, g:i A";
						}
					?>

					<td class="gallery_modified"><?php echo date($modified_format, strtotime($curr_gallery['PhotoGallery']['modified'])); ?> </td> 
					<td class="gallery_created"><?php echo date($created_format, strtotime($curr_gallery['PhotoGallery']['created'])); ?> </td> 
					<td class="gallery_action last"><a href="/admin/photo_galleries/edit_gallery/<?php echo $curr_gallery['PhotoGallery']['id']; ?>/"><?php __('Edit'); ?></a></td>
				</tr>
			<?php endforeach; ?> 
		</tbody>
	</table>
<?php else: ?>
	<?php __('You do not have any galleries yet.'); ?>
<?php endif; ?>

