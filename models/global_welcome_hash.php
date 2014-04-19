<?php

class GlobalWelcomeHash extends AppModel {
	public $name = 'GlobalWelcomeHash';
	public $useDbConfig = 'server_global';
	public $useTable = "welcome_hashes";
	
	
	public function create_new_hash_entry() {
		$this->SiteSetting = ClassRegistry::init('SiteSetting');
		$account_id = $this->SiteSetting->getVal('account_id');
		$site_domain = $this->SiteSetting->getVal('site_domain');
		
		// check existing
		$welcome_hash_exists = $this->find('first', array(
			'conditions' => array(
				'GlobalWelcomeHash.account_id' => $account_id,
			),
			'contain' => false,
		));
		if (!empty($welcome_hash_exists['GlobalWelcomeHash']['hash'])) {
			throw new Exception('A hash for account id already exists');
//			return $welcome_hash_exists['GlobalWelcomeHash']['hash'];
		}
		
		
		$hash = $this->generateRandomString(128);
		
		$global_welcome_hash = array();
		$global_welcome_hash['GlobalWelcomeHash']['hash'] = $hash;
		$global_welcome_hash['GlobalWelcomeHash']['account_id'] = $account_id;
		$global_welcome_hash['GlobalWelcomeHash']['site_domain'] = $site_domain;
		
		$this->create();
		if (!$this->save($global_welcome_hash)) {
			throw new Exception('Failed to save the welcome hash.');
		}
		
		return $hash;
	}
	
	function generateRandomString($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';
		$char_length = strlen($characters) - 1;
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $char_length)];
		}
		return $randomString;
	}
}
