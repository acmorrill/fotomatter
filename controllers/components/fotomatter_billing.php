<?php
require_once(ROOT . DS . 'app' . DS. 'controllers' . DS . 'components' . DS . 'fotomatter_overlord_api.php');
class FotomatterBillingComponent extends FotoMatterOverlordApi {
    
   // public $server_url = 'https://overlord.fotomatter.net';
    //
    //public $server_url = 'http://local.fotomatter.net';
    //adam TODO this should be ssl
    
	public function __construct() {
		$this->server_url = Configure::read('OVERLORD_URL');
	}
	
	public function remove_item($line_item_id) {
		$result = json_decode($this->send_api_request('api_billing/remove_item', array('line_item_id'=>$line_item_id)), true);
		return $result;
	}
	
	public function undo_cancellation($line_item_id) {
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
		$this->log($changes, 'makeAccountChanges');
		$result = json_decode($this->send_api_request('api_billing/makeAccountChanges', $changes), true);
		if ($result['code']) {
			return true;
		}
		return false;
	}
    
	public function save_payment_profile($profile_data) {
		$save_result = json_decode($this->send_api_request('api_billing/save_payment_profile', $profile_data), true);
		if ($save_result['code']) {
			return $save_result['data']['authnet_profile_id'];
		}
		return false;
	}
    
	public function get_info_account($params=array()) {
		$result_of_find = json_decode($this->send_api_request('api_billing/get_info_account', $params), true);

		if($result_of_find['code']) {
			return $result_of_find['payload'];
		}

		$this->MajorError = ClassRegistry::init("MajorError");
		$this->MajorError->major_error('Remote find from overlord returned with error.', $params);
		return false;
		//overlord returned an error so store it
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