<?php

$sqls = array();
$functions = array();



$sqls[] = "ALTER TABLE  `paypal_reimbursement_logs` ADD  `order_ids` CHAR( 100 ) NOT NULL AFTER  `amount`";