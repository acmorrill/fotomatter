<?php echo $this->Session->flash(); ?>

<?php $photo_sellable_prints = $this->Photo->get_enabled_photo_sellable_prints($photo_id); ?>

<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('#add_to_cart_buttons_cont .submit_button_cont').click(function() { 
			jQuery(this).closest('form').submit();
		});
	});
</script>

<div id="add_to_cart_buttons_cont">
<?php if (empty($photo_sellable_prints)): ?>
	<!-- <?php __('The add to cart buttons have not been fully setup'); ?> -->
<?php else: ?>
	<?php debug($photo_sellable_prints); ?>
	<?php foreach ($photo_sellable_prints as $print_type_name => $print_type_sizes): ?>
		<h2><?php echo $print_type_name; ?></h2>
		<form action="/ecommerces/add_to_cart/" method="post">
			<input type="hidden" name="data[Photo][id]" value="<?php echo $photo_id; ?>" />
			<input type="hidden" name="data[PhotoPrintType][id]" value="<?php echo $print_type_sizes['print_type_id']; ?>" />
			<select name="data[Photo][short_side_inches]">
				<?php foreach ($print_type_sizes['items'] as $print_type_size): ?>
					<!--DREW TODO - improve the money formatting here-->
					<option value="<?php echo $print_type_size['short_side_inches']; ?>"><?php echo $print_type_size['short_side_inches']; ?> x <?php echo $print_type_size['long_side_feet_inches']; ?> --- $<?php echo $print_type_size['price']; ?></option>  
				<?php endforeach; ?>
			</select> 
			<div class="submit_button_cont">
				<div class="submit_inner"><?php __('Submit'); ?></div>
			</div>
		</form>
	<?php endforeach; ?>
<?php endif; ?>
</div>


