<?php
class FotoMatterOverlordApi extends Object {
	
	protected function send_api_request($api, $params=array()) {
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
		// DREW TODO - make this request over SSL
		curl_setopt($ch, CURLOPT_URL , $url_to_use);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		//curl_setopt($ch, CURLINFO_HEADER_OUT, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
			'Content-Type: application/json',
			'API_SIGNATURE: '.$request['Access']['signature']
		));
		$json_response = curl_exec($ch);
		
//		$curl_info = curl_getinfo($ch);
//		$this->log($curl_info, 'curl_info');
		
		if ($json_response === false) {
			$curl_error = curl_error($ch);
			$this->MajorError = ClassRegistry::init('MajorError');
			$this->MajorError->major_error('api call to overlord failed', compact('url_to_use', 'request', 'curl_error'), 'high');
		}
		curl_close($ch);
		
		$response = json_decode($json_response, true);
		if (isset($response['code']) && $response['code'] != 1) {
			$this->MajorError = ClassRegistry::init('MajorError');
			$this->MajorError->major_error('api call to overlord returned with fail code', compact('url_to_use', 'request', 'json_response', 'response'), 'high');
		}
		
		return $response;
	}
}