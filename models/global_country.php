<?php

class GlobalCountry extends AppModel {
    public $name = 'GlobalCountry';
	public $useDbConfig = 'server_global';
	public $useTable = "countries";
	
	public $hasMany = array(
		'GlobalState',
	);
	
}
