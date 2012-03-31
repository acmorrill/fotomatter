<?php

class UtilShell extends Shell {
	public $uses = array('User', 'Group', 'Permission', 'Photo', 'SiteSetting', 'PhotoGallery', 'PhotoGalleriesPhoto');
	
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
	
}