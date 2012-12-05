<?php foreach($photos as $photo): ?><!--
--><?php $img_src = $this->Photo->get_photo_path($photo['Photo']['id'], $height, $width, $sharpness, true); ?><!--
--><img photo_id="<?php echo $photo['Photo']['id']; ?>" src="<?php echo $img_src['url']; ?>" <?php echo $img_src['tag_attributes']; ?> /><!--
--><?php endforeach; ?>