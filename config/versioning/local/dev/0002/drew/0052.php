<?php

$sqls = array();

$functions = array();

$sqls[] = "ALTER TABLE  `photos` ADD  `is_globally_shared` TINYINT NOT NULL DEFAULT  '0' COMMENT  'if true then the image uses the global default images container' AFTER  `id` ;";
$sqls[] = "
	INSERT INTO `photos` (`id`, `is_globally_shared`, `date_taken`, `cdn-filename`, `cdn-filename-forcache`, `cdn-filename-smaller-forcache`, `display_title`, `display_subtitle`, `description`, `alt_text`, `enabled`, `photo_format_id`, `pixel_width`, `pixel_height`, `forcache_pixel_width`, `forcache_pixel_height`, `smaller_forcache_pixel_width`, `smaller_forcache_pixel_height`, `tag_attributes`, `created`, `modified`) VALUES
	(null, 1, '2014-07-24', 'Ancient-Waterways.jpg', 'Ancient-Waterways.jpg', 'Ancient-Waterways.jpg', 'Ancient-Waterways.jpg', '', '', '', 1, 1, 1500, 1030, 1500, 1030, 250, 172, 'width=\"1500\" height=\"1030\"', '2014-07-24 16:52:43', '2014-07-24 16:52:43'),
	(null, 1, '2014-07-24', 'Ashen-Flame.jpg', 'Ashen-Flame.jpg', 'Ashen-Flame.jpg', 'Ashen-Flame.jpg', '', '', '', 1, 1, 720, 571, 720, 571, 250, 198, 'width=\"720\" height=\"571\"', '2014-07-24 16:52:50', '2014-07-24 16:52:50'),
	(null, 1, '2014-07-24', 'Basic-Trees.jpg', 'Basic-Trees.jpg', 'Basic-Trees.jpg', 'Basic-Trees.jpg', '', '', '', 1, 1, 1500, 1087, 1500, 1087, 250, 181, 'width=\"1500\" height=\"1087\"', '2014-07-24 16:52:56', '2014-07-24 16:52:56'),
	(null, 1, '2014-07-24', 'Golden-Graces.jpg', 'Golden-Graces.jpg', 'Golden-Graces.jpg', 'Golden-Graces.jpg', '', '', '', 1, 4, 1530, 542, 1500, 531, 250, 89, 'width=\"1530\" height=\"542\"', '2014-07-24 16:53:03', '2014-07-24 16:53:03');
";		





