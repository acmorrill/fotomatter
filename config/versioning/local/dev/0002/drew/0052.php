<?php

$sqls = array();

$functions = array();

$sqls[] = "ALTER TABLE  `photos` ADD  `is_globally_shared` TINYINT NOT NULL DEFAULT  '0' COMMENT  'if true then the image uses the global default images container' AFTER  `id` ;";
$sqls[] = "
	INSERT INTO `photos` (`id`, `is_globally_shared`, `date_taken`, `cdn-filename`, `cdn-filename-forcache`, `cdn-filename-smaller-forcache`, `display_title`, `display_subtitle`, `description`, `alt_text`, `enabled`, `photo_format_id`, `pixel_width`, `pixel_height`, `forcache_pixel_width`, `forcache_pixel_height`, `smaller_forcache_pixel_width`, `smaller_forcache_pixel_height`, `tag_attributes`, `created`, `modified`) VALUES
	(null, 1, '2014-07-25', 'Bow-Tie.jpg', 'Bow-Tie-forcache.jpg', 'Bow-Tie-smaller-forcache.jpg', '(Example Image) Bow Tie', '', '', '', 1, 1, 3130, 2075, 1500, 994, 250, 166, 'width=\"3130\" height=\"2075\"', '2014-07-25 17:30:33', '2014-07-25 17:30:33'),
	(null, 1, '2014-07-25', 'Wreath.jpg', 'Wreath-forcache.jpg', 'Wreath-smaller-forcache.jpg', '(Example Image) Wreath', '', '', '', 1, 1, 3072, 2048, 1500, 1000, 250, 167, 'width=\"3072\" height=\"2048\"', '2014-07-25 17:30:41', '2014-07-25 17:30:41'),
	(null, 1, '2014-07-25', 'A-Degree-Coyote.jpg', 'A-Degree-Coyote-forcache.jpg', 'A-Degree-Coyote-smaller-forcache.jpg', '(Example Image) 22 Degree Coyote', '', '', '', 1, 2, 3539, 4000, 1327, 1500, 221, 250, 'width=\"3539\" height=\"4000\"', '2014-07-25 17:19:28', '2014-07-25 17:19:28'),
	(null, 1, '2014-07-28', 'A-Tangerine-Blue.jpg', 'A-Tangerine-Blue-forcache.jpg', 'A-Tangerine-Blue-smaller-forcache.jpg', '(Example Image) A Tangerine Blue', '', '', '', 1, 1, 4000, 2836, 1500, 1064, 250, 177, 'width=\"4000\" height=\"2836\"', '2014-07-28 14:45:09', '2014-07-28 14:45:09'),
	(null, 1, '2014-07-25', 'Harvest.jpg', 'Harvest-forcache.jpg', 'Harvest-smaller-forcache.jpg', '(Example Image) Harvest', '', '', '', 1, 3, 4000, 4000, 1500, 1500, 250, 250, 'width=\"4000\" height=\"4000\"', '2014-07-25 17:27:29', '2014-07-25 17:27:29'),
	(null, 1, '2014-07-25', 'Provo-River-Stars.jpg', 'Provo-River-Stars-forcache.jpg', 'Provo-River-Stars-smaller-forcache.jpg', '(Example Image) Provo River Stars', '', '', '', 1, 2, 2772, 4000, 1040, 1500, 173, 250, 'width=\"2772\" height=\"4000\"', '2014-07-25 17:28:01', '2014-07-25 17:28:01'),
	(null, 1, '2014-07-28', 'Southwest-Passage.jpg', 'Southwest-Passage-forcache.jpg', 'Southwest-Passage-smaller-forcache.jpg', '(Example Image) Southwest Passage', '', '', '', 1, 1, 4000, 3548, 1500, 1331, 250, 222, 'width=\"4000\" height=\"3548\"', '2014-07-28 14:30:35', '2014-07-28 14:30:35');
";		





