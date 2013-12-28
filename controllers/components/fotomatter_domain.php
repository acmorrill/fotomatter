<?php
/**
 * Description of fotomatter_domain
 *
 * @author aholsinger
 */
class FotomatterDomainComponent extends Object {
	
	private $_account = 'adamdude828-ote';
    
    private $_api_token = '07dc29fd052cf2d92413feaae9fea17d3967ccb5';
    
    private $_api_url = 'https://api.dev.name.com';
    
    private $_dns_servers = array(
        'dns1.fotomatter.net',
        'dns2.fotomatter.net'
    );
    
    private $_ttl_to_use = 3600;
   
    public function __construct() {
		App::import('Core', 'HttpSocket');
		$this->Http = new HttpSocket();
        
		//Adam Todo test real api
        if (false && Configure::read('debug') == 0) {
            $this->_account = 'adamdude828';
            $this->_api_token = '26580a43ea7e072288446b67f15af073e53aeab6';
            $this->_api_url = 'https://api.name.com';
            $this->_dns_servers = array(
                'dns1.fotomatter.net',
                'dns2.fotmatter.net'
            );
        } else {
            $this->_account = "adamdude828-ote";
            $this->_api_token = "07dc29fd052cf2d92413feaae9fea17d3967ccb5";
            $this->_api_url = 'https://api.dev.name.com';
            $this->_dns_servers = array(
                'ns1.name.com',
                'ns2.name.com'
            );
        }
    }
    
    /**
     * This funciton is used to check the availabiliity to check a single domain name
     *  
     * * @param type $domain_name The domain to check
     * 
     * If you pass a domain formated as str1.str2.com then it will check str2 (basically it just assumes that this is a subdomain and you really want to know about str2
     * 
     * VS
     * 
     * If you pass a domain formated as str2.com it will also check str2 
     * 
     * @return bool true of false depending on domain availability
     * 
     */
    public function check_availability($domainObj) {
        $api_args = array(
            "keyword"=>$domainObj['name'],
            'tld'=>array(
                $domainObj['tld']
            ),
            'services'=>array(
                'availability'
            )
        );
        $api_results = json_decode($this->_send_request("/api/domain/check", 'POST', $api_args), true);
		foreach($api_results['domains'] as $domain_name => $domain_info) {
			if ($domain_name == $api_args['keyword']) {
				if ($domain_info['avail']) {
					return true;
				}
			}
		}
		return false;
    }
    
    /**
     * renew the domain for one year
     * @param type $domain
     */
    public function renew_domain($domain) {
        $order = array(
            'order_type'=>'domain/renew',
            'domain_name'=>$domain,
            'period'=>'1',
        );
        $api_args['items'][] = $order;
        $api_results = json_decode($this->_send_request("/api/order", "POST", $api_args), true);
        if ($api_results['result']['code'] == '100') {
            return true;
        }
        return false;
    }
    
    /**
     * buy the following domain, if its avilable, and then save it associated to the account id passed
     * @param account_id Id of the account that will own the domain
     * @param the domain that we should attempt to buy
     */
    public function buy_domain($contact_info, $domainObj) {
        if ($this->check_availability($domainObj) === false) {
            return false;
        }
		
		$this->SiteSetting = ClassRegistry::init("SiteSetting");
		$site_email = $this->SiteSetting->getVal('account_email', '');
		
        //formulate the api call
        $order = array(
            'order_type'=>'domain/create',
            'domain_name'=>$domainObj['name'],
            'nameservers'=>$this->_dns_servers,
            'contacts'=>array(
                'type'=>array(
                    'registrant','administrative','billing','technical'
                ),
                'first_name'=>$contact_info['first_name'],
                'last_name'=>$contact_info['last_name'],
                'organization'=>empty($contact_info['organization']) == false ? $contact_info['organization'] : '',
                'address_1'=>$contact_info['address_1'],
                'address_2'=>empty($contact_info['address_2']) == false ? $contact_info['address_2'] : '',
                'city'=>$contact_info['city'],
                'state'=>$contact_info['country_state_id'],
                'email'=>$site_email,
                'phone'=>$contact_info['phone'], //TODO fix this
                'fax'=>empty($contact_info['fax']) == false ? $contact_info['fax'] : '',
                'country'=>$contact_info['country_id']
            ),
            'period'=>1,
        );
        $api_args['items'][] = $order;
	
        $api_results = json_decode($this->_send_request("/api/order", "POST", $api_args), true);
		
        if ($api_results['result']['code'] != '100') {
            return false;
        }
		return true;
    }
    
    
    /**
     * @param String api_call String that represents the attempted api action.. (example /api/domain/create)
     * @param String request_type GET or POST
     * @param request_args array Parameter required for api call.. see docs here https://www.name.com/files/name_api_documentation.pdf
     */
    private function _send_request($api_call, $request_type='GET', $request_args) {
		$this->log($this->_api_url . $api_call, 'domain_log');
		$this->log($request_args, 'domain_log');
       $api_result = $this->Http->request(array(
          'method'=>$request_type,
           'uri'=>$this->_api_url .  $api_call,
           'body'=>json_encode($request_args),
           'header'=>array(
               'Api-Username'=>$this->_account,
               'Api-Token'=>$this->_api_token
           )
       ));
	   $this->log($api_result, 'domain_log');
	   
       return $api_result;
    }
   
}
