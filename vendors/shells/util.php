<?php

class UtilShell extends Shell {
	public $uses = array('User', 'Group', 'Permission', 'Photo', 'SiteSetting', 'PhotoGallery', 'PhotoGalleriesPhoto', 'PhotoCache');
	
		///////////////////////////////////////////////////////////////
	/// shell start
	function _welcome() {
		Configure::write('debug', 1);

		$this->out();
		$this->out('Welcome to CakePHP v' . Configure::version() . ' Console');
		$this->hr();
		$this->out('App : '. $this->params['app']);
		$this->out('Path: '. $this->params['working']);
		$this->hr();
	}
	
	function main() {
		$this->help();
	}
	
	function help() {
		$kind = '';
		if (count($this->args)) {
			$kind = $this->args[0];
		}

		switch ($kind) {
			default:
				$this->out("
	add_default_data
	

");
		}
	}
	
	function defaults() {
		$this->SiteSetting->setVal('image-container-url', 'http://c9134086.r86.cf2.rackcdn.com/');
		$this->SiteSetting->setVal('image-container-secure_url', 'https://c9134086.ssl.cf2.rackcdn.com/');
		$this->SiteSetting->setVal('image-container-name', 'andrew-dev-container');
		
		$this->Photo->deleteAll(array("1=1"), true, true);
		$this->PhotoGallery->deleteAll(array("1=1"), true, true);
		
		
		App::import("Component", "CloudFiles");
        $this->files = new CloudFilesComponent();
		
		$all_objects = $this->files->list_objects();
		
		foreach ($all_objects as $all_object) {
			$this->files->delete_object($all_object['name']);
			//print_r($all_object);
		}
		
		
		$photo_data = array();
		
		////////////////////////////////////////////
		// add some default photos
		$lastPhoto = $this->Photo->find('first', array(
			'order' => 'Photo.id DESC'
		));
		if ($lastPhoto) {
			$x = $lastPhoto['Photo']['id'];
		} else {
			$lastPhoto['Photo']['id'] = 0;
			$x = 0;
		}
		for (; $x < $lastPhoto['Photo']['id'] + 300; $x++) {
			$photo_data[$x]['display_title'] = 'Title '.$x;
			$photo_data[$x]['display_subtitle'] = 'Subtitle '.$x;
			$photo_data[$x]['description'] = 'description '.$x;
			$photo_data[$x]['alt_text'] = $photo_data[$x]['display_subtitle'];
			$photo_data[$x]['enabled'] = 1;
			$photo_data[$x]['photo_format_id'] = rand(1, 5);
		}
		$this->Photo->saveAll($photo_data);
		
		
		
		// add some default galleries and add random photos to them
		$lastGallery = $this->PhotoGallery->find('first', array(
			'order' => 'PhotoGallery.id DESC'
		));
		if ($lastGallery) {
			$x = $lastGallery['PhotoGallery']['id'];
		} else {
			$x = 0;
			$lastGallery['PhotoGallery']['id'] = 0;
		}
		for (; $x < $lastGallery['PhotoGallery']['id'] + 50; $x++) {
			$gallery_data['PhotoGallery']['display_name'] = 'Name '.$x;
			$gallery_data['PhotoGallery']['description'] = 'description '.$x;
			$this->PhotoGallery->create();
			$this->PhotoGallery->save($gallery_data);
			
			$limit = rand(0, 10);
			if ($limit > 0) {
				$randomPhotoIds = $this->Photo->find('list', array(
					'fields' => 'id',
					'order' => 'RAND()',
					'limit' => $limit
				));
			} else {
				$randomPhotoIds = array();
			}
				
			foreach ($randomPhotoIds as $randomPhotoId) {
				$photo_gallery_photo['PhotoGalleriesPhoto'] = array(
					'photo_id' => $randomPhotoId,
					'photo_gallery_id' => $this->PhotoGallery->id
				);
				
				$this->PhotoGalleriesPhoto->create();
				$this->PhotoGalleriesPhoto->save($photo_gallery_photo);
			}
		}
	}
	
	public function list_cloudfiles() {
		App::import("Component", "CloudFiles");
        $this->files = new CloudFilesComponent();
		
		debug($this->files->list_objects());
	}
	
	public function give_me_images() {
		if (isset($this->args[0]) && is_numeric($this->args[0]) === false) {
			$this->hr();
			$this->out('It looks you are trying to pass a image limit, but the value is not numeric');
			$this->out("example \n cake util give_me_images 12");
			$this->hr();
			exit(1);
		}
		
	    $this->Photo->query("truncate table photos;");
	    $this->PhotoCache->query("truncate table photo_caches");
	    $this->PhotoGallery->query("truncate table photo_galleries");
		$this->PhotoGallery->query("truncate table photo_galleries_photos");
		
		//clear current image
		App::import("Component", "CloudFiles");
        $this->files = new CloudFilesComponent();
	    $all_objects = $this->files->list_objects();
	    foreach($all_objects as $object) {
			$this->files->delete_object($object['name']);
	    }
		
		//Download any new images form the gallery
	    App::import("Component", "CloudFiles");
	    $this->files = new CloudFilesComponent();
	    $tmp_images = TEMP_IMAGE_VAULT . DS . 'test_images';
	    if (is_dir($tmp_images) === false) mkdir($tmp_images);
	    
	    $local_images = scandir($tmp_images);
	    $tmp = array();
	    foreach ($local_images as $image) {
			if ($image == '.' || $image=='..') {
				continue;
			}
			$tmp[$image] = $image;
	    }
	    $local_images = $tmp;
	    
	    $master_test_images = $this->files->list_objects('master-test');
	    foreach ($master_test_images as $image) {
			if (empty($local_images[$image['name']])) {
				unset($output);
				exec("cd $tmp_images; wget http://c13957077.r77.cf2.rackcdn.com/".$image['name']." > /dev/null 2>&1", $output);
			}
	    }
		
		//I probably saved new images so rescan to be sure
		$local_images = scandir($tmp_images);
	  
		//insert images into db
		$limit=false;
		if (isset($this->args[0])) {
			$limit=$this->args[0];
		}
		$actual_count=0;
	    foreach($local_images as $count => $image) {
			if ($image == '.' || $image=='..') {
				continue;
			}
			if ($actual_count >= $limit) break;
			$actual_count++;
			
			$photo_for_db['Photo']['cdn-filename']['tmp_name'] = $tmp_images . DS . $image;
			$photo_for_db['Photo']['cdn-filename']['name'] = $image;
			list($width, $height, $type, $attr) = getimagesize($tmp_images . DS . $image);
			$photo_for_db['Photo']['cdn-filename']['type'] = $type;
			$photo_for_db['Photo']['cdn-filename']['size'] = filesize($photo_for_db['Photo']['cdn-filename']['tmp_name']);


			$photo_for_db['Photo']['display_title'] = 'Title' . $image;
			$photo_for_db['Photo']['display_subtitle'] = 'subtitle' . $image;
			$photo_for_db['Photo']['alt_text'] = 'alt text ' . $image;
			$this->Photo->create();
			$this->Photo->save($photo_for_db);
			$this->out(($actual_count).". Image ".$image." has been saved to the database.");
	    }
		
		$this->out("Done Inserting Images");
		$this->hr();
		$this->out("Creating Galleries");
	     
		//create random galleries and assign photos to them
	    $lastGallery = $this->PhotoGallery->find('first', array(
			'order' => 'PhotoGallery.id DESC'
		));
		if ($lastGallery) {
			$x = $lastGallery['PhotoGallery']['id'];
		} else {
			$x = 0;
			$lastGallery['PhotoGallery']['id'] = 0;
		}
		for (; $x < $lastGallery['PhotoGallery']['id'] + 50; $x++) {
			$gallery_data['PhotoGallery']['display_name'] = 'Name '.$x;
			$gallery_data['PhotoGallery']['description'] = 'description '.$x;
			$this->PhotoGallery->create();
			$this->PhotoGallery->save($gallery_data);
			
			$limit = rand(0, 10);
			if ($limit > 0) {
				$randomPhotoIds = $this->Photo->find('list', array(
					'fields' => 'id',
					'order' => 'RAND()',
					'limit' => $limit
				));
			} else {
				$randomPhotoIds = array();
			}
				
			foreach ($randomPhotoIds as $randomPhotoId) {
				$photo_gallery_photo['PhotoGalleriesPhoto'] = array(
					'photo_id' => $randomPhotoId,
					'photo_gallery_id' => $this->PhotoGallery->id
				);
				
				$this->PhotoGalleriesPhoto->create();
				$this->PhotoGalleriesPhoto->save($photo_gallery_photo);
			}
		}
		$this->out("Done Creating Galleries");
	}
	
	public function upload_folder() {
	    if (count($this->args) != 2) {
			$this->error("cake util upload_folder <complete-system-path> <rackspace container");
			exit(1);
	    }
	    App::import("Component", "CloudFiles");
	    $this->files = new CloudFilesComponent();
	    
	    if (is_readable($this->args[0]) === false) {
			$this->error("You non person you... the folder is not readable");
			exit(1);
	    }
	    
	    $all_images = scandir($this->args[0]);
	    foreach ($all_images as $image) {
			if ($image == '.' || $image == '..') {
				continue;
			}

			list($width, $height, $type, $attr) = getimagesize($this->args[0]."/".$image);
			$result = $this->files->put_object($image, $this->args[0]."/".$image, $type,$this->args[1]);
			if ($result === 'false') {
				$this->error('I returned false');
				exit(1);
			}
	    }
	}
}