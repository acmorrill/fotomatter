<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('#reset_printsize_button').click(function(e) {
			e.preventDefault();
			
			jQuery.foto('confirm', {
				message: '<?php echo __('Are you sure you want to reset the available print sizes?', true); ?>',
				onConfirm: function() {
					jQuery('#reset_printsize_form').submit();
				}
			});
		});
		
		jQuery('#add_new_printsize_button').click(function() {
			jQuery(this).closest('form').submit();
		});
	});
</script>

<h1><?php echo __('Choose available formats for retail', true); ?>
	<div id="help_tour_button" class="custom_ui"><?php echo $this->Element('/admin/get_help_button'); ?></div>
</h1>
<p>
	<?php echo __('Presented are the sizes available to you and it is a complete list of the print size that you sell. You will need to make a Print Type to go along with the Print Size to match the available sizes. That is done in the “Manage Print Types and Default Pricing” tab on the left hand side of the page.', true); ?>
</p>

<div class="right">
	<div class="add_gallery_element custom_ui" style="margin: 5px; margin-bottom: 15px;">
		<form action="/admin/ecommerces/add_print_size/" method="get" style="float: right;">
			<div id="add_new_printsize_button" class="add_button" type="submit" data-step="2" data-intro="<?php echo __("You don’t have to do anything here unless you don’t want to use the default print sizes. If you have custom print sizes that you would like to sell you will need to add them by using this button.", true); ?>" data-position="bottom"><div class="content"><?php echo __('Add New Print Size', true); ?></div>
				<div class="plus_icon_lines icon-_button-01"><div class="one"></div><div class="two"></div></div>
			</div>
		</form>
		<form id="reset_printsize_form" action="/admin/ecommerces/reset_print_sizes/" method="get" style="float: right; margin-right: 20px;">
			<div id="reset_printsize_button" class="add_button" type="submit" data-step="3" data-intro="<?php echo __("Clicking this button will clear all print sizes you have created or it will help you create some print sizes if you don't have any created.", true); ?>" data-position="bottom"><div class="content"><?php echo __('Restore Defaults', true); ?></div></div>
		</form>
		<div style="clear: both;"></div>
	</div>
</div>
<div class="clear"></div>

<div class="table_container" data-step="1" data-intro="<?php echo __('Presented are the sizes available to you and it is a complete list of the print size that you sell. You will need to make a Print Type to go along with the Print Size to match the available sizes. That is done in the “Manage Print Types and Default Pricing” tab on the left hand side of the page.', true); ?>" data-position="top">
	<div class="fade_background_top"></div>
	<div class="table_top"></div>
	<table class="list">
		<thead>
			<tr> 
				<th class="first dimension_col" data-step="4" data-intro="<?php echo __('The short side is the dimension of the shorter side of an image depending on the format. For example, the short side of a landscape is the height while the short side of a vertical panoramic is the width. The long side will be calculated based on the actual image depending on the formats.', true); ?>" data-position="right">
					<div class="content one_line">
						<?php echo __('Dimension', true); ?>
					</div>
				</th> 
				<th class="format_col"data-step="5" data-intro="<?php echo __('Format(s) is the type of image orientaion. Such as landscape, portrait, square and so on.', true); ?>" data-position="right">
					<div class="content one_line">
						<?php echo __('Format(s)', true); ?>
					</div>
				</th> 
				<th class="last actions_call">
				</th>
			</tr> 
		</thead>
		<tbody>
			<tr class="spacer"><td colspan="3"></td></tr>

			<?php if (empty($photo_avail_sizes)): ?>
				<tr class="first last">
					<td class="first last" colspan="3">
						<div class="rightborder"></div>
						<span>You have not added any print sizes yet.</span>
					</td>
				</tr>
			<?php endif; ?>

			<?php 
				$count = 1; 
				$total = count($photo_avail_sizes);
			?>
			<?php foreach($photo_avail_sizes as $photo_avail_size): ?> 
				<tr photo_avail_size_id="<?php echo $photo_avail_size['PhotoAvailSize']['id']; ?>" class="<?php echo ($count === 1) ? " first " : ""; ?><?php echo ($count === $total) ? " last " : ""; ?>" >
					<td class="first">
						<div class="rightborder"></div>
						<span><?php echo $photo_avail_size['PhotoAvailSize']['short_side_length']; ?> x --</span>
					</td>
					<td>
						<div class="rightborder"></div>
						<span style="display: inline-block;">
							<?php $formats = Set::extract('/PhotoFormat/display_name', $photo_avail_size); ?>
							<?php echo implode(' / ', $formats) ?>
						</span>
					</td>
					<td class="last table_actions">
						<div class="rightborder"></div>
						<span class="custom_ui">
							<a href="/admin/ecommerces/add_print_size/<?php echo $photo_avail_size['PhotoAvailSize']['id']; ?>/"><div class="add_button"><div class="content"><?php echo __('Edit',true);?></div><div class="right_arrow_lines icon-arrow-01"><div></div></div></div></a>
							<a class="delete_link" href="/admin/ecommerces/delete_print_size/<?php echo $photo_avail_size['PhotoAvailSize']['id']; ?>/"><div class="add_button icon icon_close"><div class="content icon-close-01"></div></div></a>
						</span>
					</td>
				</tr>
			<?php $count++; endforeach; ?> 
		</tbody>
	</table>
</div>


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
			<li>data-step="4" data-intro="'.__('', true).'" data-position="right"'</li>
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
