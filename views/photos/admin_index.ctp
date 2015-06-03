<h1><?php echo __('Photos', true); ?>
	<?php if (!empty($current_on_off_features['unlimited_photos'])): ?>
		(<?php echo $total_photos; ?>)
	<?php else: ?>
		(<?php echo $total_photos; ?>/<?php echo LIMIT_MAX_FREE_PHOTOS; ?>)
	<?php endif; ?>
	<?php echo $this->Element('/admin/get_help_button'); ?>
</h1>
<p>
	<?php echo sprintf(__('Easily upload, view, or delete your photos. You may also edit the description and pricing information for your images. The recommended .jpg size is 4000px or less on the long side. Add up to %s photos for free. To add unlimited photos go to Manage Features at the top.', true), LIMIT_MAX_FREE_PHOTOS); ?>
</p>

<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('#upload_photos').click(function() {
			jQuery(this).closest('form').submit();
		});
	});
</script>

<div class="right">
	<div class="add_gallery_element custom_ui" style="margin: 5px; margin-bottom: 15px;">
		<form action="/admin/photos/mass_upload/" method="get" style="float: right;">
			<div id="upload_photos" class="add_button minor_highlight" type="submit" data-step="1" data-intro="<?php echo __('Start by uploading your photos. The recommended image size is 4000px or less on the long side.', true); ?>" data-position="left"><div class="content"><?php echo __('Upload Photos', true); ?></div>
				<div class="plus_icon_lines icon-_button-01"><div class="one"></div><div class="two"></div></div>
			</div>
		</form>
		<div style="clear: both;"></div>
	</div>
</div>

<div class="clear"></div>

