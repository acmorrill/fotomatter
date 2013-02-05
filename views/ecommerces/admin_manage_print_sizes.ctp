<script type="text/javascript">
	jQuery(document).ready(function() {
		console.log ("document loaded");
	});
</script>

<?php echo $this->Session->flash(); ?>
<div class="right">
	<?php echo $this->Element('admin/gallery/add_gallery'); ?>
</div>
<div class="clear"></div>
<?php //debug($photo_avail_sizes); ?>
<?php if (!empty($photo_avail_sizes)): ?>
	<div class="table_header">
		<label class="inline"><?php __('Available Print Sizes:'); ?></label> 
	</div>
	<table class="list">
		<thead>
			<tr> 
				<th class="first"><?php __('Dimension Size'); ?></th> 
				<th class=""><?php __('Format(s)'); ?></th> 
				<th class="last"><?php __('Actions'); ?></th>
			</tr> 
		</thead>
		<tbody>
			<?php foreach($photo_avail_sizes as $photo_avail_size): ?> 
				<tr photo_avail_size_id="<?php echo $photo_avail_size['PhotoAvailSize']['id']; ?>">
					<td style="width: 100px;"><?php echo $photo_avail_size['PhotoAvailSize']['short_side_length']; ?> x --</td>
					<td style="width: 150px;">
						<?php foreach($photo_avail_size['PhotoFormat'] as $format): ?>
							<?php echo $format['display_name']; ?>
						<?php endforeach; ?>
					</td>
					<td>edit delete</td>
				</tr>
			<?php endforeach; ?> 
		</tbody>
	</table>
<?php else: ?>
	<?php __('You have not added any sizes yet.'); ?>
<?php endif; ?>
