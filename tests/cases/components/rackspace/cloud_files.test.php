<?php
App::import('Model', 'Article');
require_once(ROOT . '/app/tests/fototestcase.php');
class CloudFilesTestCase extends fototestcase {
    
    var $fixtures = array('app.server_setting', 'app.site_setting', 'app.major_error');
    
    public function start() {
        App::import("Component", "CloudFiles");
        $this->CloudFiles = new CloudFilesComponent();
        parent::start();
    }
        
   /* public function test_delete_extra() {
        $all_containers = $this->CloudFiles->list_containers();
        foreach ($all_containers as $b) {
          
            if (strlen($b['name']) == 10) {
                $objs = $this->CloudFiles->list_objects($b['name']);
               
                    foreach ($objs as $o) {
                        $this->assertEqual($this->CloudFiles->delete_object($o['name'], $b['name']), true);
                    }
                    $this->CloudFiles->delete_container($b['name']);
            }
        }     
    } */
    
    public function test_check_for_container_name() {
	$this->SiteSetting = ClassRegistry::init("SiteSetting");
	$image_container_name = $this->SiteSetting->getVal('image-container-name');
	if (empty($image_container_name)) {
	    debug("Warning: image-container-name does not exist.");
	}
    }
    
    public function test_list_containers() {
        //make sure the api data exists in the database
        $all_containers = $this->CloudFiles->list_containers();
        $this->assertEqual(empty($all_containers), false);
        
        //make sure it has the name, count of objects, and bytes
        $one_container = $all_containers[1];
        $this->assertEqual(isset($one_container['name']), true);
        $this->assertEqual(isset($one_container['bytes']), true);
        $this->assertEqual(isset($one_container['count']), true);
    }
   
   
    public function test_create_container() {
        for ($i=0; $i < 1; $i++) {
            $container_name = $this->_create_random_string(10);
            $this->container_names[]['container_name'] = $container_name;
            $create_result = $this->CloudFiles->create_container($container_name);
            $this->assertEqual($create_result, true);
        }
        
        //failure cases
        $big_container_name = $this->_create_random_string(101);
        $this->assertEqual($this->CloudFiles->create_container($big_container_name), false);
        
        $empty_name = '';
        $this->assertEqual($this->CloudFiles->create_container($empty_name), false);
    }
    
    public function test_list_objects() {
        $this->cellPhoneObjects = $this->CloudFiles->list_objects('MichelleCellPhone');
        $this->assertEqual(empty($this->cellPhoneObjects), false);
        $first_object = $this->cellPhoneObjects[0];
        $this->assertEqual(empty($first_object['name']), false);
        $this->assertEqual(empty($first_object['hash']), false);
        $this->assertEqual(isset($first_object['bytes']), true);
        $this->assertEqual(empty($first_object['content_type']), false);
        $this->assertEqual(empty($first_object['last_modified']), false);
    }
    
    public function test_detail_object() {
        $object_details = $this->CloudFiles->detail_object($this->cellPhoneObjects[0]['name'], 'MichelleCellPhone');
        $this->assertEqual(isset($object_details['Trans-Id']), true);
        
        //download a object
        $test_image = $this->CloudFiles->get_object($this->cellPhoneObjects[0]['name'], 'MichelleCellPhone');
        file_put_contents(TEMP_IMAGE_UNIT . DS . 'test_image', $test_image);
        
        //upload it to the container
        $upload_result = $this->CloudFiles->put_object('test-image', TEMP_IMAGE_UNIT . DS . 'test_image', 'image\jpeg');
        unlink(TEMP_IMAGE_UNIT . DS . 'test_image');
        $this->assertEqual($upload_result, true);
        
        $object_details = $this->CloudFiles->detail_object('test-image');
        $this->assertEqual(isset($object_details['Trans-Id']), true);
        
        $delete_result = $this->CloudFiles->delete_object("test-image");
        $this->assertEqual($delete_result, true);
    }
    
    public function test_list_objects_no_container() {
        $this->SiteSetting = ClassRegistry::init("SiteSetting");
        $site_container = $this->SiteSetting->find('first', array(
            'conditions'=>array(
                'SiteSetting.name'=>'image-container-name'
            )
        ));
        
        $this->assertEqual(empty($site_container), false);
        $this->SiteSetting->delete($site_container['SiteSetting']['id']);
        
        $this->cellPhoneObjects_empty = $this->CloudFiles->list_objects();
        $this->assertEqual($this->cellPhoneObjects_empty === false, true);
        
        //I should have a major error
         $this->MajorError = ClassRegistry::init("MajorError");
         $me = $this->MajorError->findByDescription("The cloud files component was called without a container name.");
         $this->assertEqual(empty($me), false);
    }
   
    public function test_list_object_default() {
        $test_image = $this->CloudFiles->get_object($this->cellPhoneObjects[0]['name'], 'MichelleCellPhone');
        file_put_contents(TEMP_IMAGE_UNIT . DS . 'test_image', $test_image);
        $upload_result = $this->CloudFiles->put_object('test-image', TEMP_IMAGE_UNIT . DS . 'test_image', 'image\jpeg');
        $this->assertEqual($upload_result, true);
        unlink(TEMP_IMAGE_UNIT . DS . 'test_image');
        
        $this->default_list = $this->CloudFiles->list_objects();
        $this->assertEqual(empty($this->default_list), false);
    }
    
