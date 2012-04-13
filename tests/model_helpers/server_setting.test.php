<?php
require_once(ROOT.DS.'app'.DS.'tests'.DS.'model_helpers'.DS.'model_helper_obj.php');
class ServerSettingTestCaseHelper extends ModelHelperObj {
    
    function __construct() {
	$this->ServerSetting = ClassRegistry::init("ServerSetting");
    }
    
    public function rackspace_creds_exist() {
	$result = true;
	$username = $this->ServerSetting->getVal('rackspace_api_username', false);
	if (empty($username)) {
	    debug('Server settings is missing the rackspace_api_username');
	    $this->_record_real_error('Server settings is missing the rackspace_api_username', $this->ServerSetting->find('all'), 'high');
	    $result = false;
	}
	
	$key = $this->ServerSetting->getVal('rackspace_api_key', false);
	if (empty($key)) {
	    debug('Server settings is missing the rackspace_api_key');
	    $this->_record_real_error('Server settings is missing the rackspace_api_key', $this->ServerSetting->find('all'), 'high');
	    $result = false;
	}

	//credentials are valid?
	App::import("Component", "CloudFiles");
	$this->CloudFiles = new CloudFilesComponent();
	$auth_test = $this->CloudFiles->test_authenticate();
	if ($auth_test === false) {
	    debug('Rackspace auth was not successful.');
	    $this->_record_real_error('Rackspace auth was not successful', $this->ServerSetting->find('all'), 'high');
	    $result = false;
	}
	return $result;
    }
}
?>