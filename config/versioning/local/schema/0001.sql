-- phpMyAdmin SQL Dump
-- version 3.3.2deb1ubuntu1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 26, 2012 at 12:40 AM
-- Server version: 5.1.41
-- PHP Version: 5.3.2-1ubuntu4.14

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `celestj7_am_com`
--

-- --------------------------------------------------------

--
-- Table structure for table `db_local_updates`
--

CREATE TABLE IF NOT EXISTS `db_local_updates` (
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
-- Dumping data for table `db_local_updates`
--


-- --------------------------------------------------------

--
-- Table structure for table `db_local_update_items`
--

CREATE TABLE IF NOT EXISTS `db_local_update_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `db_local_update_id` int(11) NOT NULL,
  `index` int(11) NOT NULL,
  `type` enum('sql','func') NOT NULL,
  `status` enum('started','success','failed') NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `db_local_update_id` (`db_local_update_id`,`index`,`type`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `db_local_update_items`
--


-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `id` char(36) NOT NULL,
  `name` varchar(40) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `name`, `created`, `modified`) VALUES
('4dc3822e-06a0-4d9a-9ff5-13d4044492e4', 'System Developers', '2011-05-06 05:07:58', '2011-05-06 05:07:58');

-- --------------------------------------------------------

--
-- Table structure for table `groups_permissions`
--

CREATE TABLE IF NOT EXISTS `groups_permissions` (
  `group_id` char(36) NOT NULL,
  `permission_id` char(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `groups_permissions`
--

INSERT INTO `groups_permissions` (`group_id`, `permission_id`) VALUES
('4dc3822e-06a0-4d9a-9ff5-13d4044492e4', '4dc38200-2f70-46ff-b532-13d4044492e4');

-- --------------------------------------------------------

--
-- Table structure for table `groups_users`
--

CREATE TABLE IF NOT EXISTS `groups_users` (
  `group_id` char(36) NOT NULL,
  `user_id` char(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `groups_users`
--

INSERT INTO `groups_users` (`group_id`, `user_id`) VALUES
('4dc3822e-06a0-4d9a-9ff5-13d4044492e4', '4dc38294-d3cc-4db8-a3ef-13d4044492e4'),
('4dc3822e-06a0-4d9a-9ff5-13d4044492e4', '4f49e1c5-5980-43cd-9b3e-1f81044492e4');

-- --------------------------------------------------------

--
-- Table structure for table `image_wizard_quotes`
--

CREATE TABLE IF NOT EXISTS `image_wizard_quotes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `width` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  `quote` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=119 ;

--
-- Dumping data for table `image_wizard_quotes`
--

INSERT INTO `image_wizard_quotes` (`id`, `width`, `height`, `quote`) VALUES
(1, 16, 11, 189.53),
(2, 24, 16, 238.85),
(3, 30, 20, 312.51),
(4, 36, 24, 369.06),
(5, 45, 30, 441.34),
(6, 60, 40, 636.69),
(7, 71, 48, 814.23),
(8, 23, 16, 235.05),
(9, 28, 20, 302.97),
(10, 34, 24, 358.41),
(11, 42, 30, 422.88),
(12, 56, 40, 606.54),
(13, 68, 48, 788.3),
(14, 62, 44, 694.1),
(15, 24, 10, 210.11),
(16, 38, 16, 296.62),
(17, 53, 22, 415.86),
(18, 69, 29, 577.46),
(19, 7, 5, 144.96),
(20, 12, 8, 166.96),
(21, 66, 44, 726.46),
(22, 11, 8, 164.17),
(23, 23, 10, 207.06),
(24, 37, 16, 292.4),
(25, 51, 22, 405.77),
(26, 67, 29, 565.43),
(27, 6, 5, 142.54),
(28, 10, 8, 161.37),
(29, 14, 11, 183.19),
(30, 20, 16, 223.67),
(31, 25, 20, 288.67),
(32, 30, 24, 337.12),
(33, 38, 30, 398.26),
(34, 51, 40, 568.85),
(35, 56, 44, 645.55),
(36, 61, 48, 727.78),
(37, 19, 10, 194.88),
(38, 30, 16, 287.9),
(39, 41, 22, 355.32),
(40, 54, 29, 487.24),
(41, 15, 11, 186.36),
(42, 22, 16, 231.26),
(43, 27, 20, 298.2),
(44, 33, 24, 353.09),
(45, 41, 30, 416.72),
(46, 55, 40, 599),
(47, 60, 44, 677.91),
(48, 65, 48, 762.36),
(49, 5, 6, 142.54),
(50, 8, 10, 161.37),
(51, 11, 14, 183.19),
(52, 16, 21, 227.47),
(53, 20, 26, 293.43),
(54, 24, 31, 342.44),
(55, 30, 39, 404.42),
(56, 8, 8, 155.78),
(57, 11, 11, 173.68),
(58, 16, 16, 208.5),
(59, 20, 20, 240.84),
(60, 24, 24, 277.17),
(61, 30, 30, 349.04),
(62, 35, 35, 414.03),
(63, 40, 40, 485.94),
(64, 8, 5, 147.39),
(65, 17, 11, 192.7),
(66, 31, 20, 317.28),
(67, 37, 24, 349.38),
(68, 46, 30, 447.49),
(69, 61, 40, 644.23),
(70, 67, 44, 734.55),
(71, 73, 48, 831.52),
(72, 29, 20, 307.74),
(73, 35, 24, 363.73),
(74, 44, 30, 435.18),
(75, 59, 40, 629.15),
(76, 64, 44, 710.28),
(77, 70, 48, 805.59),
(78, 5, 5, 140.12),
(79, 10, 10, 167.46),
(80, 43, 30, 429.03),
(81, 57, 40, 614.08),
(82, 27, 10, 240.83),
(83, 43, 16, 317.69),
(84, 59, 22, 446.14),
(85, 78, 29, 631.59),
(86, 13, 11, 180.02),
(87, 19, 16, 219.88),
(88, 24, 20, 258.01),
(89, 29, 24, 331.8),
(90, 36, 30, 385.96),
(91, 65, 44, 718.37),
(92, 61, 44, 686.01),
(93, 67, 48, 779.65),
(94, 60, 26, 492.41),
(95, 32, 24, 347.77),
(96, 40, 30, 410.57),
(97, 29, 10, 247.59),
(98, 47, 16, 334.55),
(99, 65, 22, 476.41),
(100, 76, 26, 582),
(101, 85, 29, 673.69),
(102, 69, 48, 796.94),
(103, 35, 45, 482.48),
(104, 40, 52, 576.39),
(105, 44, 57, 653.64),
(106, 48, 62, 736.43),
(107, 18, 11, 195.87),
(108, 26, 16, 271.04),
(109, 32, 20, 322.05),
(110, 39, 24, 360.02),
(111, 49, 30, 465.95),
(112, 65, 40, 674.38),
(113, 71, 44, 766.92),
(114, 78, 48, 874.74),
(115, 70, 26, 548.4),
(116, 72, 48, 822.88),
(117, 39, 16, 300.83),
(118, 70, 29, 583.47);

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE IF NOT EXISTS `permissions` (
  `id` char(36) NOT NULL,
  `name` varchar(40) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `created`, `modified`) VALUES
('4dc38200-2f70-46ff-b532-13d4044492e4', '*', '2011-05-06 05:07:12', '2011-05-06 05:07:12');

-- --------------------------------------------------------

--
-- Table structure for table `photo_dimensions`
--

CREATE TABLE IF NOT EXISTS `photo_dimensions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `allimage_id` int(11) NOT NULL,
  `relWidth` float NOT NULL,
  `relHeight` float NOT NULL,
  `inWidth` float NOT NULL,
  `inHeight` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1069 ;

--
-- Dumping data for table `photo_dimensions`
--

INSERT INTO `photo_dimensions` (`id`, `allimage_id`, `relWidth`, `relHeight`, `inWidth`, `inHeight`) VALUES
(1068, 22, 700, 496, 67.7419, 48),
(1067, 22, 700, 496, 62.0968, 44),
(1066, 22, 700, 496, 56.4516, 40),
(1065, 22, 700, 496, 42.3387, 30),
(1064, 22, 700, 496, 33.871, 24),
(1063, 22, 700, 496, 28.2258, 20),
(1062, 22, 700, 496, 22.5806, 16),
(1061, 22, 700, 496, 15.5242, 11),
(1060, 22, 700, 496, 11.2903, 8),
(1059, 22, 700, 496, 7.05645, 5);

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--

CREATE TABLE IF NOT EXISTS `site_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(30) CHARACTER SET utf8 NOT NULL,
  `value` char(128) CHARACTER SET utf8 NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `site_settings`
--

INSERT INTO `site_settings` (`id`, `name`, `value`, `created`, `modified`) VALUES
(1, 'current_schema', '0001', '2012-02-26 00:39:44', '2012-02-26 00:39:44');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` char(36) NOT NULL,
  `email_address` varchar(127) NOT NULL,
  `password` varchar(40) NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email_address`, `password`, `active`, `created`, `modified`) VALUES
('4dc38294-d3cc-4db8-a3ef-13d4044492e4', 'acmorrill@gmail.com', '9d3c620291d8c235446ea52876ebbacaee49bca7', 1, '2011-05-06 05:09:40', '2011-05-06 05:09:40'),
('4f49e1c5-5980-43cd-9b3e-1f81044492e4', 'adamdude828@gmail.com', '01ec50cd73fcbe40a3204d54783d490af696e749', 1, '2012-02-26 00:39:49', '2012-02-26 00:39:49');
