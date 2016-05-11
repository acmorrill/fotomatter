<?php
class AccountDomain extends AppModel {

	public $hasMany = array(
		'AccountSubDomain' => array(
			'dependent' => true,
		),
	);
	
	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);
		
		$this->primary_domain_apc_key = 'primary_domain_'.$_SERVER['local']['database'];
	}
	
	public function set_system_domain_as_primary() { 
		$system_domain = $this->find('first', array(
			'conditions' => array(
				'AccountDomain.type' => 'system',
			),
			'contain' => false,
		));
		
		return $this->set_as_primary($system_domain['AccountDomain']['id']);
	}
	
	public function set_as_primary($primary_domain_id) {
		$new_primary_domain = $this->find('first', array(
			'conditions' => array(
				'AccountDomain.id' => $primary_domain_id,
			),
			'contain' => false
		));
		if (empty($new_primary_domain)) {
			$this->major_error("Tried to set primary domain with id that doesn't exist, should never happen.", compact('new_primary_domain', 'primary_domain_id'));
			return false;
		}

		$remove_primary_query = "
			UPDATE account_domains SET is_primary = 0
		";
		if ($this->query($remove_primary_query) === false) {
			$this->major_error('Failed remove all domains primary', compact('new_primary_domain', 'primary_domain_id'));
			return false;
		}
		

		$new_primary_domain['AccountDomain']['is_primary'] = 1;
		$this->create();
		if ($this->save($new_primary_domain) === false) {
			$this->major_error('Tried to set new domain as primary but the save failed', compact('new_primary_domain', 'primary_domain_id'));
			return false;
		}
		
		return true;
	}
	
	public function send_expired_domain_emails($data = array()) {
		$three_months_from_now = date('Y-m-d H:i:s', strtotime('23:59:59', strtotime('+3 months')));
		$account_domains = $this->find('all', array(
			'conditions' => array(
				'AccountDomain.expires <=' => $three_months_from_now,
				'AccountDomain.type' => 'purchased',
			),
			'contain' => false,
		));
		
		
		$data_to_email = array();
		foreach ($account_domains as $account_domain) {
			$days_till_expired = $this->get_days_until_expired($account_domain['AccountDomain']['expires']);
			
			if($days_till_expired < DOMAIN_MAX_DAYS_PAST_EXPIRE) {
				// do nothing
			} else if (
				$days_till_expired <= 7 ||
				$days_till_expired === 14 ||
				$days_till_expired === 21 || 
				$days_till_expired === 30 ||
				$days_till_expired === 60 ||
				$days_till_expired === 90
			) {
				$data_to_email[$account_domain['AccountDomain']['url']] = array(
					'expires' => $account_domain['AccountDomain']['expires'],
					'days_till_expired' => $days_till_expired,
				);
			} 
			
			
			//$days_till_expired = strtotime($expire_date) - time();
		}
		
		if (!empty($data_to_email)) {
			$this->send_fotomatter_email('send_domain_renew_reminder_email', $data_to_email);
		}
		
		return true;
	}
	
	public function get_days_until_expired($expires) {
		$expire_timestamp = strtotime($expires);
		$expire_date = new DateTime($expires);
		$today_timestamp = strtotime('00:00:00');
		$today_date = new DateTime('00:00:00');

		$days_till_expired = (int)$expire_date->diff($today_date)->format("%a");
		if ($today_timestamp > $expire_timestamp) {
			$days_till_expired = -$days_till_expired;
		}
		
		return $days_till_expired;
	}
	
	public function beforeSave($options = array()) {
		parent::beforeSave($options);
		apc_delete($this->primary_domain_apc_key);
		
		return true;
	}
	
	public function beforeDelete() {
		parent::beforeDelete();
		apc_delete($this->primary_domain_apc_key);
		
		return true;
	}
	
	public function get_current_primary_domain() {
		if (apc_exists($this->primary_domain_apc_key)) {
			return apc_fetch($this->primary_domain_apc_key);
		}
		
		$end_of_day_today = date('Y-m-d H:i:s', strtotime('23:59:59'));
		$primary_domain = $this->find('first', array(
			'conditions' => array(
				'AccountDomain.expires >' => $end_of_day_today,
				'AccountDomain.is_primary' => 1,
				'AccountDomain.type' => 'purchased',
			),
			'contain' => false,
		));
		if (empty($primary_domain['AccountDomain']['url'])) {
			$primary_domain = $this->find('first', array(
				'conditions' => array(
					'AccountDomain.is_primary' => 1,
					'AccountDomain.type !=' => 'purchased',
				),
				'contain' => false,
			));
		}
		$result = false;
		if (!empty($primary_domain['AccountDomain']['url'])) {
			$result = $primary_domain['AccountDomain']['url'];
		}
		
		if ($result === false) { // if no primary domain return the build domain
			$this->SiteSetting = ClassRegistry::init('SiteSetting');
			$site_domain = $this->SiteSetting->getVal('site_domain');
			$result = "$site_domain.fotomatter.net";
		}
		
		apc_store($this->primary_domain_apc_key, $result, 28800); // 8 hours
		
		return $result;
	}
}