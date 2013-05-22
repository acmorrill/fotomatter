<?php

class GlobalCountry extends AppModel {
    public $name = 'GlobalCountry';
	public $useDbConfig = 'server_global';
	public $useTable = "countries";
	
	public $hasMany = array(
		'GlobalCountryState',
	);
        
        public function get_available_countries() {
            $apc_key = 'available_countries';
            if (apc_exists($apc_key)) {
                    $countries = apc_fetch($apc_key);
            } else {
                    $this->GlobalCountry = ClassRegistry::init("GlobalCountry");

                    $countries = $this->GlobalCountry->find('all', array(
                            'contain' => false
                    ));
                    apc_store($apc_key, $countries, 60*60*24*7); // store for one week
            }

            return $countries;
        }
        
        
	
}
