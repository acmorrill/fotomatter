<?php
/**
 *  Contains multi purpose testing functions, utility functions and for inserting data 
 */
class TestingComponent extends Object {
	
	public function __construct() {
		App::import("Component", "CloudFiles");
		$this->CloudFiles = new CloudFilesComponent();
		$this->Photo = ClassRegistry::init("Photo");
	}
	
	public function give_me_this($image_name, $container_url='http://d7d33ce07e5a4dde758f-907816caf88b83a66c02c54765504ae9.r33.cf2.rackcdn.com') {
		//master test container
		$image = file_get_contents($container_url . '/'.$image_name);
		file_put_contents(TEMP_IMAGE_PATH . DS . $image_name, $image);
		$this->_insert_this_image(TEMP_IMAGE_PATH.DS.$image_name);
	}
	
	public function give_me_images($number_to_process) {
		$all_objects = $this->CloudFiles->list_objects("MichelleCellPhone");
		
		foreach ($all_objects as $key => $picture) {
			$image = $this->CloudFiles->get_object($picture['name'], 'MichelleCellPhone');
			file_put_contents(TEMP_IMAGE_UNIT.DS.$picture['name'], $image);
			if($key == ($number_to_process - 1)) break;
		}

		foreach ($all_objects as $key => $photo) {
			$this->_insert_this_image(TEMP_IMAGE_UNIT . DS . $photo['name']);
			if($key == ($number_to_process - 1)) break;
		}
		
		$test_images = scandir(TEMP_IMAGE_UNIT);
		foreach ($test_images as $image) {
			if ($image == '.' || $image == '..') continue;
			unlink(TEMP_IMAGE_UNIT."/".$image);
		}
	}

	public function give_me_gallery($number_to_process) {
		$this->PhotoGallery = ClassRegistry::init("PhotoGallery");
		for ($i=0; $i < $number_to_process; $i++) {
			$name = $this->create_random_string(10);
			$g['PhotoGallery']['display_name'] = $name;
			$g['PhotoGallery']['description'] = 'description for '.$name;
			$this->PhotoGallery->create();
			$this->PhotoGallery->save($g);
		}
	}
	
	public function create_random_string($length) {
        $lib = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789';
        $return = '';
        for ($i=0;$i < $length; $i++) {
            $return .= $lib[rand(0, strlen($lib)-1)];
        }
        return $return; 
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
	
	
}