<?php

class GlobalCountryState extends AppModel {
    public $name = 'GlobalCountryState';
	public $useDbConfig = 'server_global';
	public $useTable = "country_states";
	
	public $belongsTo = array(
		'GlobalCountry',
	);

	public function get_states_by_country($country_id) {
		$apc_key = 'global_country_state_option_html_'.$country_id;
//		apc_clear_cache('user');
		if (apc_exists($apc_key)) {
			$states = apc_fetch($apc_key);
		} else {
			$states = $this->find('all', array(
				'conditions' => array(
					'GlobalCountryState.country_id' => $country_id
				),
				'contain' => false,
			));
			
			apc_store($apc_key, $states, 60*60*24*7); // store for one week
		}
		
		return $states;
	}
	
	public function get_state_id_by_name($state_name) {
		$state_data = $this->find('first', array(
			'conditions' => array(
				'GlobalCountryState.state_name' => $state_name,
			),
			'contain' => false,
		));
		
		if (!empty($state_data['GlobalCountryState']['id'])) {
			return $state_data['GlobalCountryState']['id'];
		} else {
			return false;
		}
	}
	
	public function get_state_name_by_id($state_id) {
		$state_data = $this->find('first', array(
			'conditions' => array(
				'GlobalCountryState.id' => $state_id,
			),
			'contain' => false,
		));
		
		if (!empty($state_data['GlobalCountryState']['state_name'])) {
			return $state_data['GlobalCountryState']['state_name'];
		} else {
			return false;
		}
	}
}
