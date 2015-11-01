<?php

$sqls = array();

$functions = array();

$sqls[] = "UPDATE `photo_caches` SET `unsharp_amount` = '0' WHERE `unsharp_amount` IS NULL;";
