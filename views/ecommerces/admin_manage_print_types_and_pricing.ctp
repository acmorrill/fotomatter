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
							// DREW TODO - maybe revert the draggable back to its start position here
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

<h1>Available Print Sizes
	<?php echo $this->Element('/admin/get_help_button'); ?>
</h1>
<p>
	Instructions for receiving payment go here so all that mumbo jumbo on the screenshot below the Paypal button will go here. Cool beans? Cool.
IPlaceholder info for getting pade. Instructions will go here. Trty and keep it to two lines. But if more, that’s fine. Instructions for receiving payment go here so all that mumbo jumbo on the screenshot below the Paypal button will go here. Cool beans? Cool.
</p>
<div class="right">
	<div class="add_gallery_element custom_ui" style="margin: 5px; margin-bottom: 15px;">
		<script type="text/javascript">
			jQuery(document).ready(function() {
				jQuery('#add_new_print_type_button').click(function() {
					jQuery(this).closest('form').submit();
				});
			});
		</script>
		<form action="/admin/ecommerces/add_print_type_and_pricing/" method="get" style="float: right;">
			<div id="add_new_print_type_button" class="add_button" data-step="2" data-intro="<?php echo __('Click here to create another print type.', true); ?>" data-position="bottom"><div class="content"><?php echo __('Add New Print Type', true); ?></div>
				<div class="plus_icon_lines"><div class="one"></div><div class="two"></div></div>
			</div>
		</form>
<!--		<form id="reset_printsize_form" action="/admin/ecommerces/reset_print_sizes/" method="get" style="float: right;">
			<input id="reset_printsize_button" class="add_button ui-button ui-widget ui-state-default ui-corner-all" type="submit" value="Restore Defaults" role="button" aria-disabled="false" />
		</form>-->
		<div style="clear: both;"></div>
	</div>
</div>
<div class="clear"></div>
<?php if (!empty($photo_print_types)): ?>
	<div class="table_container" data-step="1" data-intro="<?php echo __('This area shows all the print types that you have created.', true); ?>" data-position="left">
		<div class="fade_background_top"></div>
		<div class="table_top"></div>
		<table id="print_types_list" class="list">
			<thead>
				<tr> 
					<th class="" colspan="2">
						<div class="content one_line">
							<?php __('Print Types'); ?>
						</div>
					</th> 
					<th class="last actions_call"></th>
				</tr> 
			</thead>
			<tbody>
				<tr class="spacer"><td colspan="3"></td></tr>
				<?php foreach($photo_print_types as $photo_print_type): ?> 
			 
				
					<tr class="photo_print_type_item" data-step="3" data-intro="<?php echo __('Displayed here is the name of the print type.', true); ?>" data-position="bottom" photo_print_type_id=" <?php echo $photo_print_type['PhotoPrintType']['id']; ?>">
						<td class="print_type_id first table_width_reorder_icon"><div class="reorder_print_type_grabber reorder_grabber" data-step="7" data-intro="<?php echo __('Rearrange the order of the print types. ', true); ?>" data-position="bottom" /> </td> 
						<td class="print_type" data-step="6" data-intro="<?php echo __('This area displays the name of the print type you have created.', true); ?>" data-position="bottom">
							<?php echo $photo_print_type['PhotoPrintType']['print_name']; ?>
						</td>
						<td class="table_actions">
							<span class="custom_ui">
								<a href="/admin/ecommerces/add_print_type_and_pricing/<?php echo $photo_print_type['PhotoPrintType']['id']; ?>/"><div class="add_button" data-step="4" data-intro="<?php echo __('Edit the print type.', true); ?>" data-position="left"><div class="content">Edit</div><div class="right_arrow_lines"><div></div></div></div></a>
								<a href="/admin/ecommerces/delete_print_type/<?php echo $photo_print_type['PhotoPrintType']['id']; ?>/"><div class="add_button icon" data-step="5" data-intro="<?php echo __('Delete a print type.', true); ?>" data-position="left"><div class="content">X</div></div></a>
							</span>
						</td>
					</tr>
				<?php endforeach; ?> 
			</tbody>
		</table>
	</div>
<?php else: ?>
	<h1><?php __('You have not added any print types yet.'); ?></h1>
<?php endif; ?>


<?php ob_start(); ?>
<ol>
	<li>This page just list available print types you have created (and has a button to add them)</li>
	<li><a href="/img/admin_screenshots/manage_print_types.jpg" target="_blank">screenshot</a></li>
	<li>Things to remember
		<ol>
			<li>This page will need explanation text at the top (or somewhere)</li>
			<li>We need a state for before the user has added any print types (with help making it obvious that they need to add one)</li>
			<li>Don't forget a design for the add new print type page</li>
			<li>This page needs a flash message</li>
		</ol>
	</li>
</ol>
<?php
$html = ob_get_contents();
ob_end_clean();
	echo $this->Element('admin/richard_notes', array(
	'html' => $html
)); ?>