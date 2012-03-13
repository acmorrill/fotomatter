<?php

class DrewShell extends Shell {
	public $uses = array('SiteSetting', 'Photo');
	
	function main() {
		$this->SiteSetting->setVal('image-container-url', 'http://c9134086.r86.cf2.rackcdn.com/');
		$this->SiteSetting->setVal('image-container-secure_url', 'https://c9134086.ssl.cf2.rackcdn.com/');
		$this->SiteSetting->setVal('image-container-name', 'andrew-dev-container');
	} 
	
	function start_over()   {
		$this->delete_photo();
		$this->remove_all_objects();
	}
	
	
	public function list_cloudfiles() {
		App::import("Component", "CloudFiles");
        $this->files = new CloudFilesComponent();
		
		debug($this->files->list_objects());
	}
	
	function delete_photo() {
		$this->Photo->deleteAll(array("1=1"), true, true);
	}
	
	function remove_all_objects() {
		App::import("Component", "CloudFiles");
        $this->files = new CloudFilesComponent();
		
		$all_objects = $this->files->list_objects();
		
		foreach ($all_objects as $all_object) {
			//$this->files->delete_object($all_object['name']);
			//print_r($all_object);
		}
	}
	
	
}