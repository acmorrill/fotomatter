<?php //debug($galleries); ?>

<h1><?php echo __('Galleries', true); ?>
	<div id="help_tour_button" class="custom_ui"><?php echo $this->Element('/admin/get_help_button'); ?></div>
</h1>
<p>
	<?php echo __('Add/delete galleries and manage the photos inside your galleries.', true); ?>
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
				var newPosition = position_of_element_among_siblings(jQuery("#photo_gallery_list .ui-sortable tr:not(.spacer)"), jQuery(ui.item));
				
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

<div class="right" data-step="2" data-intro="<?php echo __('To create a new gallery, choose from two options: standard or smart.', true); ?>" data-position="left">
	<?php echo $this->Element('admin/gallery/add_gallery'); ?>
</div>
<div class="clear"></div>

<div id="photo_gallery_list" class="table_container">
	<div class="fade_background_top"></div>
	<div class="table_top"></div>
	<table class="list" data-step="1" data-intro="<?php echo __ ('Here you can view all of the galleries currently created. Edit the titles, manage the photos, or delete the gallery completely.', true); ?>" data-position="top">
		<thead>
			<tr> 
				<?php /* <?php if ($this->Paginator->sortKey('Photo') == 'Photo.id'): ?> curr <?php echo $sort_dir; ?><?php endif; ?> */ ?>
				<?php /* <?php echo $this->Paginator->sort(__('Photo ID', true), 'Photo.id'); ?> */ ?>
				<th class="first">
				</th> 
				<th class="">
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
				<th class="mobile_hide">
					<div class="content one_line">
						<?php echo __('Gallery Type', true); ?>
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
			<tr class="spacer"><td colspan="6"></td></tr>

			<?php if (empty($galleries)): ?>
				<tr class="first last">
					<td class="first last" colspan="6">
						<div class="rightborder"></div>
						<span><?php echo __('You have not added any photo galleries yet.', true); ?></span>
					</td>
				</tr>
			<?php endif; ?>

			<?php $count = 1; $smart_count = 1; $step_count = 4; foreach($galleries as $curr_gallery): ?> 
				<?php 
					$edit_button_help = "";
					$manage_button_help = "";
					$configure_button_help = "";
					$x_button_help = "";
					if ($curr_gallery['PhotoGallery']['type'] == 'smart') {
						if ($smart_count === 1) {
							$configure_button_help = 'data-step="' . $step_count++ . '" data-intro="' . __("Configure the settings for a “Smart” gallery.", true) . '" data-position="left"';
						}
						$smart_count++;
					} else {
						if ($count === 1) {
							$edit_button_help = 'data-step="' . $step_count++ . '" data-intro="' . __("Select “Edit” to modify basic gallery settings.", true) . '" data-position="left"';
							$manage_button_help = 'data-step="' . $step_count++ . '" data-intro="' . __("Manage gallery photos to add/remove photos from a “Standard” gallery.", true) . '" data-position="left"';
							$x_button_help = 'data-step="' . $step_count++ . '" data-intro="' . __("Click on the X to permanently delete a gallery.", true) . '" data-position="left"';
						}
						$count++;
					}
				?>
				<tr gallery_id="<?php echo $curr_gallery['PhotoGallery']['id']; ?>">
					<td class="gallery_id first">
						<div class="rightborder"></div>
						<div class="reorder_gallery_grabber reorder_grabber icon-position-01" />
					</td> 
					<td class="gallery_name ">
						<div class="rightborder"></div>
						<span><?php echo $curr_gallery['PhotoGallery']['display_name']; ?></span>
					</td> 
					<?php /*<td class="gallery_description mobile_hide">
						<div class="rightborder"></div>
						<?php if (empty($curr_gallery['PhotoGallery']['description'])): ?>
							<span>no description yet</span>
						<?php else: ?>
							<span><?php echo $curr_gallery['PhotoGallery']['description']; ?></span>
						<?php endif; ?>
					</td> */ ?>
					<td class="gallery_description mobile_hide">
						<div class="rightborder"></div>
						<span><?php echo ucwords($curr_gallery['PhotoGallery']['type']); ?></span>
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
								<?php /*<a href="/admin/photo_galleries/edit_gallery_arrange_photos/<?php echo $curr_gallery['PhotoGallery']['id']; ?>/">
									<div class="add_button">
										<div class="content"><?php echo __('Arrange', true); ?></div>
										<div class="right_arrow_lines  icon-arrow-01"><div></div></div>
									</div>
								</a>*/ ?>
							<?php endif; ?>
							<a class="delete_link" href="/admin/photo_galleries/delete_gallery/<?php echo $curr_gallery['PhotoGallery']['id']; ?>/"><div class="add_button icon icon_close" <?php echo $x_button_help; ?>><div class="content icon-close-01"></div></div></a>
						</span>
					</td>
				</tr>
			<?php endforeach; ?> 
		</tbody>
	</table>
</div>


<?php /*
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
*/ ?>

						