<?php

class DbGlobalUpdateItem extends AppModel {
	public $useDbConfig = 'server_global';
	public $useTable = "db_global_update_items";
	
	public $belongsTo = array('DbGlobalUpdate');
}