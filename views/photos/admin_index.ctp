<style type="text/css">
	h1 {
		font-size: 30px;
		margin-bottom: 10px;
	}
</style>



<h1><?php __('Photos'); ?></h1>
<?php if (!empty($data)): ?>
	<div class="table_header">
		<label class="inline"><?php __('Page:'); ?></label> <?php echo $this->Paginator->counter(); ?>
		<div class="right">
			<?php echo $this->Paginator->prev(__('Prev', true), null, null, array('class' => 'disabled')); ?>&nbsp;
			<?php echo $this->Paginator->numbers(array(
				'modulus' => 2,
				'first' => 2,
				'last' => 2
			)); ?>&nbsp;
			<?php echo $this->Paginator->next(__('Next', true), null, null, array('class' => 'disabled')); ?> 
		</div>
	</div>
	<?php $sort_dir = $this->Paginator->sortDir('Photo'); ?>
	<table class="list">
		<tr> 
			<?php /* <?php if ($this->Paginator->sortKey('Photo') == 'Photo.id'): ?> curr <?php echo $sort_dir; ?><?php endif; ?> */ ?>
			<?php /* <?php echo $this->Paginator->sort(__('Photo ID', true), 'Photo.id'); ?> */ ?>
			<th class="first <?php if ($this->Paginator->sortKey('Photo') == 'Photo.id'): ?> curr <?php echo $sort_dir; ?><?php endif; ?>"><?php echo $this->Paginator->sort(__('ID', true), 'Photo.id'); ?></th> 
			<th><?php __('Photo'); ?></th> 
			<th class="<?php if ($this->Paginator->sortKey('Photo') == 'Photo.display_title'): ?> curr <?php echo $sort_dir; ?><?php endif; ?>"><?php echo $this->Paginator->sort(__('Title', true), 'Photo.display_title'); ?></th> 
			<th class="<?php if ($this->Paginator->sortKey('Photo') == 'Photo.enabled'): ?> curr <?php echo $sort_dir; ?><?php endif; ?>"><?php echo $this->Paginator->sort(__('Enabled', true), 'Photo.enabled'); ?></th> 
			<th class="<?php if ($this->Paginator->sortKey('Photo') == 'PhotoFormat.display_name'): ?> curr <?php echo $sort_dir; ?><?php endif; ?>"><?php echo $this->Paginator->sort(__('Format', true), 'PhotoFormat.display_name'); ?></th> 
			<th class="<?php if ($this->Paginator->sortKey('Photo') == 'Photo.modified'): ?> curr <?php echo $sort_dir; ?><?php endif; ?>"><?php echo $this->Paginator->sort(__('Modified', true), 'Photo.modified'); ?></th> 
			<th class="<?php if ($this->Paginator->sortKey('Photo') == 'Photo.created'): ?> curr <?php echo $sort_dir; ?><?php endif; ?>"><?php echo $this->Paginator->sort(__('Created', true), 'Photo.created'); ?></th>
			<th class="last"><?php __('Actions'); ?></th>
		</tr> 
	   <?php foreach($data as $curr_photo): ?> 
			<tr>
				<td class="photo_id first <?php if ($this->Paginator->sortKey('Photo') == 'Photo.id'): ?> curr<?php endif; ?>"><?php echo $curr_photo['Photo']['id']; ?> </td> 
				<?php /* <?php if ($this->Paginator->sortKey('Photo') == 'Photo.id'): ?> curr<?php endif; ?>"><?php echo $curr_photo['Photo']['id']; ?> */ ?>
				<?php $img_path = $this->Photo->get_photo_path($curr_photo['Photo']['id'], 110, 110); ?>
				<td class="photo_image"><img src="<?php echo $img_path; ?>" /><?php //echo $curr_photo['Photo']['id']; ?></td> 
				<td class="photo_title <?php if ($this->Paginator->sortKey('Photo') == 'Photo.display_title'): ?> curr<?php endif; ?>"><?php echo $curr_photo['Photo']['display_title']; ?> </td> 
				<td class="photo_enabled <?php if ($this->Paginator->sortKey('Photo') == 'Photo.enabled'): ?> curr<?php endif; ?>"><?php echo ($curr_photo['Photo']['enabled'] == 0) ? __('NO', true): __('YES', true); ?> </td> 
				<td class="photo_format <?php if ($this->Paginator->sortKey('Photo') == 'PhotoFormat.display_name'): ?> curr<?php endif; ?>"><?php echo $curr_photo['PhotoFormat']['display_name']; ?> </td> 
				<?php 
					if (date("Y", strtotime($curr_photo['Photo']['modified'])) == date('Y')) {
						$modified_format = "F j, g:i A";
					} else {
						$modified_format = "F j Y, g:i A";
					}
					if (date("Y", strtotime($curr_photo['Photo']['created'])) == date('Y')) {
						$created_format = "F j, g:i A";
					} else {
						$created_format = "F j Y, g:i A";
					}
				?>
				
				<?php $created_current_year = date("Y", strtotime($curr_photo['Photo']['created'])) == date('Y'); ?>
				<td class="photo_modified <?php if ($this->Paginator->sortKey('Photo') == 'Photo.modified'): ?> curr<?php endif; ?>"><?php echo date($modified_format, strtotime($curr_photo['Photo']['modified'])); ?> </td> 
				<td class="photo_created <?php if ($this->Paginator->sortKey('Photo') == 'Photo.created'): ?> curr<?php endif; ?>"><?php echo date($created_format, strtotime($curr_photo['Photo']['created'])); ?> </td> 
				<td class="photo_action last">
					<a href="/admin/photos/edit/<?php echo $curr_photo['Photo']['id']; ?>/"><?php __('Edit'); ?></a>
				</td>
			</tr>
		<?php endforeach; ?> 
	</table>
	<?php echo $this->Paginator->prev(__('Prev', true), null, null, array('class' => 'disabled')); ?>&nbsp;
	<?php echo $this->Paginator->numbers(array(
		'modulus' => 2,
		'first' => 2,
		'last' => 2
	)); ?>&nbsp;
	<?php echo $this->Paginator->next(__('Next', true), null, null, array('class' => 'disabled')); ?> 

<?php else: ?>
	<?php __('You do not have any photos yet.'); ?>
<?php endif; ?>

