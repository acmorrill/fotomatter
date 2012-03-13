<?php

require_once 'generic_db.php';


class DbShell extends GenericDbShell {
	// NOTE: don't put a $uses here -- as it can break db updates and install - instead use ClassRegistry::init() just before you need the model
	// $uses = array();
	
	function main() {
		$this->help();
	}
	function help() {

		$this->out("
	
	db reset
		-- checks to make sure the global schema is installed
			-- if not then the global schema reset is called first
		-- reset the local database by first deleting the local tables
		-- next it installs the latest schema and then runs update
		
	db update
	    -- run all the updates that have been added since the last schema that ran
		-- if the last update failed it will fail again if it was an sql file
		-- and will try the update again if it was a php file
		
");
		
	}
	
	/////////////////////////////////////////////////////////////////
	// shell main functions
	////////////////////////////////////////////////////////////////
	public function reset() {
		Configure::write('Cache.disable', true);
		$this->_connect_db();
		
		// check to see if the global db is setup
		// if not then install and update it first
		if (!$this->_global_db_installed()) {
			$this->_reset(true);
			$this->_update(true);
		}
		$this->_reset(false);
		
		$this->out(" ");
		$this->out(" ");
		$this->hr();
		$this->out("Local database installed successfully. Now running 'update'");
		$this->hr();
		$this->update();
	}
	
	
	public function update() {
		Configure::write('Cache.disable', true);
		$this->_connect_db();

		
		$this->_update(true);		
		$this->_update(false);		
	}
	

	

}




