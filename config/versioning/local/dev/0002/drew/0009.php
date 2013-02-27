<?php

$sqls = array();
$functions = array();



$sqls[] = "CREATE TABLE IF NOT EXISTS `photo_sellable_prints` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `photo_id` int(11) NOT NULL,
  `photo_avail_sizes_photo_print_type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";