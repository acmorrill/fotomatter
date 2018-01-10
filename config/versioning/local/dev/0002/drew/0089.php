<?php

$sqls = array();

$functions = array();

$sqls[] = 'DROP TABLE IF EXISTS photo_avail_sizes;';
$sqls[] = 'CREATE TABLE IF NOT EXISTS `photo_avail_sizes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `short_side_length` float NOT NULL,
  `photo_format_ids` char(30) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `short_side_length` (`short_side_length`,`photo_format_ids`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;';

$functions[]= function() {
	$this->PhotoAvailSize = ClassRegistry::init('PhotoAvailSize');
	$this->PhotoAvailSize->restore_avail_photo_size_defaults();
	return true;
};




