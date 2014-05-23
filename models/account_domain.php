<?php
class AccountDomain extends AppModel {

	
	public function send_expired_domain_emails($data = array()) {
		$three_months_from_now = date('Y-m-d H:i:s', strtotime('23:59:59', strtotime('+3 months')));
		$account_domains = $this->find('all', array(
			'conditions' => array(
				'AccountDomain.expires <=' => $three_months_from_now,
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
}