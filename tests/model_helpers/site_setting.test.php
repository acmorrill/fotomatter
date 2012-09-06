<?php
require_once(ROOT.DS.'app'.DS.'tests'.DS.'model_helpers'.DS.'model_helper_obj.php');
class SiteSettingTestCaseHelper extends ModelHelperObj {
    
    function __construct() {
	$this->SiteSetting = ClassRegistry::init("SiteSetting");
    }
    
    public function test_check_for_container_name() {
	$this->SiteSetting = ClassRegistry::init("SiteSetting");
	$image_container_name = $this->SiteSetting->getVal('image-container-name');
	if (empty($image_container_name)) {
	    $this->SiteSetting = ClassRegistry::init("SiteSetting");
	    $this->_record_real_error('Warning: image-container-name does not exist.', $this->SiteSetting->find('all'), 'high');
	    debug("Warning: image-container-name does not exist.");
	    return false;
	}
	return true;
    }
    
    public function ensure_correct_url() {
	App::import("Component", 'CloudFiles');
	$this->CloudFiles = new CloudFilesComponent();
	$container_details = $this->CloudFiles->cdn_detail_container();
	$current_db_url = $this->SiteSetting->getImageContainerUrl();
	$current_db_url = rtrim($current_db_url, "/ ");
	preg_match("/^[A-Z0-9\-\.\/\:]+/i", $container_details['Cdn-Uri'], $matches);
	$container_details['Cdn-Uri'] = $matches[0];
      
	if ($current_db_url == $container_details['Cdn-Uri']) {
	    return true;
	}
	$this->_record_real_error('Warning: the cdn-uri does not match what the url should be for image-container-name', null, 'high');
	debug('Warning: the cdn-uri does not match the save image-container-name');
	return false;
    }
    
    public function ensure_correct_secure_url() {
	App::import("Component", 'CloudFiles');
	$this->CloudFiles = new CloudFilesComponent();
	$container_details = $this->CloudFiles->cdn_detail_container();
	$current_db_url = $this->SiteSetting->getImageContainerSecureUrl();
	$current_db_url = rtrim($current_db_url, "/ ");
	preg_match("/^[A-Z0-9\-\.\/\:]+/i", $container_details['Cdn-Ssl-Uri'], $matches);
	$container_details['Cdn-Ssl-Uri'] = $matches[0];
	
        if ($current_db_url == $container_details['Cdn-Ssl-Uri']) {
	    return true;
	}
	$this->_record_real_error('Warning: the cdn-ssl-uri does not match what the secure url should be for image-container-name', null, 'high');
	debug('Warning: the cdn-ssl-uri does not match what the secure url should be for image-container-name');
	return false;
    }
}
?>