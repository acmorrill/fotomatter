<?php
$functions = array();
$sqls = array();

$sqls[] = 'drop table domains';
$sqls[] = 'CREATE TABLE IF NOT EXISTS `account_domains` (
  `id` char(36) COLLATE utf8_bin NOT NULL,
  `rackspace_id` bigint(20) NOT NULL,
  `url` varchar(1028) COLLATE utf8_bin NOT NULL,
  `modified` datetime NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;';

$sqls[] = 'CREATE TABLE IF NOT EXISTS `account_sub_domains` (
  `id` char(36) NOT NULL,
  `rackspace_id` varchar(32) NOT NULL,
  `account_domain_id` char(36) NOT NULL,
  `type` varchar(32) NOT NULL,
  `data` varchar(1024) NOT NULL,
  `ttl` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;';


