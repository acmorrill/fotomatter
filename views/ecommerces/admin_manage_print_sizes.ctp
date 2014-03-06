<script type="text/javascript">
	jQuery(document).ready(function() {
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

<h1>Choose available formats for retail</h1>
<p>
	Instructions for receiving payment go here so all that mumbo jumbo on the screenshot below the Paypal button will go here. Cool beans? Cool.
IPlaceholder info for getting pade. Instructions will go here. Trty and keep it to two lines. But if more, that’s fine. Instructions for receiving payment go here so all that mumbo jumbo on the screenshot below the Paypal button will go here. Cool beans? Cool.
</p>

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


<?php ob_start(); ?>
<ol>
	<li>This page is where the user can decide what print sizes are available
		<ol>
			<li>This page will probobly need some help to explain what it is for - probobly at the top</li>
			<li>The sizes are not actual print sizes - they are actually just the sizes that will be options for when you create types</li>
			<li>FYI - restore defaults goes back to the starting sizes (this is the user doesn't have to think about this page if they don't want to)</li>
		</ol>
	</li>
	<li>Things to remember
		<ol>
			<li>This needs a flash message</li>
			<li>The confirm for restore defaults needs design</li>
			<li>We need a design for both the edit available print size page and the add new print size page
				<ol>
					<li>For the print short side dimension drop down we need a "taken" state for sizes already used</li>
					<li>This page needs a flash message</li>
				</ol>
			</li>
		</ol>
	</li>
</ol>
<?php
$html = ob_get_contents();
ob_end_clean();
	echo $this->Element('admin/richard_notes', array(
	'html' => $html
)); ?>
