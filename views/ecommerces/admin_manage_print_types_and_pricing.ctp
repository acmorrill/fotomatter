<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('#print_types_list tbody').sortable({
			items: 'tr.photo_print_type_item',
			handle : '.reorder_print_type_grabber',
			update : function(event, ui) {
				var context = this;
				jQuery(context).sortable('disable');
				
				// figure the the new position of the dragged element
				var photo_print_type_id = jQuery(ui.item).attr('photo_print_type_id');
				var newPosition = position_of_element_among_siblings(jQuery('.photo_print_type_item', this), jQuery(ui.item));
				
				jQuery.ajax({
					type: 'post',
					url: '/admin/ecommerces/ajax_set_print_type_order/'+photo_print_type_id+'/'+newPosition+'/',
					data: {},
					success: function(data) {
						if (data.code != 1) {
							// TODO - maybe revert the draggable back to its start position here
						}
					},
					complete: function() {
						jQuery(context).sortable('enable');
					},
					error: function() {
						//console.log ("this is where an error would occure");
					},
					dataType: 'json'
				});
			}
		}).disableSelection();
	});
</script>

<?php echo $this->Session->flash(); ?>
<div class="right">
	<div class="add_gallery_element custom_ui" style="margin: 5px; margin-bottom: 15px;">
		<form action="/admin/ecommerces/add_print_type_and_pricing/" method="get" style="float: right;">
			<input id="add_new_print_type_button" class="add_button ui-button ui-widget ui-state-default ui-corner-all" type="submit" value="Add New Print Type" role="button" aria-disabled="false" />
		</form>
<!--		<form id="reset_printsize_form" action="/admin/ecommerces/reset_print_sizes/" method="get" style="float: right;">
			<input id="reset_printsize_button" class="add_button ui-button ui-widget ui-state-default ui-corner-all" type="submit" value="Restore Defaults" role="button" aria-disabled="false" />
		</form>-->
		<div style="clear: both;"></div>
	</div>
</div>
<div class="clear"></div>
<?php //debug($photo_avail_sizes); ?>
<?php if (!empty($photo_print_types)): ?>
	<div class="table_header">
		<label class="inline"><?php __('Available Print Sizes:'); ?></label> 
	</div>
	<table id="print_types_list" class="list">
		<thead>
			<tr> 
				<th class="first"></th> 
				<th class=""><?php __('Print Type Name'); ?></th> 
				<th class="last"><?php __('Actions'); ?></th>
			</tr> 
		</thead>
		<tbody>
			<?php foreach($photo_print_types as $photo_print_type): ?> 
				<tr class="photo_print_type_item" photo_print_type_id="<?php echo $photo_print_type['PhotoPrintType']['id']; ?>">
					<td class="print_type_id first"><div class="reorder_print_type_grabber reorder_grabber" /> </td> 
					<td style="width: 300px;">
						<?php echo $photo_print_type['PhotoPrintType']['print_name']; ?>
					</td>
					<td>
						<a href="/admin/ecommerces/add_print_type_and_pricing/<?php echo $photo_print_type['PhotoPrintType']['id']; ?>/">Edit</a> 
						<a href="/admin/ecommerces/delete_print_type/<?php echo $photo_print_type['PhotoPrintType']['id']; ?>/">Delete</a>
					</td>
				</tr>
			<?php endforeach; ?> 
		</tbody>
	</table>
<?php else: ?>
	<?php __('You have not added any print types yet.'); ?>
<?php endif; ?>
