-- phpMyAdmin SQL Dump
-- version 3.3.2deb1ubuntu1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 26, 2012 at 05:05 PM
-- Server version: 5.1.41
-- PHP Version: 5.3.2-1ubuntu4.14

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `server_global`
--

-- --------------------------------------------------------

--
-- Table structure for table `db_global_updates`
--

CREATE TABLE IF NOT EXISTS `db_global_updates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_name` char(40) CHARACTER SET utf8 NOT NULL,
  `dev` char(20) CHARACTER SET utf8 NOT NULL,
  `status` enum('pending','started','success','failed') NOT NULL DEFAULT 'pending',
  `type` enum('sql','php') NOT NULL,
  `schema` char(10) NOT NULL,
  `full_file_path` text NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `file_name` (`file_name`,`dev`,`schema`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `db_global_updates`
--


-- --------------------------------------------------------

--
-- Table structure for table `db_global_update_items`
--

CREATE TABLE IF NOT EXISTS `db_global_update_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `db_global_update_id` int(11) NOT NULL,
  `index` int(11) NOT NULL,
  `type` enum('sql','func') NOT NULL,
  `status` enum('started','success','failed') NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `db_local_update_id` (`db_global_update_id`,`index`,`type`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `db_global_update_items`
--


-- --------------------------------------------------------

--
-- Table structure for table `server_settings`
--

CREATE TABLE IF NOT EXISTS `server_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(30) CHARACTER SET utf8 NOT NULL,
  `value` char(128) CHARACTER SET utf8 NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `server_settings`
--

