<?php
class ServerToolsTestCase extends CakeTestCase {
    
    function test_server_list() {
        App::import("Component", "ServerTools");
        $imageobj = new ServerToolsComponent();
	$result = $imageobj->list_servers();
        $this->assertEqual(empty($result), false);
        
    }

}

?>