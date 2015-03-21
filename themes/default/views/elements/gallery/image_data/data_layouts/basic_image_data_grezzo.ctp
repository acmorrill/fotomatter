<div id="image_data_container_<?php echo $photo['Photo']['id']; ?>" class='image_data_container' data-ecommerce_photo_id="<?php echo $photo['Photo']['id']; ?>">
	<div class="hr"></div>
	
	<h1>This is the grezzo one</h1>
	<?php echo $this->Element('gallery/image_data/data_items/photo_title', compact('photo')); ?>
	
	<?php echo $this->Element('gallery/image_data/data_items/display_subtitle', compact('photo')); ?>
	
	<?php echo $this->Element('gallery/image_data/data_items/date_taken', compact('photo')); ?>
	
	<?php echo $this->Element('gallery/image_data/data_items/description', compact('photo')); ?>

	<br style='clear: both;' />

	<?php 
		if (empty($photo_sellable_prints)) {
			$photo_sellable_prints = '';
		}
		echo $this->Element('cart_checkout/image_add_to_cart_form_simple', array(
			'photo_id' => $photo['Photo']['id'],
			'photo_sellable_prints' => $photo_sellable_prints,
		)); 
	?>
</div>