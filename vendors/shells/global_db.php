<?php

require_once 'generic_db.php';


class GlobalDbShell extends GenericDbShell {
	// NOTE: don't put a $uses here -- as it can break db updates and install - instead use ClassRegistry::init() just before you need the model
	// $uses = array();
	
	function main() {
		$this->help();
	}
	function help() {

		$this->out("
	
	db reset
		-- reset the global database by first deleting the global tables
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
		
		$this->_reset(true);
		
		$this->out(" ");
		$this->out(" ");
		$this->hr();
		$this->out("Global database installed successfully. Now running 'update'");
		$this->hr();
		$this->update();
	}
	
	
	public function update() {
		Configure::write('Cache.disable', true);
		$this->_connect_db();

		$this->_update(true);
	}
	


	

}




