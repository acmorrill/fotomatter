<?php
class FotomatterDomainManagementComponent extends Object {
	
	public function __construct() {
        $this->server_url = Configure::read('OVERLORD_URL');
    }
	
	public function setupDomain($domain) {
		$api_result = json_decode($this->send_api_request("api_domain/setup", $domain), true);
		$this->log($api_result, 'domain_log');
		
		if (empty($api_result['data']['AuthnetDomainOrder']['id']) === false) {
			return true;
		}
		return $api_result['data']['AuthnetDomainOrder']['id'];
	}
	
	public function charge_domain($domain) {
		$api_result = json_decode($this->send_api_request("api_domain/charge_domain", $domain), true);
		
		if (empty($api_result['data']['AuthnetDomainOrder']['id']) === false) {
			return true;
		}
		return $api_result['data']['AuthnetDomainOrder']['id'];
	}
	
	private function send_api_request($api, $params=array()) {
		$request['Request']['data'] = $params;
        
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
		return $response;
    }
	
}