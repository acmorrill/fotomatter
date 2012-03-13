<?php

class DbGlobalUpdate extends AppModel {
	public $useDbConfig = 'server_global';
	public $useTable = "db_global_updates";
	
	public $hasMany = array('DbGlobalUpdateItem');
	

}