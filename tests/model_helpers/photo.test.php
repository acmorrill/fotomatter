<?php
class PhotoTestCaseHelper {
    
    function __construct() {
	$this->Photo = ClassRegistry::init("Photo");
    }
    
    function check_for_consistent_values() {
	//init major error model and connect it to default data source, major errors need to go to the real db
	$photos_to_check_at_once = 50;
	$this->MajorError = ClassRegistry::init("MajorError");
	$this->MajorError->setDataSource('default');
	
	//init cloud files component to make sure I have matching file entries
	App::import("Component", "CloudFiles");
	$cloudFiles = new CloudFilesComponent();
	$photo_objects = $cloudFiles->list_objects();
	
	//key object array 
	$tmp_array = array();
	foreach ($photo_objects as $object) {
	    $tmp_array[$object['name']] = '';
	}
	unset($photo_objects);
	$photo_objects = $tmp_array;

	$photo_count = $this->Photo->find('count');
	$photo_pages = ceil($photo_count / $photos_to_check_at_once);
	$error_found = false;
	for ($i = 1; $i <= $photo_pages; $i++) {
	    $photos_for_batch = $this->Photo->find("all", array(
		'limit'=>$photos_to_check_at_once,
		'page'=>$i,
		'contain'=>false
	    ));
	    
	    foreach ($photos_for_batch as $photo) {
		if (empty($photo['Photo']['cdn-filename']) === false) {
		    if (empty($photo['Photo']['cdn-filename-forcache'])) {
			$this->MajorError->major_error("Photo found with missin cdn-filename-forcache", $photo);
			$error_found = true;
		    }
		    
		    if (empty($photo['Photo']['pixel_width'])) {
			$this->MajorError->major_error("Photo found with missing pixel_width", $photo);
			$error_found = true;
		    }
		    
		    if (empty($photo['Photo']['pixel_height'])) {
			$this->MajorError->major_error("Photo found with missing pixel_height", $photo);
			$error_found = true;
		    }
		    
		    if (empty($photo['Photo']['forcache_pixel_width'])) {
			$this->MajorError->major_error("Photo found with missing forcache_pixel_width", $photo);
			$error_found = true;
		    }
		    
		    if (empty($photo['Photo']['forcache_pixel_height'])) {
			$this->MajorError->major_error("Photo found with missing forcache_pixel_height", $photo);
			$error_found = true;
		    }
		    
                    //major error happening for this check for some reason
		    if (isset($photo_objects[$photo['Photo']['cdn-filename']]) === false) {
			$this->MajorError->major_error("Missing photo in cloud files for cdn-filename", $photo);
			$error_found = true;
		    }
		    
		    if (isset($photo_objects[$photo['Photo']['cdn-filename-forcache']]) === false) {
			$this->MajorError->major_error("Missing photo in cloud files for cdn-filename-forcache", $photo);
			$error_found = true;
		    }
		}
	    }
	}
	if ($error_found) return false;
	return true;
    }
}
?>