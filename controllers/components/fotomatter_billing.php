<?php
require_once(ROOT . DS . 'app' . DS. 'controllers' . DS . 'components' . DS . 'fotomatter_overlord_api.php');
class FotomatterBillingComponent extends FotoMatterOverlordApi {
	// DREW TODO this should be ssl
	
	public $account_on_off_apc_key;
	public $account_price_apc_key;
    
	public function __construct() {
		$this->server_url = Configure::read('OVERLORD_URL');
		$this->account_on_off_apc_key =  'account_on_off_'.$_SERVER['local']['database'];
		$this->account_price_apc_key =  'account_price_'.$_SERVER['local']['database'];
	}
	
	public function remove_item($line_item_id) {
		apc_delete($this->account_on_off_apc_key);
		$result = json_decode($this->send_api_request('api_billing/remove_item', array('line_item_id'=>$line_item_id)), true);
		return $result;
	}
	
	public function undo_cancellation($line_item_id) {
		apc_delete($this->account_on_off_apc_key);
		$result = json_decode($this->send_api_request('api_billing/undo_cancellation', array('line_item_id'=>$line_item_id)), true);
		return $result;
	}
    
	public function getAccountDetails() {
		$details = json_decode($this->send_api_request('api_billing/get_account_details', array()), true);
		return $details;
	}
    
	public function getPaymentProfile() {
		$result = json_decode($this->send_api_request('api_billing/get_payment_profile', array()), true);
		return $result;
	}
    
	public function makeAccountChanges($changes) {
		apc_delete($this->account_on_off_apc_key);
		$result = json_decode($this->send_api_request('api_billing/makeAccountChanges', $changes), true);
		if ($result['code']) {
			return true;
		}
		return false;
	}
    
	public function save_payment_profile($profile_data) {
		apc_delete($this->account_on_off_apc_key);
		$save_result = json_decode($this->send_api_request('api_billing/save_payment_profile', $profile_data), true);
		if ($save_result['code']) {
			return $save_result['data']['authnet_profile_id'];
		}
		return false;
	}
    
	public function get_current_on_off_features() {
		if (apc_exists($this->account_on_off_apc_key)) {
			return apc_fetch($this->account_on_off_apc_key);
		}
		
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
		apc_store($this->account_on_off_apc_key, $formatted_current_on_off_features, 10800); // 3 hours
		
		return $formatted_current_on_off_features;
	}
	
	public function get_current_feature_pricing() {
		if (apc_exists($this->account_price_apc_key)) {
			return apc_fetch($this->account_price_apc_key);
		}
		
		$account_info = $this->get_account_info();
		if ($account_info === false) {
			return false;
		}
		$formatted_current_pricing = Set::combine($account_info['items'], '{n}.AccountLineItem.ref_name', '{n}.AccountLineItem');
		foreach ($formatted_current_pricing as $key => &$formatted_current_price) {
			$formatted_current_price = $formatted_current_price['current_cost'];
		}
		apc_store($this->account_price_apc_key, $formatted_current_pricing, 10800); // 3 hours
		
		return $formatted_current_pricing;
	}
	
	public function get_account_info($params = array()) {
		$result_of_find = json_decode($this->send_api_request('api_billing/get_account_info', $params), true);
		if($result_of_find['code']) {
			return $result_of_find['payload'];
		}

		$this->MajorError = ClassRegistry::init("MajorError");
		$this->MajorError->major_error('Remote find from overlord returned with error.', $params);
		return false;
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