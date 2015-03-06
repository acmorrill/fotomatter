<?php foreach($photos as $f_photo): ?><!-- the comments in this file are required
	--><?php $img_src = $this->Photo->get_photo_path($f_photo['Photo']['id'], $height, $width, .5, true); ?> <!--
	--><img class="preload_for_progress" photo_id="<?php echo $f_photo['Photo']['id']; ?>" src="<?php echo $img_src['url']; ?>" <?php echo $img_src['tag_attributes']; ?> alt="<?php echo $f_photo['Photo']['alt_text']; ?>" /><!--
--><?php endforeach; ?>