<?php
class PhotoSettingTestCase extends CakeTestCase {
	
    public $fixtures = array('app.photo', 'app.photo_gallery', 'app.photo_galleries_photo', 'app.major_error', 'app.user', 
	'app.group', 'app.permission', 'app.groups_permission', 'app.groups_user', 'app.site_setting', 'app.server_setting', 'app.photo_format',
	    'app.photo_cache');

    function start() {
		parent::start();
		require_once(ROOT . "/app/tests/model_helpers/photo.test.php");
		$this->helper = new PhotoTestCaseHelper();
		$this->Photo = ClassRegistry::init("Photo");

		//init cloudfiles component
		App::import("Component", "CloudFiles");
		$this->CloudFiles = new CloudFilesComponent();
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
		$name = $this->_create_random_string(10);
		$photo_for_db['Photo']['cdn-filename']['name'] = $name . ".jpg";
		$photo_for_db['Photo']['cdn-filename']['type'] = 'image/jpeg';
		$photo_for_db['Photo']['cdn-filename']['size'] = filesize(TEMP_IMAGE_UNIT . "/larger_image.jpg");


		$photo_for_db['Photo']['display_title'] = 'Title' . $name;
		$photo_for_db['Photo']['display_subtitle'] = 'subtitle' . $name;
		$photo_for_db['Photo']['alt_text'] = 'alt text ' . $name;
		$this->Photo->create();
		$this->assertEqual($this->Photo->save($photo_for_db), false);
		$this->assertEqual(unlink(TEMP_IMAGE_UNIT . "/larger_image.jpg"), true);
	}
    
    public function test_download_files() {
		$this->_give_me_images(2);
    }
	
	public function test_delete_cache() {
		//make sure that when I resave a photo I invalidate any cache
		$this->_give_me_images(1);
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
		$this->_give_me_images(1);
		$this->Photo->delete($this->Photo->id);
		
		//nothing should have went wrong, check for no major errors
		$this->MajorError = ClassRegistry::init("MajorError");
		$this->MajorError->setDataSource('test');
		$mes = $this->MajorError->find('all');
		$this->assertEqual(empty($mes), true);
	}
	
	public function test_delete_image_rackspace_fail() {
		$this->_give_me_images(1);
		$this->ServerSetting = ClassRegistry::init("ServerSetting");
		$this->ServerSetting->setVal('rackspace_api_username', 'a');
		
		Configure::write("debug", 0);
		unset($this->Photo->CloudFiles);
	    $this->Photo->delete($this->Photo->id);
	    Configure::write("debug", 2);
		
		//make sure we have a major error
		$this->MajorError = ClassRegistry::init("MajorError");
		$this->MajorError->setDataSource('test');
		$me = $this->MajorError->find('first', array(
			'conditions'=>array(
				'MajorError.description'=>'failed to delete object cdn-filename-forcache in photo before delete'
			)
		));
		$this->assertEqual(empty($me), false);
		
		$me = $this->MajorError->find('first', array(
			'conditions'=>array(
				'MajorError.description'=>'failed to delete object cdn-filename-forcache in photo before delete'
			)
		));
		$this->assertEqual(empty($me), false);
	}
	
	public function test_save_rackspace_fail() {
		$this->MajorError = ClassRegistry::init("MajorError");
		$this->MajorError->setDataSource('test');
		$mes = $this->MajorError->find('all');
		$this->assertEqual(empty($mes), true);
		
		//make sure that when I resave a photo I invalidate any cache
		$this->_give_me_images(1);
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
	
	public function test_smaller_than_master() {
		$this->_give_me_this('larger_than_cache.jpg');
		debug($this->Photo->findById($this->Photo->id));
	}
	
	private function _give_me_this($image_name) {
		$image = file_get_contents('http://c13957077.r77.cf2.rackcdn.com/'.$image_name);
		file_put_contents(TEMP_IMAGE_PATH . DS . $image_name, $image);
		$this->_insert_this_image(TEMP_IMAGE_PATH.DS.$image_name);
	}
	
	private function _insert_this_image($file_path) {
		list($width, $height, $type, $attr) = getimagesize($file_path);
		$path_info = pathinfo($file_path);
		
		$photo_for_db = array();
		$photo_for_db['Photo']['cdn-filename']['tmp_name'] = $file_path;
		$file_name = $path_info['filename'] . '.' . $path_info['extension'];
		$photo_for_db['Photo']['cdn-filename']['name'] = $file_name;
		$photo_for_db['Photo']['cdn-filename']['type'] = $type;
		$photo_for_db['Photo']['cdn-filename']['size'] = filesize($file_path);
		
		$photo_for_db['Photo']['display_title'] = 'Title' . $file_name;
		$photo_for_db['Photo']['display_subtitle'] = 'subtitle' . $file_name;
		$photo_for_db['Photo']['alt_text'] = 'alt text ' . $file_name;
		
		$this->Photo->create();
		$this->Photo->save($photo_for_db);
	}
	
	private function _give_me_images($number_to_process) {
		$all_objects = $this->CloudFiles->list_objects("MichelleCellPhone");
		$this->assertEqual(is_writable(TEMP_IMAGE_UNIT), true);
		
		foreach ($all_objects as $key => $picture) {
			$image = $this->CloudFiles->get_object($picture['name'], 'MichelleCellPhone');
			file_put_contents(TEMP_IMAGE_UNIT.DS.$picture['name'], $image);
			if($key == $number_to_process) break;
		}

		foreach ($all_objects as $key => $photo) {
			$this->_insert_this_image(TEMP_IMAGE_UNIT . DS . $photo['name']);
			if ($key == $number_to_process) break;
		}
		
		$test_images = scandir(TEMP_IMAGE_UNIT);
		foreach ($test_images as $image) {
			if ($image == '.' || $image == '..') continue;
			$this->assertEqual(unlink(TEMP_IMAGE_UNIT."/".$image), true);
		}
	}
    
    private function _create_random_string($length) {
        $lib = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789';
        $return = '';
        for ($i=0;$i < $length; $i++) {
            $return .= $lib[rand(0, strlen($lib)-1)];
        }
        return $return; 
    }
}
?>