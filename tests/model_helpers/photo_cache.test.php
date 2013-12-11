<?php
require_once(ROOT.DS.'app'.DS.'tests'.DS.'model_helpers'.DS.'model_helper_obj.php');
class PhotoCacheTestCaseHelper extends ModelHelperObj {

	function __construct() {
		$this->PhotoCache = ClassRegistry::init('PhotoCache');
	}
        
        function validate_no_old_queued() {
            $all_photo_cache = $this->PhotoCache->find('all', array(
                'contain'=>false
            ));
            
            foreach ($all_photo_cache as $cache) {
                $old_time = strtotime("-1 day");
                $cache_created_time = strtotime($cache['PhotoCache']['created']);
                
                if (($cache['PhotoCache']['status'] == 'queued' || $cache['PhotoCache']['status'] == 'processing') && $cache_created_time < $old_time) {
                     $this->_record_real_error("Queued or processing photo cache is older than one day", $cache);
                     return false;
                }
                
                if ($cache['PhotoCache']['status'] == 'failed') {
                    $this->_record_real_error("PhotoCache with failed status.", $cache);
                     return false;
                }
                
                if ($cache['PhotoCache']['status'] != 'queued' && ($cache['PhotoCache']['max_width'] == null || $cache['PhotoCache']['max_height'] == null)) {
                    $this->_record_real_error("cache max width and height not set.");
                    return false;
                }
            }
            return true;
        }

}