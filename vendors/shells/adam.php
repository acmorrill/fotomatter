<?php
class AdamShell extends Shell {
    
    public function my_settings() {
        $this->User = ClassRegistry::init("User");
        $user['User']['email_address'] = 'adamdude828@gmail.com';
        $user['User']['password'] = '1990geocar';
        $user['User']['active'] = true;
        $this->User->save();
        $this->User->create($user);
        
        App::import("Component", "CloudFiles");
        $this->files = new CloudFilesComponent();
        
        $this->SiteSetting = ClassRegistry::init("SiteSetting");
        $this->SiteSetting->setVal('image-container-name', 'adam-dev-container');
        
        $cdn_info = $this->files->cdn_detail_container('adam-dev-container');
      
        $this->SiteSetting->setVal("image-container-url", trim($cdn_info['Cdn-Uri']) . "/");
        $this->SiteSetting->setVal("image-container-secure_url", trim($cdn_info['Cdn-Ssl-Uri']) . "/");
        
        $all_images = $this->files->list_objects();
        foreach ($all_images as $image) {
            $this->files->delete_object($image['name']);
        }
    }
    
    public function test_api() {
        App::import("Component", "CloudFiles");
        $this->files = new CloudFilesComponent();
	//$test_file = TEMP_IMAGE_PATH . DS . 'test_images' . DS . 'EmeraldFlow.jpg';
	//$this->files->put_object('EmeraldFlow.jpg', $test_file, 'image/jpeg');
	
        
       /* $this->files->create_container('unit-test-fail');
		$this->files->cdn_enable_container('unit-test-fail');
		debug($this->files->cdn_detail_container('unit-test-fail'));
		$this->files->put_object('larger_image.jpg', '/home/adam/Pictures/large_image.jpg', "image/jpeg", "unit-test-fail"); */
		debug($this->files->list_objects('unit-test-fail'));
     
    }
	
	/***
	 * I wrote this to test if copying images from another container could be used to quickly get test images. It proved to be approxiamately 
	 * the same speed as just uploading the image. This funciton could prove usefule so I decided not to remove it. 
	 */
	public function copy_container() {
		if (count($this->args) != 2) {
			$this->error("Wrong arg count");
			exit(1);;
		}
		
		App::import("Component", "CloudFiles");
		$this->CloudFiles = new CloudFilesComponent();
		
		$destination_list = $this->CloudFiles->list_objects($this->args[0]);
		$this->CloudFiles->create_container($this->args[1]);
		$start = microtime(true);
		foreach ($destination_list as $file) {
			$result = $this->CloudFiles->copy_object($file['name'], $file['name'], $this->args[0], $this->args[1]);
			if ($result === false) {
				$this->error("returned false");
				exit(1);
			}
		}
		$end = microtime(true);
		$this->out("function ran in ".($end-$start));
	}
    
    public function generate_fixtures() {
        $this->SiteSetting = ClassRegistry::init("SiteSetting");
        $all_tables = $this->SiteSetting->query("SELECT 
 table_name AS `default` 
FROM 
 information_schema.tables
WHERE 
 table_schema = DATABASE()");
        foreach ($all_tables as $table) {
            $file_name = Inflector::singularize($table['tables']['default']);
            $class_name = Inflector::camelize($file_name);
            $file_name .= '_fixture.php';
            
            $output = "<?php\n";
            $output .= "class ".$class_name."Fixture extends CakeTestFixture {\n";
            $output .= "\n\t".'var $name = "'.$class_name.'";';
            $output .= "\n\t".'var $import = array("model"=>"'.$class_name.'", "records"=>"true");';
            $output .= "\n}";
            file_put_contents(ROOT.DS.'app'.DS.'tests'.DS.'fixtures'.DS.$file_name, $output);
            exec("git add ".ROOT.DS.'app'.DS.'tests'.DS.'fixtures'.DS.$file_name);
        }
    }
}