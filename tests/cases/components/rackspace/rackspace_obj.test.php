<?php
class RackspaceTestCase extends CakeTestCase {
	
	function testSetUp() {
		//can't instantiate rackspace obj directly as its not a component
		App::Import("Component", "ServerImages");
		$this->server_images = new ServerImagesComponent();
		$this->assertEqual($this->server_images->test_authenticate(), true);	
	}
}

?>
