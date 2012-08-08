<?php

$sqls = array();
$functions = array();

$sqls[] = 'CREATE TABLE IF NOT EXISTS `tags` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(80) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;';

$sqls[] =  'CREATE TABLE IF NOT EXISTS `photos_tags` (
  `id` int(11) NOT NULL auto_increment,
  `tag_id` int(11) NOT NULL,
  `photo_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;';

$sqls[] = 'ALTER TABLE  `tags` ADD UNIQUE  `name_unique` (  `name` )';

