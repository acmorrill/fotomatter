<?php

$sqls = array();

$functions = array();

$sqls[] = "ALTER TABLE  `photo_prebuild_cache_sizes` ADD  `unsharp` FLOAT NULL AFTER  `max_width` ;";
$sqls[] = "ALTER TABLE  `photo_prebuild_cache_sizes` ADD  `crop` BOOLEAN NOT NULL DEFAULT FALSE AFTER  `unsharp` ;";
$sqls[] = "ALTER TABLE `photo_prebuild_cache_sizes` DROP `created`;";
$sqls[] = "ALTER TABLE `photo_prebuild_cache_sizes` DROP `modified`;";

$sqls[] = "CREATE TABLE IF NOT EXISTS `theme_prebuild_cache_sizes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `theme_id` int(11) NOT NULL,
  `max_width` int(11) NOT NULL,
  `max_height` int(11) NOT NULL,
  `unsharp` float NOT NULL DEFAULT '0.4',
  `crop` tinyint(1) NOT NULL DEFAULT '0',
  `photo_format_id` int(11) DEFAULT NULL,
  `used_on_upload` int(10) unsigned NOT NULL DEFAULT '0',
  `used_in_theme` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `theme_id` (`theme_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;";

$sqls[] = "INSERT INTO `theme_prebuild_cache_sizes` (`id`, `theme_id`, `max_width`, `max_height`, `unsharp`, `crop`, `photo_format_id`, `used_on_upload`, `used_in_theme`) VALUES
(1, 3, 556, 453, 0.4, 1, NULL, 0, 0),
(2, 3, 300, 140, 0.4, 0, NULL, 0, 0),
(3, 3, 185, 185, 0.5, 0, NULL, 0, 0),
(4, 3, 700, 700, 0.4, 0, NULL, 0, 0),
(5, 3, 1000, 1000, 0.4, 0, NULL, 0, 0),
(6, 6, 720, 720, 0.5, 0, NULL, 0, 0),
(7, 6, 179, 179, 0.5, 0, NULL, 0, 0),
(8, 6, 700, 700, 0.4, 0, NULL, 0, 0),
(9, 6, 900, 900, 0.4, 0, 4, 0, 0),
(10, 7, 2000, 500, 0.4, 0, NULL, 0, 0),
(11, 13, 2000, 500, 0.4, 0, NULL, 0, 0),
(12, 8, 636, 636, 0.4, 0, 3, 0, 0),
(13, 8, 636, 680, 0.4, 0, 2, 0, 0),
(14, 8, 636, 3000, 0.4, 0, 2, 0, 0),
(15, 8, 720, 3000, 0.4, 0, 1, 0, 0),
(16, 8, 3000, 300, 0.4, 0, 4, 0, 0),
(17, 8, 3000, 680, 0.4, 0, 5, 0, 0),
(18, 8, 636, 3000, 0.4, 0, 5, 0, 0);";


// data for the current themes
// width, height, sharpness, crop, is_pano (null means both)
// andrewmorrill - 3 -  'width' => 556, 'height' => 453, .4, true, null ---- done
// andrewmorrill - 3 -  width: 300, height: 140 .4, false, null  ---- done
// andrewmorrill - 3 -  width: 185, height: 185 .5, false, null  ---- done
// andrewmorrill - 3 -  width: 700, height: 700 .4, false, null  ---- done
// andrewmorrill - 3 -  width: 1000, height: 1000 .4, false, 4 ---- done
// 
// Viewfinder - 6 -  'width' => 720, 'height' => 720, .5, false, null ---- done
// Viewfinder - 6 -  'width' => 179, 'height' => 179, .5, false, null ---- done
// Viewfinder - 6 -  'width' => 700, 'height' => 700, .4, false, null ---- done
// Viewfinder - 6 -  'width' => 900, 'height' => 900, .4, false, 4 ---- done
// 
// White Balance - 7 -  'width' => 2000, 'height' => 500, .4, false, null ---- done
// 
// Dark Slide - 13 -  'width' => 2000, 'height' => 500, .4, false, null ---- done

// still need to do angular slider
// Angle - 8 -  'width' => 636, 'height' => 636, .4, false, 3 ---- done
// Angle - 8 -  'width' => 636, 'height' => 680, .4, false, 2 ---- done
// Angle - 8 -  'width' => 636, 'height' => 3000, .4, false, 2 ---- done
// Angle - 8 -  'width' => 720, 'height' => 3000, .4, false, 1 ---- done
// Angle - 8 -  'width' => 3000, 'height' => 300, .4, false, 4 ---- done
// Angle - 8 -  'width' => 3000, 'height' => 680, .4, false, 5 ---- done
// Angle - 8 -  'width' => 636, 'height' => 3000, .4, false, 5 ---- done







