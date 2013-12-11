CREATE TABLE IF NOT EXISTS `authnet_line_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `authnet_order_id` int(11) NOT NULL,
  `unit_cost` decimal(10,2) NOT NULL,
  `name` varchar(80) COLLATE utf8_bin NOT NULL,
  `description` text COLLATE utf8_bin NOT NULL,
  `foreign_model` varchar(80) COLLATE utf8_bin NOT NULL,
  `foreign_key` int(11) NOT NULL,
  `authnet_line_item_type_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Table structure for table `authnet_line_item_types`
--

CREATE TABLE IF NOT EXISTS `authnet_line_item_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=5 ;

--
-- Dumping data for table `authnet_line_item_types`
--

INSERT INTO `authnet_line_item_types` (`id`, `name`) VALUES
(1, 'item'),
(2, 'subscription');

-- --------------------------------------------------------

--
-- Table structure for table `authnet_orders`
--

CREATE TABLE IF NOT EXISTS `authnet_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `authnet_profile_id` int(11) DEFAULT NULL COMMENT 'Null if not connected to profile',
  `foreign_model` varchar(80) COLLATE utf8_bin DEFAULT NULL,
  `foreign_key` int(11) DEFAULT NULL,
  `total` decimal(10,2) NOT NULL,
  `tax` decimal(10,2) NOT NULL,
  `shipping` decimal(10,2) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `authnet_profiles`
--

CREATE TABLE IF NOT EXISTS `authnet_profiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customerProfileId` bigint(20) NOT NULL,
  `customerPaymentProfileId` bigint(20) NOT NULL,
  `customerShippingAddressId` int(11) NOT NULL,
  `billing_firstname` varchar(80) NOT NULL,
  `billing_lastname` varchar(80) NOT NULL,
  `billing_address` varchar(80) NOT NULL,
  `billing_city` varchar(80) NOT NULL,
  `billing_state` varchar(80) NOT NULL,
  `billing_zip` varchar(80) NOT NULL,
  `billing_country` varchar(80) NOT NULL,
  `billing_phoneNumber` varchar(80) NOT NULL,
  `payment_cc_last_four` varchar(80) NOT NULL,
  `payment_expirationDate` datetime NOT NULL,
  `payment_cardCode` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;