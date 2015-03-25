<div id="image_data_container_<?php echo $photo['Photo']['id']; ?>" class='image_data_container' data-ecommerce_photo_id="<?php echo $photo['Photo']['id']; ?>">
	<?php 
		$left_empty = false;
		if (empty($photo['Photo']['display_subtitle']) && empty($photo['Photo']['date_taken']) && empty($photo['Photo']['description'])) {
			$left_empty = true;
		}
		
		$has_ecommerce = !empty($current_on_off_features['basic_shopping_cart']);
		$has_nothing = false;
		if ($has_ecommerce == false && $left_empty == true) {
			$has_nothing = true;
		}
	?>
	<?php if (!empty($photo['Photo']['display_title'])): ?>
		<div class="photo_title_container">
			<?php echo $this->Element('gallery/image_data/data_items/photo_title', compact('photo')); ?>
		</div>
	<?php endif; ?>
	<?php if ($has_nothing === false): ?>
		<div class="hr"></div>
	<?php endif; ?>
	
	<div class="left_and_right_container <?php if ($has_ecommerce == false): ?> no_ecommerce <?php endif; ?>">
		<?php if ($left_empty === false): ?>
			<div class="left_photo_data">
				<?php if( !empty($photo['Photo']['display_subtitle']) || !empty($photo['Photo']['date_taken']) ): ?>
					<div class="subtitle_description_container">
						<?php echo $this->Element('gallery/image_data/data_items/display_subtitle', compact('photo')); ?>

						<?php echo $this->Element('gallery/image_data/data_items/date_taken', compact('photo')); ?>
					</div>
				<?php endif; ?>

				<?php echo $this->Element('gallery/image_data/data_items/description', compact('photo')); ?>

				<br style='clear: both;' />
			</div>
		<?php endif; ?>
		<div class="right_cart_data <?php if ($left_empty === true): ?> left <?php endif; ?>">
			<?php 
				if (empty($photo_sellable_prints)) {
					$photo_sellable_prints = '';
				}
				echo $this->Element('cart_checkout/compact_image_add_to_cart_form_simple', array(
					'photo_id' => $photo['Photo']['id'],
					'photo_sellable_prints' => $photo_sellable_prints,
				)); 
			?>
		</div>
	</div>
</div>