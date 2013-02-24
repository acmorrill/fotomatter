<?php foreach($photos as $f_photo): ?><!--
--><?php $img_src = $this->Photo->get_photo_path($f_photo['Photo']['id'], $height, $width, $sharpness, true); ?><!--
--><img photo_id="<?php echo $f_photo['Photo']['id']; ?>" src="<?php echo $img_src['url']; ?>" <?php echo $img_src['tag_attributes']; ?> /><!--
--><?php endforeach; ?>