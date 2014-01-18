<ul>
<?php foreach($photos as $f_photo): ?>
	<?php $img_src = $this->Photo->get_photo_path($f_photo['Photo']['id'], $height, $width, $sharpness, true); ?>
	<li photo_id="<?php echo $f_photo['Photo']['id']; ?>" style="background: url('<?php echo $img_src['url']; ?>'); width: <?php echo $img_src['width']; ?>px; height: <?php echo $img_src['height']; ?>px;"></li>
	<?php endforeach; ?>
</ul>