    public function test_get_object_default() {
        $test_image = $this->CloudFiles->get_object($this->default_list[0]['name']);
        $this->assertEqual(empty($test_image), false);
        
        foreach ($this->default_list as $image) {
            $result = $this->CloudFiles->delete_object($image['name']);
            $this->assertEqual($result, true);
        }
    }
    
    public function test_get_object_return_false() {
        $must_be_false = $this->CloudFiles->get_object("really_great_non_existing_file");
        $this->assertEqual($must_be_false, false);
    }
    
    public function test_downloadobjects() {
        $this->assertEqual(is_writable(TEMP_IMAGE_UNIT), true);
        $tmp_images = TEMP_IMAGE_UNIT . DS . 'test_images';
        if (is_dir($tmp_images) === false) mkdir($tmp_images);
        foreach ($this->cellPhoneObjects as $key => $picture) {
            $image = $this->CloudFiles->get_object($picture['name'], 'MichelleCellPhone');
            file_put_contents($tmp_images.DS.$picture['name'], $image);
            if($key == 10) break;
        }
    }
    
    public function test_putobjects() {
        $this->downloaded_files = scandir(TEMP_IMAGE_UNIT . DS . 'test_images');
        foreach ($this->downloaded_files as $picture) {
            if ($picture == '.' || $picture == '..') continue;
            $random_container = rand(0,0);
            $file_path = TEMP_IMAGE_UNIT . DS . 'test_images' . DS . $picture;
            $result = $this->CloudFiles->put_object($picture, $file_path, 'image/jpeg', $this->container_names[$random_container]['container_name']);
            $this->assertEqual($result, true);
            $this->container_names[$random_container]['files'][] = $picture;
        }
    }
    
    public function test_putobjects_return_false() {
        touch(TEMP_IMAGE_UNIT . DS . 'test_images' . DS . 'test');
        $result = $this->CloudFiles->put_object("NA", TEMP_IMAGE_UNIT . DS . 'test_images' . DS . 'test', 'image/jpeg', 'invalid-container');
        unlink(TEMP_IMAGE_UNIT . DS . 'test_images' . DS . 'test');
        $this->assertEqual($result, false);
    }
     
    public function test_objects_are_there() {
        foreach ($this->container_names as $container) {
            $all_files = $this->CloudFiles->list_objects($container['container_name']);
            foreach ($all_files as $file) {
                $this->assertEqual(in_array($file['name'], $container['files']), true);
		$this->assertEqual('image/jpeg', $file['content_type']);
            }
        }
    }
    
    public function test_delete_return_false() {
        $result = $this->CloudFiles->delete_object("non_existent_file");
        $this->assertEqual($result, false);
    }
    
    public function test_delete_objects() {
		$tmp_images = TEMP_IMAGE_UNIT . DS . 'test_images';
			foreach ($this->container_names as $container) {
				foreach ($container['files'] as $file) {
					$this->CloudFiles->delete_object($file, $container['container_name']);
					unlink($tmp_images . DS . $file);
				}
			}
		if (is_dir($tmp_images)) rmdir($tmp_images);
    }
    
    public function test_delete_container() {
        foreach ($this->container_names as $container) {
            $result = $this->CloudFiles->delete_container($container['container_name']);
            $this->assertEqual($result, true);
        }
        $empty_name = '';
        $this->assertEqual($this->CloudFiles->delete_container($empty_name), false);
    } 
    
    public function test_cdn_list() {
        $this->cdn_list = $this->CloudFiles->cdn_list_containers();
        $this->assertEqual(empty($this->cdn_list), false);
        $ce = $this->cdn_list[0];
        $this->assertEqual(empty($ce['cdn_streaming_uri']), false);
        $this->assertEqual(empty($ce['cdn_uri']), false);
        $this->assertEqual(empty($ce['cdn_ssl_uri']), false);
        $this->assertEqual(empty($ce['cdn_enabled']), true);
        $this->assertEqual(empty($ce['ttl']), false);
        $this->assertEqual(empty($ce['name']), false);
    }
    
    public function test_cdn_detail_container() {
        $cdn_detail = $this->CloudFiles->cdn_detail_container();
        $this->assertEqual(empty($cdn_detail), false);
        
        $cdn_detail = $this->CloudFiles->cdn_detail_container('MichelleCellPhone');
        $this->assertEqual(empty($cdn_detail), false);
    }
    
    /*public function test_cdn_enable() {
        $test_name = $this->_create_random_string(10);
        $result = $this->CloudFiles->create_container($test_name);
        $this->assertEqual($result, true);
        
        //$result = $this->CloudFiles->cdn_enable_container($test_name);
       // debug($result);
        
        
        $delete_result = $this->CloudFiles->delete_container($rest_name);
        $this->assertEqual($delete_result, true);
        
    } */
    
    private function _create_random_string($length) {
        $lib = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789';
        $return = '';
        for ($i=0;$i < $length; $i++) {
            $return .= $lib[rand(0, strlen($lib)-1)];
        }
        return $return;
        
    }
     
}