<?php if (!isset($sharpness)) { $sharpness = .4; } ?><?php foreach($photos as $f_photo): ?><!-- the comments in this file are required
	--><?php $img_src = $this->Photo->get_photo_path($f_photo['Photo']['id'], $height, $width, $sharpness, true); ?><!--
	--><img class="preload_for_progress" photo_id="<?php echo $f_photo['Photo']['id']; ?>" src="<?php echo $img_src['url']; ?>" <?php echo $img_src['tag_attributes']; ?> <?php echo $img_src['alt_title_str']; ?> /><!--
--><?php endforeach; ?>