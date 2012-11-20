<?php
class BuildShell extends Shell {
    
    /**
     * start build process
     * @preconditions
     * 1. accounts directory will be created
     * 2. Symlink will be created from domains directory to accounts
     * 3. dns has already been taken care of.
     */
    public function now() {
	//so the idea is that customer mangement will initiate this process, and pass the root password,
	//check to make sure it works
	if (empty($this->params[0]) === false) {
	    $this->error("Please provide root mysql password.");
	    exit(1);
	}
	
	$mysql_connection_resource = @mysql_connect("localhost", "root", $this->params[0]);
	if ($mysql_connection_resource === false) {
	    $this->error("Mysql root password was provided but, could not connect");
	    exit(1);
	}
	
	
	
	
	
	
	
	
	
    }
    
    
}