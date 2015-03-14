<?php foreach ($photos as $photo): ?>
	<?php echo $this->Element('gallery/image_data/basic_image_data', compact('photo')); ?>
<?php endforeach; ?>