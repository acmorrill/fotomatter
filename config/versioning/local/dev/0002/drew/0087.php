<?php

$sqls = array();

$functions = array();

$sqls[] = "ALTER TABLE `authnet_orders`  ADD `shipping_firstname` VARCHAR(132) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `refund_transaction_id`,  ADD `shipping_lastname` VARCHAR(132) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `shipping_firstname`,  ADD `shipping_address1` VARCHAR(132) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `shipping_lastname`,  ADD `shipping_address2` VARCHAR(132) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `shipping_address1`,  ADD `shipping_city` VARCHAR(132) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `shipping_address2`,  ADD `shipping_zip` VARCHAR(80) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `shipping_city`,  ADD `shipping_country_id` INT NOT NULL AFTER `shipping_zip`,  ADD `shipping_state_id` INT NOT NULL AFTER `shipping_country_id`,  ADD `shipping_country_name` VARCHAR(132) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `shipping_state_id`,  ADD `shipping_state_name` VARCHAR(132) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `shipping_country_name`;";


