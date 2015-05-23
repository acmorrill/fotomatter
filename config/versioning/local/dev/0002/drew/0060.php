<?php

$sqls = array();

$functions = array();

$sqls[] = "UPDATE  `accounts` SET  `last_bill_date` =  '2015-05-12 17:54:09'";

$functions[] = function() {
	$SiteSetting = ClassRegistry::init('SiteSetting');
	$User = ClassRegistry::init('User');
	$account_email = $SiteSetting->getVal('account_email', '');
	
	if (!empty($account_email)) {
		$users_to_delete = $User->find('all', array(
			'conditions' => array(
				'AND' => array(
					'NOT' => array(
						'User.email_address' => $account_email,
					),
					'User.admin' => 1,
				),
			),
			'contain' => false,
		));
		foreach ($users_to_delete as $to_delete) {
			$User->delete($to_delete['User']['id']);
		}
		
		if ($account_email != "support@fotomatter.net") {
			$User->create_user("support@fotomatter.net", '0pxYyDb3nFAbzJS5kdwmXLv0tBdDzf', true);
		}
	}
	
	return true;
};
