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
		$all_objects = $this->CloudFiles->list_objects("MichelleCellPhone");
		$this->assertEqual(is_writable(TEMP_IMAGE_UNIT), true);
			$tmp_images = TEMP_IMAGE_UNIT . DS . 'test_images';
			if (is_dir($tmp_images) === false) mkdir($tmp_images);
			foreach ($all_objects as $key => $picture) {
				$image = $this->CloudFiles->get_object($picture['name'], 'MichelleCellPhone');
				file_put_contents($tmp_images.DS.$picture['name'], $image);
				if($key == 10) break;
			}

		foreach ($all_objects as $key => $photo) {
			$photo_for_db['Photo']['cdn-filename']['tmp_name'] = $tmp_images . DS . $photo['name'];
			$name = $this->_create_random_string(10);
			$photo_for_db['Photo']['cdn-filename']['name'] = $name . ".jpg";
			$photo_for_db['Photo']['cdn-filename']['type'] = 'image/jpeg';
			$photo_for_db['Photo']['cdn-filename']['size'] = filesize($photo_for_db['Photo']['cdn-filename']['tmp_name']);


			$photo_for_db['Photo']['display_title'] = 'Title' . $name;
			$photo_for_db['Photo']['display_subtitle'] = 'subtitle' . $name;
			$photo_for_db['Photo']['alt_text'] = 'alt text ' . $name;



			$this->Photo->create();
			$this->Photo->save($photo_for_db);
			if ($key == 2) break;
		}
		
		$test_images = scandir($tmp_images);
		foreach ($test_images as $image) {
			if ($image == '.' || $image == '..') continue;
			$this->assertEqual(unlink($tmp_images."/".$image), true);
		}
		rmdir($tmp_images);
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