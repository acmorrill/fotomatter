<?php
require_once(ROOT . DS . 'app' . DS. 'controllers' . DS . 'components' . DS . 'fotomatter_overlord_api.php');
class FotomatterBillingComponent extends FotoMatterOverlordApi {
	// DREW TODO this should be ssl
	
	public $account_details_apc_key;
	public $account_info_apc_key;
	public $account_payment_profile_apc_key;
    
	public function __construct() {
		$this->server_url = Configure::read('OVERLORD_URL');
		$this->account_details_apc_key =  'account_details_'.$_SERVER['local']['database'];
		$this->account_info_apc_key =  'account_info_'.$_SERVER['local']['database'];
		$this->account_payment_profile_apc_key =  'account_payment_profile_'.$_SERVER['local']['database'];
	}
	
	public function getAccountDetails() {
		if (apc_exists($this->account_details_apc_key)) {
			return apc_fetch($this->account_details_apc_key);
		}
		$details = json_decode($this->send_api_request('api_billing/get_account_details', array()), true);
		apc_store($this->account_details_apc_key, $details, 10800); // 3 hours
		return $details;
	}
    
	public function getPaymentProfile() {
		if (apc_exists($this->account_payment_profile_apc_key)) {
			return apc_fetch($this->account_payment_profile_apc_key);
		}
		$result = json_decode($this->send_api_request('api_billing/get_payment_profile', array()), true);
		apc_store($this->account_payment_profile_apc_key, $result, 10800); // 3 hours
		return $result;
	}
    
	public function get_account_info($params = array()) {
		if (apc_exists($this->account_info_apc_key)) {
			return apc_fetch($this->account_info_apc_key);
		}
		
		$result_of_find = json_decode($this->send_api_request('api_billing/get_account_info', $params), true);
		if($result_of_find['code']) {
			apc_store($this->account_info_apc_key, $result_of_find['payload'], 10800); // 3 hours
			return $result_of_find['payload'];
		}

		$this->MajorError = ClassRegistry::init("MajorError");
		$this->MajorError->major_error('Remote find from overlord returned with error.', $params);
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
		$result = json_decode($this->send_api_request('api_billing/remove_item', array('line_item_id'=>$line_item_id)), true);
		return $result;
	}
	
	public function undo_cancellation($line_item_id) {
		$this->clear_billing_apc();
		$result = json_decode($this->send_api_request('api_billing/undo_cancellation', array('line_item_id'=>$line_item_id)), true);
		return $result;
	}
	
	public function makeAccountChanges($changes) {
		$this->clear_billing_apc();
		$result = json_decode($this->send_api_request('api_billing/makeAccountChanges', $changes), true);
		if ($result['code']) {
			return true;
		}
		return false;
	}
    
	public function save_payment_profile($profile_data) {
		$this->clear_billing_apc();
		$save_result = json_decode($this->send_api_request('api_billing/save_payment_profile', $profile_data), true);
		if ($save_result['code']) {
			return $save_result['data']['authnet_profile_id'];
		}
		return false;
	}
	
	private function clear_billing_apc() {
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
    
    
    
}