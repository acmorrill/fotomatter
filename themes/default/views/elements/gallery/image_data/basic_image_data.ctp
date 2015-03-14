<div id="image_data_container_<?php echo $photo['Photo']['id']; ?>" class='image_data_container' data-ecommerce_photo_id="<?php echo $photo['Photo']['id']; ?>">
	<div class="hr"></div>
	
	<?php echo $this->Element('gallery/image_data/photo_title', compact('photo')); ?>
	
	<?php echo $this->Element('gallery/image_data/display_subtitle', compact('photo')); ?>
	
	<?php echo $this->Element('gallery/image_data/date_taken', compact('photo')); ?>
	
	<?php echo $this->Element('gallery/image_data/description', compact('photo')); ?>

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