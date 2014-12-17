<?php
	$full_title = "fotomatter.net";
	if (!empty($layout_default)) {
		$full_title = "$layout_default | $full_title";
	}
	if (!empty($title_for_layout)) {
		$full_title = "$title_for_layout | $full_title";
	}
?><title><?php echo $full_title; ?></title>