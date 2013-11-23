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
    public function check_availability($domain_name) {
        $domain_parts = explode('.', $domain_name);
        $keyword = '';
        $tld = '';
		if (count($domain_parts) == 3) {
			$keyword = $domain_parts[1];
            $tld = $domain_parts[2];
		} else if (count($domain_parts) == 2) {
			$keyword = $domain_parts[0];
            $tld = $domain_parts[1];
		} else {
			$keyword = $domain_parts[0];
			$tld = 'com';
		}

        //$tld = '.' . $tld;
        $api_args = array(
            "keyword"=>$keyword,
            'tld'=>array(
                $tld
            ),
            'services'=>array(
                'availability'
            )
        );
        $api_results = json_decode($this->_send_request("/api/domain/check", 'POST', $api_args), true);
		return $api_results['domains'];
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
    public function buy_domain($account_id, $domain) {
        if ($this->check_availability($domain) === false) {
            return false;
        }
        
        //find the account thats going to buy the domain
        $this->Account = ClassRegistry::init("Account");
        $buying_account = $this->Account->find('first', array(
            'conditions'=>array(
                'Account.id'=>$account_id
            )
        ));
        
        //return false if the account can't be found
        if (empty($buying_account)) {
            return false;
        }
        
        
        
        //formulate the api call
        $order = array(
            'order_type'=>'domain/create',
            'domain_name'=>$domain,
            'nameservers'=>$this->_dns_servers,
            'contacts'=>array(
                'type'=>array(
                    'registrant','administrative','billing','technical'
                ),
                'first_name'=>$buying_account['Account']['first_name'],
                'last_name'=>$buying_account['Account']['last_name'],
                'organization'=>$buying_account['Account']['site_domain'],
                'address_1'=>$buying_account['Account']['address1'],
                'address_2'=>$buying_account['Account']['address2'],
                'city'=>$buying_account['Account']['city'],
                'state'=>$buying_account['Account']['state'],
                'email'=>$buying_account['Account']['email'],
                'phone'=>'2083532813', //TODO fix this
                'fax'=>'2083532813',
                'country'=>$buying_account['Account']['country']
            ),
            'period'=>1,
        );
        $api_args['items'][] = $order;
        $api_results = json_decode($this->_send_request("/api/order", "POST", $api_args), true);
        if ($api_results['result']['code'] != '100') {
            return false;
        }
        
        //add the domain to rackspace dns
        App::uses('CloudDomains', 'Lib/rackspace');
        $this->CloudDomains = new CloudDomains();
        if ($this->CloudDomains->add_domain($domain, $buying_account['Account']['email'], $this->_ttl_to_use) == false) {
            $this->Account->major_error('failed to park domain ('.$domain.') after buying it', $buying_account);
            return false;
        }
        
        //insert db record
        $this->AccountDomain = ClassRegistry::init("AccountDomain");
        $ad['AccountDomain'] = array(
            'account_id'=>$buying_account['Account']['id'],
            'url'=>$domain,
            'ttl'=>$this->_ttl_to_use
        );
        $this->AccountDomain->create();
        //Adam todo theses will need to be deleted if the account is deleted
        if ($this->AccountDomain->save($ad) == false) {
            $this->AccountDomain->major_error('failed to save Accountdomain record after buying domain('.$domain.')', $buying_account);
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
       $api_result = $this->Http->request(array(
          'method'=>$request_type,
           'uri'=>$this->_api_url .  $api_call,
           'body'=>json_encode($request_args),
           'header'=>array(
               'Api-Username'=>$this->_account,
               'Api-Token'=>$this->_api_token
           )
       ));
	   
       return $api_result;
    }
   
}
