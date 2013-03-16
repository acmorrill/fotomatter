<?php $photo_sellable_prints = $this->Photo->get_enabled_photo_sellable_prints($photo_id); ?>

<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('.submit_button').click(function() { 
			jQuery(this).closest('form').submit();
		});
	});
</script>

<?php if (!isset($photo_sellable_prints)): ?>
	<?php __('The add to cart buttons have an error.'); ?>
<?php else: ?>
	<div>
			<?php //debug($photo_sellable_prints); ?>
			<?php foreach ($photo_sellable_prints as $print_type_name => $print_type_sizes): ?>
				<h2><?php echo $print_type_name; ?></h2>
				<form action="/ecommerces/add_to_cart/" method="post">
					<select name="data[PhotoAvailSizesPhotoPrintType][id]">
						<?php foreach ($print_type_sizes as $print_type_size): ?>
							<!--DREW TODO - improve the money formatting here-->
							<option value="<?php echo $print_type_size['photo_avail_sizes_photo_print_type_id']; ?>"><?php echo $print_type_size['short_side_inches']; ?> x <?php echo $print_type_size['long_side_feet_inches']; ?> --- $<?php echo $print_type_size['price']; ?></option>  
						<?php endforeach; ?>
					</select> 
					<div class="submit_button">
						<div class="submit_inner"><?php __('Submit'); ?></div>
					</div>
				</form>
			<?php endforeach; ?>
	</div>
<?php endif; ?>


