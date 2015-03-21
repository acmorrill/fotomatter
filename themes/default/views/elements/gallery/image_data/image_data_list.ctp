<?php
	if (empty($data_layout)) {
		$data_layout = 'basic_image_data';
	}
?>
<?php foreach ($photos as $photo): ?>
	<?php echo $this->Element("gallery/image_data/data_layouts/$data_layout", compact('photo')); ?>
<?php endforeach; ?>