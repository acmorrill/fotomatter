<?php
class FotomatterBillingComponent extends Object {
    
    public $server_url = 'http://overlord.local.net';
    //adam TODO this should be ssl
    
    public $shared_secret = 'baYMbSR0EM0REmSheFHc0Qo2RUmEGoToNFnPWFcyAEUYRlaOgSynnI1F9DyI';
    
    public function remote_find($params) {
        $response = $this->send_api_request('billing_api/remote_find', $params);
        debug($response);
        die();
    }
    
     private function send_api_request($api, $params=array()) {
        App::import('Core', 'HttpSocket');
	$HttpSocket = new HttpSocket();
	
	$request['Request']['data'] = $this->clear_values($params);
        
        $url_to_use = $this->server_url . '/' .$api;
        $request['Request']['Server_params']['url'] = $url_to_use;
        $time_stamp = (string) time();
        $request['Request']['Server_params']['time_stamp'] = $time_stamp;
        
	$request['Access']['signature'] = hash_hmac('sha256', json_encode($request['Request']), $this->shared_secret);
	$response = $HttpSocket->request(array(
            'body'=>$request,
	    'method'=>'POST',
	    'uri'=>$url_to_use,
	    'auth'=>array(
		'billing'=>'GlAxUMNy5upK97MKZk4C'
	    )
	));
	return $response;
    }
    
    private function clear_values($params) {
        $value_to_return;
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