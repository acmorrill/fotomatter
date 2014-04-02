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

<h1>Choose available formats for retail 
	<div id="help_tour_button" class="custom_ui"><div class="add_button" type="submit"><div class="content"><?php echo __(HELP_TOUR_ENGLISH_TEXT); ?></div><div class="right_arrow_lines"><div></div></div></div></dIv>
</h1>
<p>
	Instructions for receiving payment go here so all that mumbo jumbo on the screenshot below the Paypal button will go here. Cool beans? Cool.
IPlaceholder info for getting pade. Instructions will go here. Trty and keep it to two lines. But if more, that’s fine. Instructions for receiving payment go here so all that mumbo jumbo on the screenshot below the Paypal button will go here. Cool beans? Cool.
</p>

<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('#add_new_printsize_button, #reset_printsize_button').click(function() {
			jQuery(this).closest('form').submit();
		});
	});
</script>

<div class="right">
	<div class="add_gallery_element custom_ui" style="margin: 5px; margin-bottom: 15px;">
		<form action="/admin/ecommerces/add_print_size/" method="get" style="float: right;">
			<div id="add_new_printsize_button" class="add_button" type="submit"><div class="content"><?php echo __('Add New Print Size', true); ?></div>
				<div class="plus_icon_lines"><div class="one"></div><div class="two"></div></div>
			</div>
		</form>
		<form id="reset_printsize_form" action="/admin/ecommerces/reset_print_sizes/" method="get" style="float: right; margin-right: 20px;">
			<div id="reset_printsize_button" class="add_button" type="submit"><div class="content"><?php echo __('Restore Defaults', true); ?></div></div>
		</form>
		<div style="clear: both;"></div>
	</div>
</div>
<div class="clear"></div>
<?php //debug($photo_avail_sizes); ?>
<?php if (!empty($photo_avail_sizes)): ?>
	<?php /*<div class="table_header">
		<label class="inline"><?php __('Available Print Sizes:'); ?></label> 
	</div> */ ?>
	<div class="table_container">
		<div class="fade_background_top"></div>
		<div class="table_top"></div>
		<table class="list">
			<thead>
				<tr> 
					<th class="first dimension_col">
						<div class="content one_line">
							<?php __('Dimension'); ?>
						</div>
					</th> 
					<th class="format_col">
						<div class="content one_line">
							<?php __('Format(s)'); ?>
						</div>
					</th> 
					<th class="last actions_call">
					</th>
				</tr> 
			</thead>
			<tbody>
				<tr class="spacer"><td colspan="3"></td></tr>
				<?php 
					$count = 1; 
					$total = count($photo_avail_sizes);
				?>
				<?php foreach($photo_avail_sizes as $photo_avail_size): ?> 
					<tr photo_avail_size_id="<?php echo $photo_avail_size['PhotoAvailSize']['id']; ?>" class="<?php echo ($count === 1) ? " first " : ""; ?><?php echo ($count === $total) ? " last " : ""; ?>">
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
								<a href="/admin/ecommerces/add_print_size/<?php echo $photo_avail_size['PhotoAvailSize']['id']; ?>/"><div class="add_button"><div class="content">Edit</div><div class="right_arrow_lines"><div></div></div></div></a>
								<a href="/admin/ecommerces/delete_print_size/<?php echo $photo_avail_size['PhotoAvailSize']['id']; ?>/"><div class="add_button icon"><div class="content">X</div></div></a>
							</span>
						</td>
					</tr>
				<?php $count++; endforeach; ?> 
			</tbody>
		</table>
	</div>
<?php else: ?>
	<h1><?php __('You have not added any sizes yet.'); ?></h1>
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
