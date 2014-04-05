<?php //debug($galleries); ?>

<?php echo $this->Session->flash(); ?>
<h1><?php echo __('Galleries', true); ?>
	<div id="help_tour_button" class="custom_ui"><?php echo $this->Element('/admin/get_help_button'); ?></div>
</h1>
<p>
	What is this page anyhow?
</p>
<div style="clear: both;"></div>
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

<div class="right" data-step="2" data-intro="<?php echo __('CONTENT HERE', true); ?>" data-position="left">
	<?php echo $this->Element('admin/gallery/add_gallery'); ?>
</div>
<div class="clear"></div>
<?php if (!empty($galleries)): ?>
	<div class="table_container" data-step="1" data-intro="<?php echo __('CONTENT HERE', true); ?>" data-position="bottom">
		<div class="fade_background_top"></div>
		<div class="table_top"></div>
		<table class="list">
			<thead>
				<tr data-step="3" data-intro="<?php echo __('CONTENT HERE', true); ?>" data-position="bottom"> 
					<?php /* <?php if ($this->Paginator->sortKey('Photo') == 'Photo.id'): ?> curr <?php echo $sort_dir; ?><?php endif; ?> */ ?>
					<?php /* <?php echo $this->Paginator->sort(__('Photo ID', true), 'Photo.id'); ?> */ ?>
					<th class="first">
					</th> 
					<th class="">
						<div class="content one_line">
							<?php echo __('Display Name', true); ?>
						</div>
					</th> 
					<th class="">
						<div class="content one_line">
							<?php echo __('Description', true); ?>
						</div>
					</th> 
					<th class="">
						<div class="content one_line">
							<?php echo __('Modified', true); ?>
						</div>
					</th> 
					<th class="">
						<div class="content one_line">
							<?php echo __('Created', true); ?>
						</div>
					</th>
					<th class="last">
						<div class="content one_line">
							<?php echo __('Actions', true); ?>
						</div>
					</th>
				</tr> 
			</thead>
			<tbody>
				<tr class="spacer"><td colspan="3"></td></tr>
				<?php $count = 0; foreach($galleries as $curr_gallery): ?> 
					
					<?php
						$gallery_name_help_code = '';
						$reoder_help_code = '';
						$edit_help_code = '';
						$connect_help_code = '';
						$arrange_help_code = '';
						if ($count === 0) {
							$gallery_name_help_code = 'data-step="4" data-intro="'.__('CONTENT HERE', true).'" data-position="left"';
							$reoder_help_code = 'data-step="5" data-intro="'.__('CONTENT HERE', true).'" data-position="left"';
							$edit_help_code = 'data-step="6" data-intro="'.__('CONTENT HERE', true).'" data-position="left"';
							$connect_help_code = 'data-step="7" data-intro="'.__('CONTENT HERE', true).'" data-position="left"';
							$arrange_help_code = 'data-step="8" data-intro="'.__('CONTENT HERE', true).'" data-position="left"';
						}					
					?>
				
					<tr gallery_id="<?php echo $curr_gallery['PhotoGallery']['id']; ?>" data-step="4" data-intro="<?php echo __('CONTENT HERE', true); ?>" data-position="bottom">
						<td class="gallery_id first">
							<div class="rightborder"></div>
							<div class="reorder_gallery_grabber reorder_grabber" data-step="5" data-intro="<?php echo __('CONTENT HERE', true); ?>" data-position="bottom"/>
						</td> 
						<td class="gallery_name ">
							<div class="rightborder"></div>
							<span><?php echo $curr_gallery['PhotoGallery']['display_name']; ?></span>
						</td> 
						<td class="gallery_description">
							<div class="rightborder"></div>
							<span><?php echo $curr_gallery['PhotoGallery']['description']; ?></span>
						</td> 
						<?php $modified_date = $this->Util->get_formatted_created_date($curr_gallery['PhotoGallery']['modified']); ?>
						<?php $created_date = $this->Util->get_formatted_created_date($curr_gallery['PhotoGallery']['created']); ?>
						<td class="gallery_modified">
							<div class="rightborder"></div>
							<span><?php echo $modified_date; ?></span>
						</td> 
						<td class="gallery_created">
							<div class="rightborder"></div>
							<span><?php echo $created_date; ?></span>
						</td> 
						<td class="gallery_action last">
							<a href="/admin/photo_galleries/edit_gallery/<?php echo $curr_gallery['PhotoGallery']['id']; ?>/"data-step="6" data-intro="<?php echo __('CONTENT HERE', true); ?>" data-position="bottom"><?php __('Edit'); ?></a>
							<a href="/admin/photo_galleries/edit_gallery_connect_photos/<?php echo $curr_gallery['PhotoGallery']['id']; ?>/" data-step="7" data-intro="<?php echo __('CONTENT HERE', true); ?>" data-position="bottom"><?php __('Connect'); ?></a>
							<a href="/admin/photo_galleries/edit_gallery_arrange_photos/<?php echo $curr_gallery['PhotoGallery']['id']; ?>/" data-step="8" data-intro="<?php echo __('CONTENT HERE', true); ?>" data-position="bottom"><?php __('Arrange'); ?></a>
						</td>
					</tr>
				<?php $count ++; endforeach; ?> 
			</tbody>
		</table>
	</div>
<?php else: ?>
	<h1><?php __('You do not have any galleries yet.'); ?></h1>
<?php endif; ?>



<?php ob_start(); ?>
<ol>
	<li>This page lists all the galleries you currently have - and also lets you reorder them.</li>
	<li>Things to remember
		<ol>
			<li>This page needs a flash message</li>
			<li>design for currently sorting column</li>
			<li>design for sorting direction</li>
			<li>design add standard and smart gallery button</li>
			<li>Don't forget the edit, connect, arrange, and smart gallery settings pages</li>
			<li>Smart galleries don't have the "connect" and "arrange" links - just a Smart Gallery Settings link</li>
			<li>We probobly want to add a gallery type to the table (smart or standard) - also, maybe smart gallery should be styled a little different in the list?</li>
			<li>We don't necessarily need modified, created etc</li>
		</ol>
	</li>
</ol>
<?php
$html = ob_get_contents();
ob_end_clean();
	echo $this->Element('admin/richard_notes', array(
	'html' => $html
)); ?>