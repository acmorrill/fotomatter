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
       
        function test_convert_with_smaller_than_master_cache_limit() {
            //upload small image
            //http://d7d33ce07e5a4dde758f-907816caf88b83a66c02c54765504ae9.r33.cf2.rackcdn.com/small_car.gif
            $this->_clear_errors_for_test();
            $this->Testing->give_me_this('small_car.gif', 'http://d7d33ce07e5a4dde758f-907816caf88b83a66c02c54765504ae9.r33.cf2.rackcdn.com');
            
            $this->Photo = ClassRegistry::init("Photo");
            $photo = $this->Photo->findById($this->Photo->id);
            $this->_ensure_no_errors();
        }
  
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
	    
            $this->_ensure_no_errors();
        } 
         
        public function test_get_dummy_image_path() {
            $this->_clear_errors_for_test();
            $image_path = $this->PhotoCache->get_dummy_error_image_path(200, 200, false);
            unlink(ROOT . '/app/webroot' . $image_path);
            $image_path = $this->PhotoCache->get_dummy_error_image_path(200, 200, false);
            
            //just make sure the image is valid
            $image_url = 'http://'.$_SERVER['HTTP_HOST'] . $image_path;
            
            $image_vars = getimagesize($image_url);
            $this->assertEqual(200, $image_vars[0]);
            $this->assertEqual(150, $image_vars[1]);
            $this->_ensure_no_errors();
        } 
  
        public function test_get_dummy_processing_image_path() {
            $this->_clear_errors_for_test();
            $image_path = $this->PhotoCache->get_dummy_processing_image_path(200, 200, false);
            
            //just make sure the image is valid
            $image_url = 'http://'.$_SERVER['HTTP_HOST'] . $image_path;
            
            $image_vars = getimagesize($image_url);
            $this->assertEqual(200, $image_vars[0]);
            $this->assertEqual(150, $image_vars[1]);
            $this->_ensure_no_errors();
        } 
        
        public function test_get_full_path() {
           $this->_clear_errors_for_test();
           $this->Testing->give_me_images(1);
           $image_cache = $this->PhotoCache->find('first', array(
               'order'=>'RAND()'
           ));
           $full_path = $this->PhotoCache->get_full_path($image_cache['PhotoCache']['id']);
           
           $image_vars = getimagesize($full_path);
           $this->assertEqual($image_cache['PhotoCache']['pixel_width'], $image_vars[0]);
           $this->assertEqual($image_cache['PhotoCache']['pixel_height'], $image_vars[1]);
           $this->_ensure_no_errors();
        } 
       
        function test_prepare_new_cache_size() {
           $this->_clear_errors_for_test();
           $this->Testing->give_me_images(3);
           
           $this->Photo = ClassRegistry::init("Photo");
           for ($i = 0; $i < 20; $i++) {
               $target_width = rand(150, 500);
               $target_height = rand(150, 500);
               $raw_id = rand(0, 1);
             
               //find randome image to create cache size
               $random_photo = $this->Photo->find('first', array(
                   'order'=>'rand()'
               ));
               
               $image_url_or_id = $this->PhotoCache->prepare_new_cachesize($random_photo['Photo']['id'], $target_width, $target_height, $raw_id);
               if (!$raw_id) {
                   preg_match("/\/photo_caches\/create_cache\/(.*?)\//", $image_url_or_id, $matches);
                   $image_url_or_id = $matches[1];
               }
               
               //make sure the last photo inserted is the one returned
               $last_photo = $this->PhotoCache->find('first', array(
                   'order'=>'PhotoCache.id DESC'
               ));
               $this->assertEqual($last_photo['PhotoCache']['id'], $image_url_or_id);
               
               $existing_test = $this->PhotoCache->get_existing_cache_create_url($image_url_or_id);
               $this->assertEqual(preg_match("/^\/photo_caches\/create_cache\/(.*?)\/$/", $existing_test), true);
               
               $this->PhotoCache->finish_create_cache($last_photo['PhotoCache']['id'], false);
               $full_path = $this->PhotoCache->get_full_path($last_photo['PhotoCache']['id']);
               $image_values = getimagesize($full_path);
               $this->assertEqual(empty($image_values), false);
           }
           
           
           $this->_ensure_no_errors();
        }
               
        //TODO .. make sure up resing and down resing happens as necessary
        function test_random_size_images() {
            //upload small image
            //http://d7d33ce07e5a4dde758f-907816caf88b83a66c02c54765504ae9.r33.cf2.rackcdn.com/small_car.gif
            $files = array(
                'small_car.jpg',
                '200car.jpg',
                '1000car.jpg',
                '1500car.jpg',
                '2200car.jpg',
                  'walkman.jpg'
            );
            
            foreach ($files as $file) {
                $this->_clear_errors_for_test();
                $this->Testing->give_me_this($file, 'http://d7d33ce07e5a4dde758f-907816caf88b83a66c02c54765504ae9.r33.cf2.rackcdn.com');

                $this->Photo = ClassRegistry::init("Photo");
                $photo = $this->Photo->findById($this->Photo->id);
                $this->_ensure_no_errors();
            }
        }
     

}
