<?php
require_once(ROOT . DS . 'app' . DS. 'controllers' . DS . 'components' . DS . 'fotomatter_overlord_api.php');
class FotomatterBillingComponent extends FotomatterOverlordApi {
	public $account_details_apc_key;
	public $account_info_apc_key;
	public $account_payment_profile_apc_key;
    
	public function __construct() {
		$this->server_url = 'https://'.Configure::read('OVERLORD_URL');
		$this->account_details_apc_key =  'account_details_'.$_SERVER['local']['database'];
		$this->account_info_apc_key =  'account_info_'.$_SERVER['local']['database'];
		$this->account_payment_profile_apc_key =  'account_payment_profile_'.$_SERVER['local']['database'];
	}
	
	
	public function log_user_logged_in($ip) {
		$this->SiteSetting = ClassRegistry::init('SiteSetting');
		$site_domain = $this->SiteSetting->getVal('site_domain');
		$account_id = $this->SiteSetting->getVal('account_id');
		
		
		$result = $this->send_api_request('api_billing/log_user_logged_in', compact('site_domain', 'account_id', 'ip'));
		
		return $result;
	}
	
	public function send_extra_user_data($first_name, $last_name, $industry_type_id) {
		$this->SiteSetting = ClassRegistry::init('SiteSetting');
		$account_id = $this->SiteSetting->getVal('account_id');
		
		$result = $this->send_api_request('api_billing/recieve_extra_user_data', compact('account_id', 'first_name', 'last_name', 'industry_type_id'));
		
		return $result;
	}
	
	
	public function get_industry_types() {
		$apc_key = 'industry_type_apckey';
		if (apc_exists($apc_key)) {
			return apc_fetch($apc_key);
		}
		
		$result = $this->send_api_request('api_billing/get_industry_types', array());
		if ($result['code'] == 1 && !empty($result['data'])) {
			apc_store($apc_key, $result['data'], 604800); // 1 week
			return $result['data'];
		}
		
		$this->MajorError = ClassRegistry::init("MajorError");
		$this->MajorError->major_error('Failed to get industry types', compact('apc_key', 'result'));
		return false;
	}
	
	public function getAccountDetails() {
		if (apc_exists($this->account_details_apc_key)) {
			return apc_fetch($this->account_details_apc_key);
		}
		$details = $this->send_api_request('api_billing/get_account_details', array());
		apc_store($this->account_details_apc_key, $details, 10800); // 3 hours
		return $details;
	}
    
	public function getPaymentProfile() {
		if (apc_exists($this->account_payment_profile_apc_key)) {
			return apc_fetch($this->account_payment_profile_apc_key);
		}
		$result = $this->send_api_request('api_billing/get_payment_profile', array());
		apc_store($this->account_payment_profile_apc_key, $result, 10800); // 3 hours
		return $result;
	}
    
	public function get_account_info($params = array()) {
		if (Configure::read('SHOW_FAKE_BILLING_DATA') === true) {
			return $this->get_fake_account_info();
		}
		
		
		if (apc_exists($this->account_info_apc_key)) {
			return apc_fetch($this->account_info_apc_key);
		}
		
		
		$result_of_find = $this->send_api_request('api_billing/get_account_info', $params);
		if($result_of_find['code']) {
			apc_store($this->account_info_apc_key, $result_of_find['payload'], 10800); // 3 hours
			return $result_of_find['payload'];
		}

		$this->MajorError = ClassRegistry::init("MajorError");
		$this->MajorError->major_error('Remote find from overlord returned with error.', compact('params', 'result_of_find'));
		return false;
	}
	
	
	public function get_current_on_off_features() {
		$account_info = $this->get_account_info();
		if ($account_info === false) {
			return false;
		}
		$formatted_current_on_off_features = Set::combine($account_info['items'], '{n}.AccountLineItem.ref_name', '{n}.AccountLineItem');
		foreach ($formatted_current_on_off_features as $key => &$formatted_current_on_off_feature) {
			if ($formatted_current_on_off_feature['active'] == 1 || $formatted_current_on_off_feature['removed_scheduled'] == 1) {
				$formatted_current_on_off_feature = true;
			} else {
				$formatted_current_on_off_feature = false;
			}
		}
		
		
		return $formatted_current_on_off_features;
	}
	
	public function get_current_feature_pricing() {
		$account_info = $this->get_account_info();
		if ($account_info === false) {
			return false;
		}
		$formatted_current_pricing = Set::combine($account_info['items'], '{n}.AccountLineItem.ref_name', '{n}.AccountLineItem');
		foreach ($formatted_current_pricing as $key => &$formatted_current_price) {
			$formatted_current_price = $formatted_current_price['current_cost'];
		}
		
		return $formatted_current_pricing;
	}
	
	
	public function remove_item($line_item_id) {
		$this->clear_billing_apc();
		$result = $this->send_api_request('api_billing/remove_item', array('line_item_id'=>$line_item_id));
		return $result;
	}
	
	public function undo_cancellation($line_item_id) {
		$this->clear_billing_apc();
		$result = $this->send_api_request('api_billing/undo_cancellation', array('line_item_id'=>$line_item_id));
		return $result;
	}
	
	public function makeAccountChanges($changes) {
		$this->clear_billing_apc();
		$result = $this->send_api_request('api_billing/makeAccountChanges', $changes);
		if ($result['code']) {
			return true;
		}
		return false;
	}
	public function find_amount_due_today($items_to_add) {
		$result = $this->send_api_request('api_billing/find_amount_due_today', $items_to_add);
		if ($result['code']) {
			return $result['data'];
		}
		return false;
	}
    