<div class="table_container" data-step="2" data-intro="<?php echo __('This section displays all of your uploaded photos.', true); ?>" data-position="top">
	<div class="fade_background_top"></div>
	<div class="table_top"></div>
	<?php $sort_dir = $this->Paginator->sortDir('Photo'); ?>
	<table class="list">
		<thead>
			<tr> 
				<?php /* <?php if ($this->Paginator->sortKey('Photo') == 'Photo.id'): ?> curr <?php echo $sort_dir; ?><?php endif; ?> */ ?>
				<?php /* <?php echo $this->Paginator->sort(__('Photo ID', true), 'Photo.id'); ?> */ ?>
				<th class="first <?php if ($this->Paginator->sortKey('Photo') == 'Photo.id'): ?> curr <?php echo $sort_dir; ?><?php endif; ?>">
					<div class="content one_line">
						<div class="direction_arrow"></div>
						<?php echo $this->Paginator->sort(__('ID', true), 'Photo.id'); ?>
					</div>
				</th> 
				<th>
					<div class="content one_line">
						<div class="direction_arrow"></div>
						<?php echo  __('Photo', true); ?>
					</div>
				</th> 
				<th class="<?php if ($this->Paginator->sortKey('Photo') == 'Photo.display_title'): ?> curr <?php echo $sort_dir; ?><?php endif; ?>">
					<div class="content one_line">
						<div class="direction_arrow"></div>
						<?php echo $this->Paginator->sort(__('Title', true), 'Photo.display_title'); ?>
					</div>
				</th>
				<th class="<?php if ($this->Paginator->sortKey('Photo') == 'Photo.enabled'): ?> curr <?php echo $sort_dir; ?><?php endif; ?>">
					<div class="content one_line" data-step="3" data-intro="<?php echo __('Enabled means the photo is live on your site.', true); ?>" data-position="top">
						<div class="direction_arrow"></div>
						<?php echo $this->Paginator->sort(__('Enabled', true), 'Photo.enabled'); ?>
					</div>
				</th> 
				<th class="<?php if ($this->Paginator->sortKey('Photo') == 'PhotoFormat.display_name'): ?> curr <?php echo $sort_dir; ?><?php endif; ?>">
					<div class="content one_line" data-step="4" data-intro="<?php echo __('Click on Format to view your photos grouped by their assigned orientation (when you edit Photo Details): landscape, portrait, square, panoramic, etc.', true); ?>" data-position="top">
						<div class="direction_arrow"></div>
						<?php echo $this->Paginator->sort(__('Format', true), 'PhotoFormat.display_name'); ?>
					</div>
				</th> 
				<th class="last">
				</th>
			</tr> 
		</thead>
		<tbody>
			<tr class="spacer"><td colspan="3"></td></tr>

			<?php if (empty($data)): ?>
				<tr class="first last">
					<td class="first last" colspan="8">
						<div class="rightborder"></div>
						<span>You have not added photos yet.</span>
					</td>
				</tr>
			<?php endif; ?>

			<?php $count = 1; foreach($data as $curr_photo): ?> 
				<?php 
					$photo_is_enabled = true;
					if (!empty($max_photo_id) && $curr_photo['Photo']['id'] <= $max_photo_id) {
						$photo_is_enabled = !empty($max_photo_id) && $curr_photo['Photo']['id'] <= $max_photo_id;
					}
					$edit_button_help = "";
					$x_button_help = "";
					if ($count === 1) {
						$edit_button_help = 'data-step="5" data-intro="' . __("Select “Edit” to add image titles, descriptions, and pricing (if you have the e-commerce feature).", true) . '" data-position="left"';
						$x_button_help = 'data-step="6" data-intro="' . __("Click on the X to permanently delete a photo.", true) . '" data-position="left"';
					}
				?>
				<tr>
					<td class="photo_id first <?php if ($this->Paginator->sortKey('Photo') == 'Photo.id'): ?> curr<?php endif; ?>">
						<div class="rightborder"></div>
						<span class=" <?php if (!$photo_is_enabled):?>disabled<?php endif; ?>"><?php echo $curr_photo['Photo']['id']; ?></span>
					</td> 
					<?php /* <?php if ($this->Paginator->sortKey('Photo') == 'Photo.id'): ?> curr<?php endif; ?>"><?php echo $curr_photo['Photo']['id']; ?> */ ?>
					<?php $img_path = $this->Photo->get_photo_path($curr_photo['Photo']['id'], 60, 60); ?>
					<td class="photo_image">
						<div class="rightborder"></div>
						<span class="photo_span <?php if (!$photo_is_enabled):?>disabled<?php endif; ?>"><img src="<?php echo $img_path; ?>" alt="" /><?php //echo $curr_photo['Photo']['id']; ?></span>
					</td> 
					<td class="photo_title <?php if ($this->Paginator->sortKey('Photo') == 'Photo.display_title'): ?> curr<?php endif; ?>">
						<div class="rightborder"></div>
						<span class=" <?php if (!$photo_is_enabled):?>disabled<?php endif; ?>"><?php echo $curr_photo['Photo']['display_title']; ?></span>
					</td>
					<td class="photo_enabled <?php if ($this->Paginator->sortKey('Photo') == 'Photo.enabled'): ?> curr<?php endif; ?>">
						<div class="rightborder"></div>
						<span class=" <?php if (!$photo_is_enabled):?>disabled<?php endif; ?>"><?php echo ($curr_photo['Photo']['enabled'] == 0) ? __('NO', true): __('YES', true); ?></span>
					</td> 
					<td class="photo_format <?php if ($this->Paginator->sortKey('Photo') == 'PhotoFormat.display_name'): ?> curr<?php endif; ?>">
						<div class="rightborder"></div>
						<span class=" <?php if (!$photo_is_enabled):?>disabled<?php endif; ?>"><?php echo $curr_photo['PhotoFormat']['display_name']; ?></span>
					</td> 
					<td class="photo_action last table_actions">
						<div class="rightborder"></div>
						<span class="custom_ui">
							<?php if ($photo_is_enabled): ?>
								<a href="/admin/photos/edit/<?php echo $curr_photo['Photo']['id']; ?>/"><div class="add_button" <?php echo $edit_button_help; ?>><div class="content"><?php __('Edit'); ?></div><div class="right_arrow_lines icon-arrow-01"><div></div></div></div></a>
							<?php endif;?>
							<a class="delete_link" href="/admin/photos/delete_photo/<?php echo $curr_photo['Photo']['id']; ?>/"><div class="add_button icon icon_close" <?php echo $x_button_help; ?>><div class="content icon-close-01"></div></div></a>
						</span>
					</td>
				</tr>
			<?php $count++; endforeach; ?> 
		</tbody>
		<?php if (!empty($data)): ?>
			<tfoot>
				<tr>
					<td colspan="8">
						<?php echo $this->Paginator->prev(__('Previous', true), null, null, array('class' => 'disabled')); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<?php echo $this->Paginator->numbers(array(
							'modulus' => 2,
							'first' => 2,
							'last' => 2,
	//							'before' => '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
	//							'after' => '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
							'separator' => '<div class="paginator_divider"></div>',
						)); ?>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->Paginator->next(__('Next', true), null, null, array('class' => 'disabled')); ?> 
					</td>
				</tr>
			</tfoot>
		<?php endif; ?>
	</table>
</div>



<?php ob_start(); ?>
<ol>
	<li>This page is where you see all the photos currently uploaded to your site</li>
	<li>Things to remember
		<ol>
			<li>We need both an edit and delete action (need to add the delete)</li>
			<li>We need the pagination, sorting etc styled</li>
			<li>We don't necessarily have to have all the columns</li>
			<li>This page needs a flash message</li>
			<li>Don't forget the photo edit page and add pages :)</li>
			<li>The add photos link shouldn't be in the menu - its needs to be a prominent button somewhere (possible the same button is also on the dashboard if no images have been uploaded)</li>
		</ol>
	</li>
</ol>
<?php
$html = ob_get_contents();
ob_end_clean();
	echo $this->Element('admin/richard_notes', array(
	'html' => $html
)); ?>