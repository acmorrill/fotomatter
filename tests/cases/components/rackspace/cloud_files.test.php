<?php
class CloudFilesTestCase extends CakeTestCase {
    
    public function start() {
        App::import("Component", "CloudFiles");
        $this->CloudFiles = new CloudFilesComponent();
    }
    
    public function test_list_containers() {
        //make sure the api data exists in the database
        $all_containers = $this->CloudFiles->list_containers();
        $this->assertEqual(empty($all_containers), false);
        
        //make sure it has the name, count of objects, and bytes
        $one_container = $all_containers[1];
        $this->assertEqual(empty($one_container['name']), false);
        $this->assertEqual(empty($one_container['bytes']), false);
        $this->assertEqual(empty($one_container['count']), false);
    }
    
    public function test_create_container() {
        for ($i=0; $i < 1; $i++) {
            $container_name = $this->_create_random_string(10);
            $this->container_names[] = $container_name;
            $create_result = $this->CloudFiles->create_container($container_name);
            $this->assertEqual($create_result, true);
        }
        //failure cases
        $big_container_name = $this->_create_random_string(101);
        $this->assertEqual($this->CloudFiles->create_container($big_container_name), false);
        
        $empty_name = '';
        $this->assertEqual($this->CloudFiles->create_container($empty_name), false);
        
    }
    
    public function test_delete_container() {
        foreach ($this->container_names as $container) {
            $result = $this->CloudFiles->delete_container($container);
            $this->assertEqual($result, true);
        }
        $empty_name = '';
        $this->assertEqual($this->CloudFiles->delete_container($empty_name), false);
    }
    
    private function _create_random_string($length) {
        $lib = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789';
        $return = '';
        for ($i=0;$i < $length; $i++) {
            $return .= $lib[rand(0, strlen($lib)-1)];
        }
        return $return;
        
    }
    
}