<?php
require_once(ROOT . '/app/tests/fototestcase.php');
class PhotoSettingTestCase extends fototestcase {
	
   public $exclude_these_tables = array("db_local_updates", "db_local_update_items", 'groups', 'groups_permissions', 'groups_users', 'hashes', 'site_one_level_menus');
 
   
    function start() {
            parent::start();
		require_once(ROOT . "/app/tests/model_helpers/photo.test.php");
		$this->helper = new PhotoTestCaseHelper();
		$this->Photo = ClassRegistry::init("Photo");

		//init cloudfiles component
		App::import("Component", "CloudFiles");
		$this->CloudFiles = new CloudFilesComponent();
		
		//init testing component
		App::import("Component", "Testing");
		$this->Testing = new TestingComponent();
    }
    
    public function test_image_container_name() {
                $result = $this->helper->check_for_container_name();
                $this->assertEqual($result, true);
                if ($result === false) {
                    debug("image container name not set.. cannot continue");
                    $this->endTest();
                    die();
                }
    }
    public function test_check_consistent_values() {
		$this->assertEqual($this->helper->check_for_consistent_values(), true);
    }
	
	public function test_large_image_should_fail() {
		$url = "http://c14354319.r19.cf2.rackcdn.com/larger_image.jpg";
		exec("cd ".TEMP_IMAGE_UNIT."; wget $url", $output, $result);
		$this->assertEqual($result, 0);
		if ($result != 0) return;
		
		$name = "larger_image.jpg";
		$photo_for_db['Photo']['cdn-filename']['tmp_name'] = TEMP_IMAGE_UNIT . "/larger_image.jpg";
		$name = $this->Testing->create_random_string(10);
		$photo_for_db['Photo']['cdn-filename']['name'] = $name . ".jpg";
		$photo_for_db['Photo']['cdn-filename']['type'] = 'image/jpeg';
		$photo_for_db['Photo']['cdn-filename']['size'] = filesize(TEMP_IMAGE_UNIT . "/larger_image.jpg");


		$photo_for_db['Photo']['display_title'] = 'Title' . $name;
		$photo_for_db['Photo']['display_subtitle'] = 'subtitle' . $name;
		$photo_for_db['Photo']['alt_text'] = 'alt text ' . $name;
		$this->Photo->create();
                
                $result_from_save = $this->Photo->save($photo_for_db);
		$this->assertEqual($result_from_save, false);
		$this->assertEqual(unlink(TEMP_IMAGE_UNIT . "/larger_image.jpg"), true);
	}
    
    public function test_download_files() {
		$this->Testing->give_me_images(2);
    }
	
	public function test_delete_cache() {
		//make sure that when I resave a photo I invalidate any cache
		$this->Testing->give_me_images(1);
		$this_photo = $this->Photo->find('first', array(
			'order'=>'Photo.created DESC'
		));
		
		$this->PhotoCache = ClassRegistry::init("PhotoCache");
		$this->PhotoCache->prepare_new_cachesize($this_photo['Photo']['id'], 200, 200);
		
		$first_cache = $this->PhotoCache->find('first', array(
			'order'=>'PhotoCache.created DESC'
		));
		
		//download a image to set as the new images
		$this->SiteSetting = ClassRegistry::init("SiteSetting");
		exec("cd ".TEMP_IMAGE_PATH."; wget ".$this->SiteSetting->getImageContainerUrl()."/".$first_cache['Photo']['cdn-filename']);
		$id_to_check = $first_cache['PhotoCache']['id'];
		unset($first_cache['PhotoCache']);
		
		$image_name = $first_cache['Photo']['cdn-filename'];
		$first_cache['Photo']['cdn-filename'] = array();
		$first_cache['Photo']['cdn-filename']['tmp_name'] = TEMP_IMAGE_PATH.DS.$image_name;
		$first_cache['Photo']['cdn-filename']['name'] = $image_name;
		$first_cache['Photo']['cdn-filename']['type'] = "image/jpeg";
		$this->Photo->create();
		$this->Photo->save($first_cache);
		$empty_image = $this->PhotoCache->find("first", array(
			'conditions'=>array(
				'PhotoCache.id'=>$id_to_check
			)
		));
		$this->assertEqual(empty($empty_image)===false, false);
	}
	
	public function test_delete_image() {
		$this->Testing->give_me_images(1);
		$this->Photo->delete($this->Photo->id);
		
		//nothing should have went wrong, check for no major errors
		$this->MajorError = ClassRegistry::init("MajorError");
		
		$this->MajorError->setDataSource('test');
		$mes = $this->MajorError->find('all');
		$this->assertEqual(empty($mes), true);
	}
	
	public function test_delete_image_rackspace_fail() {
		$this->Testing->give_me_images(1);
		$this->ServerSetting = ClassRegistry::init("ServerSetting");
		$this->ServerSetting->setVal('rackspace_api_username', 'a');
                		
		Configure::write("debug", 0);
		unset($this->Photo->CloudFiles);
                $this->Photo->delete($this->Photo->id);
                Configure::write("debug", 2);
                
                $count = $this->ServerSetting->query("select count(*) as count from major_errors");
                $this->assertEqual($count[0][0]['count'], 6);
	}
	
	public function test_save_rackspace_fail() {
		$this->MajorError = ClassRegistry::init("MajorError");
		$this->MajorError->setDataSource('test');
		$mes = $this->MajorError->find('all');
		$this->assertEqual(empty($mes), true);
		
		//make sure that when I resave a photo I invalidate any cache
		$this->Testing->give_me_images(1);
		$this_photo = $this->Photo->find('first', array(
			'order'=>'Photo.created DESC'
		));
		
		$test_images = $this->CloudFiles->list_objects("master-test");
		exec("cd ".TEMP_IMAGE_UNIT."; wget http://c13957077.r77.cf2.rackcdn.com/".$test_images[0]['name']);
		
		$this_photo['Photo']['cdn-filename'] = array();
		$this_photo['Photo']['cdn-filename']['tmp_name'] =TEMP_IMAGE_UNIT.DS.$test_images[0]['name'];
		$this_photo['Photo']['cdn-filename']['name'] = $test_images[0]['name'];
		$this_photo['Photo']['cdn-filename']['type'] = "image/jpeg";
		$this->ServerSetting = ClassRegistry::init("ServerSetting");
		$this->ServerSetting->setVal('rackspace_api_username', 'a');
		unset($this->Photo->CloudFiles);
		$this->Photo->create();
		$this->Photo->save($this_photo);
		$me = $this->MajorError->find('first', array(
			'conditions'=>array(
				'MajorError.description'=>'failed to put an object to cloud files on photo save'
			)
		));
		$this->assertEqual(empty($me), false); 
	}
	
	public function test_image_path_not_writeable() {
            $this->MajorError= ClassRegistry::init("MajorError");
            $this->MajorError->query("truncate table major_errors");
            
            //get permissions for temp path
            $file_permis = fileperms(TEMP_IMAGE_PATH);
            chmod(TEMP_IMAGE_PATH, 222);
            
            //add one file
            $this->Testing->give_me_images(1);
            
            //make sure I have a major errors
            $major_error_count = $this->MajorError->query("select count(*) as count from major_errors");
            $this->assertEqual($major_error_count[0][0]['count'] == 1, true);
            
            chmod(TEMP_IMAGE_PATH, $file_permis);
        }
}
?>