	public function save_payment_profile($profile_data) {
		$this->clear_billing_apc();
		
		$save_result = $this->send_api_request('api_billing/save_payment_profile', $profile_data);
		if ($save_result['code']) {
			return $save_result['data']['authnet_profile_id'];
		}
		return false;
	}
	
	public function clear_billing_apc() {
		apc_delete($this->account_details_apc_key);
		apc_delete($this->account_info_apc_key);
		apc_delete($this->account_payment_profile_apc_key);
	}
	
	private function clear_values($params) {
		$value_to_return = '';
		if (is_array($params)) {
			foreach ($params as $key => $param) {
				if (empty($param) === false) { 
					$value_to_return[$key] = $this->clear_values($param);
				}
			}
			return $value_to_return;
		} else {
			return $params;
		}


	}
    
    
	private function get_fake_account_info() {
		return array(
			'items' => array(
				array(
					'AccountLineItem' => array(
						'id' => 2,
						'display_group' => '',
						'ref_name' => 'unlimited_photos',
						'better_best_group' => '',
						'order' => 1,
						'better_best_group_order' => '',
						'name' => 'Unlimited Photos',
						'current_cost' => '2.99',
						'created' => '2014-04-12 21:39:09',
						'modified' => '2014-04-18 10:58:43',
						'is_pay_fail' => false,
						'active' => '1',
						'removed_scheduled' => '0',
						'addable' => '0',
						'previous' => '0',
					),
				),
				array(
					'AccountLineItem' => array(
						'id' => 3,
						'display_group' => '',
						'ref_name' => 'basic_shopping_cart',
						'better_best_group' => '',
						'order' => 2,
						'better_best_group_order' => '',
						'name' => 'Shopping Cart',
						'current_cost' => '4.99',
						'created' => '2014-04-12 21:39:09',
						'modified' => '2014-04-18 10:58:43',
						'is_pay_fail' => false,
						'active' => '1',
						'removed_scheduled' => '0',
						'addable' => '0',
						'previous' => '0',
					),
				),
				array(
					'AccountLineItem' => array(
						'id' => 5,
						'display_group' => '',
						'ref_name' => 'page_builder',
						'better_best_group' => '',
						'order' => 3,
						'better_best_group_order' => '',
						'name' => 'Page  Builder',
						'current_cost' => '.99',
						'created' => '2014-04-12 21:39:09',
						'modified' => '2014-04-18 10:58:43',
						'is_pay_fail' => false,
						'active' => '1',
						'removed_scheduled' => '0',
						'addable' => '0',
						'previous' => '0',
					),
				),
				array(
					'AccountLineItem' => array(
						'id' => 4,
						'display_group' => '',
						'ref_name' => 'mobile_theme',
						'better_best_group' => '',
						'order' => 4,
						'better_best_group_order' => '',
						'name' => 'Mobile Theme',
						'current_cost' => '.99',
						'created' => '2014-04-12 21:39:09',
						'modified' => '2014-04-18 10:58:43',
						'is_pay_fail' => false,
						'active' => '1',
						'removed_scheduled' => '0',
						'addable' => '0',
						'previous' => '0',
					),
				),
				array(
					'AccountLineItem' => array(
						'id' => 6,
						'display_group' => '',
						'ref_name' => 'remove_fotomatter_branding',
						'better_best_group' => '',
						'order' => 5,
						'better_best_group_order' => '',
						'name' => 'Remove Fotomatter Branding',
						'current_cost' => '.99',
						'created' => '2014-04-12 21:39:09',
						'modified' => '2014-04-18 10:58:43',
						'is_pay_fail' => false,
						'active' => '1',
						'removed_scheduled' => '0',
						'addable' => '0',
						'previous' => '0',
					),
				),
				array(
					'AccountLineItem' => array(
						'id' => 7,
						'display_group' => '',
						'ref_name' => 'email_support',
						'better_best_group' => '',
						'order' => 6,
						'better_best_group_order' => '',
						'name' => 'Email Support',
						'current_cost' => '.99',
						'created' => '2014-04-12 21:39:09',
						'modified' => '2014-04-18 10:58:43',
						'is_pay_fail' => false,
						'active' => '1',
						'removed_scheduled' => '0',
						'addable' => '0',
						'previous' => '0',
					),
				),
			),
			'total_bill' => '6.97',
			'is_pay_fail' => false,
			'Account' => array(
				'id' => '534a015a-c7c4-474b-ac8d-2d5b9e3f42ff',
				'email' => 'acmorrill@gmail.com',
				'promo_credit_balance' => '0.00',
				'account_status_id' => 2,
				'account_payment_status_id' => 2,
				'next_bill_date' => '2014-05-16 22:57:34',
				'site_domain' => 'drew',
				'welcome_hash' => 'QpM01rpHimMvzZpMVvRpFi5ER0ImHZpyoczpDZ7WlTrUTRGOmye2RjHIkp52ouBNHadlakhveIp7z6VWF9YwtFfO4kRtOshvDuQNP8i3QHaqO6mtgkZKZey4zpyoSPTv',
				'first_name' => 'Andrew',
				'last_name' => 'Morrill',
				'country_id' => 223,
				'business_name' => '', 
				'industry_type_id' => 1,
				'authnet_profile_id' => 5,
				'billing_in_progress' => 0,
				'is_welcome' => 0,
				'market_source' => 'direct',
				'partner_id' => 0,
				'created' => '2014-04-12 21:15:38',
				'modified' => '2014-04-16 22:05:31',
			),
		);
	}
    
}