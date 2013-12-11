<?php
class FotomatterBillingComponent extends Object {
    
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
    
     private function send_api_request($api, $params=array()) {
		$request['Request']['data'] = $this->clear_values($params);
        
        $url_to_use = $this->server_url . '/' .$api;
        $request['Request']['Server_params']['url'] = $url_to_use;
        $time_stamp = (string) time();
        $request['Request']['Server_params']['time_stamp'] = $time_stamp;
        
        $this->SiteSetting = ClassRegistry::init("SiteSetting");
        $site_key = $this->SiteSetting->getVal('site_domain');
        $request['Request']['key'] = $site_key;
		$request['Access']['signature'] = hash_hmac('sha256', json_encode($request['Request']), OVERLORD_API_KEY);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL , $url_to_use);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
			'Content-Type: application/json',
			'API_SIGNATURE: '.$request['Access']['signature']
		));
		$response = curl_exec($ch);
		curl_close($ch);
		$this->log($response, 'add_profile');
				
   //     $this->log($request['Request'], 'client_billing');
		return $response;
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