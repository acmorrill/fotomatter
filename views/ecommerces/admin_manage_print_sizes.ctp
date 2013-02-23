<script type="text/javascript">
	jQuery(document).ready(function() {
		console.log ("document loaded");
		jQuery('#reset_printsize_button').click(function(e) {
			e.preventDefault();
			
			jQuery.foto('confirm', {
				message: 'Are you sure you want to reset the available print sizes?',
				onConfirm: function() {
					jQuery('#reset_printsize_form').submit();
				}
			});
		});
	});
</script>

<?php echo $this->Session->flash(); ?>
<div class="right">
	<div class="add_gallery_element custom_ui" style="margin: 5px; margin-bottom: 15px;">
		<form action="/admin/ecommerces/add_print_size/" method="get" style="float: right;">
			<input id="add_new_printsize_button" class="add_button ui-button ui-widget ui-state-default ui-corner-all" type="submit" value="Add New Print Size" role="button" aria-disabled="false" />
		</form>
		<form id="reset_printsize_form" action="/admin/ecommerces/reset_print_sizes/" method="get" style="float: right;">
			<input id="reset_printsize_button" class="add_button ui-button ui-widget ui-state-default ui-corner-all" type="submit" value="Restore Defaults" role="button" aria-disabled="false" />
		</form>
		<div style="clear: both;"></div>
	</div>
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
				<th class="first"><?php __('Dimension'); ?></th> 
				<th class=""><?php __('Format(s)'); ?></th> 
				<th class="last"><?php __('Actions'); ?></th>
			</tr> 
		</thead>
		<tbody>
			<?php foreach($photo_avail_sizes as $photo_avail_size): ?> 
				<tr photo_avail_size_id="<?php echo $photo_avail_size['PhotoAvailSize']['id']; ?>">
					<td style="width: 100px;"><?php echo $photo_avail_size['PhotoAvailSize']['short_side_length']; ?> x --</td>
					<td style="width: 300px;">
						<?php $formats = Set::extract('/PhotoFormat/display_name', $photo_avail_size); ?>
						<?php echo implode(' | ', $formats) ?>
					</td>
					<td>
						<a href="/admin/ecommerces/add_print_size/<?php echo $photo_avail_size['PhotoAvailSize']['id']; ?>/">Edit</a> 
						<a href="/admin/ecommerces/delete_print_size/<?php echo $photo_avail_size['PhotoAvailSize']['id']; ?>/">Delete</a>
					</td>
				</tr>
			<?php endforeach; ?> 
		</tbody>
	</table>
<?php else: ?>
	<?php __('You have not added any sizes yet.'); ?>
<?php endif; ?>
