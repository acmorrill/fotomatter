<?php
require_once(ROOT . '/app/tests/fototestcase.php');
class PhotoCacheTestCase extends fototestcase {

	function start() {
		require_once(ROOT . '/app/tests/model_helpers/photo_cache.test.php');
		$this->helper = new PhotoCacheTestCaseHelper();
		$this->PhotoCache = ClassRegistry::init('PhotoCache');
		$this->_run_validate_functions($this->helper);
                
                App::import("Component", "Testing");
		$this->Testing = new TestingComponent();
	}
        
        /*function test_convert_with_smaller_than_master_cache_limit() {
            //upload small image
            //http://d7d33ce07e5a4dde758f-907816caf88b83a66c02c54765504ae9.r33.cf2.rackcdn.com/small_car.gif
            $this->_clear_errors_for_test();
            $this->Testing->give_me_this('small_car.gif', 'http://d7d33ce07e5a4dde758f-907816caf88b83a66c02c54765504ae9.r33.cf2.rackcdn.com');
            
            $this->Photo = ClassRegistry::init("Photo");
            $photo = $this->Photo->findById($this->Photo->id);
            
            $this->_ensure_no_errors();
        } */
        
        function test_delete_cached_file() {
            $this->_clear_errors_for_test();
            $this->Testing->give_me_images(1);
            $this->Photo = ClassRegistry::init("Photo");
            $all_cache_file = $this->PhotoCache->find('first', array(
                'conditions'=>array(
                    'PhotoCache.photo_id'=>$this->Photo->id
                )
            ));
            
           
            $this->PhotoCache->delete($all_cache_file['PhotoCache']['id']);
            
            App::import("Component", "CloudFiles");
            $this->CloudFiles = new CloudFilesComponent();
            $all_files = $this->CloudFiles->list_objects();
         
            foreach ($all_files as $file) {
                $this->assertEqual($file['name'] == $all_cache_file['PhotoCache']['cdn-filename'], false);
            }
	    
	    debug($this->Photo->query("select * from major_errors"));
            
            $this->_ensure_no_errors();
        }
        
   
        
        

}