<?php
class ServerImagesTestCase extends CakeTestCase {
    function test_server_image_list() {
        App::import("Component", "ServerImages");
        $imageobj = new ServerImagesComponent();
	$result = $imageobj->list_images();
	$this->assertEqual(empty($result), false);
    }
}

?>
