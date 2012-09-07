<?php
class fototestcase extends CakeTestCase {
    
    public function before($method) {
        $default_db_data = @$db =& ConnectionManager::getDataSource('server_global');
        $output_command_global = "mysqldump -u {$default_db_data->config['login']} -p{$default_db_data->config['password']} {$default_db_data->config['database']}";
        
        $default_db_data = @$db =& ConnectionManager::getDataSource('default');
        $output_command = "mysql -u {$default_db_data->config['login']} -p{$default_db_data->config['password']} {$default_db_data->config['database']}";
        
        @$db =& ConnectionManager::getDataSource('test');
        $input_command = "mysql -u {$db->config['login']} -p{$db->config['password']} {$db->config['database']}";
        
        $tmp = "/tmp/db_dump";
        $command = $output_command . " > " . $tmp . " && " . $input_command . " < " . $tmp;
        debug($command); die();
        
        
        debug($command); die();
        exec($command);
        
        $command = $output_command . " > " . $input_command;
        exec($command); 
        
        die();
        
        
        
        
        
       /* $all_models = App::objects('model');
        foreach ($all_models as $model) {
            $table_sql = $this->_get_table($model);
            $model->setDataSource('test_suite');
           
        }
        
        $testDbAvailable = in_array('test', array_keys(ConnectionManager::enumConnectionObjects()));

        if ($testDbAvailable) {
                // Try for test DB
                restore_error_handler();
                @$db =& ConnectionManager::getDataSource('test');
                set_error_handler('simpleTestErrorHandler');
                $testDbAvailable = $db->isConnected();
        }
        
        ConnectionManager::create('test_suite', $db->config);
        $db->config['prefix'] = $_prefix;

        // Get db connection
        $this->db =& ConnectionManager::getDataSource('test_suite');
        $this->db->cacheSources  = false;

     //   ClassRegistry::config(array('ds' => 'test_suite')); */
        
        
    }
    
    private function _get_table($model) {
        $sql_to_return = '';
        $model_obj = ClassRegistry::init($model);
      
        //get table name
        $table_name_for_model = Inflector::tableize($model);
        
        //get db source
        $db = $model_obj->getDataSource();
        $mysql_dump_command = "mysqldump -u {$db->config['login']} -p{$db->config['password']} {$db->config['database']} $table_name_for_model";     
        exec($mysql_dump_command, $mysql_output);
       
        //insert table
        $model_obj->setDataSource('test');
        $model_obj->query(implode("\n", $mysql_output));
        
        die('here');
        
        
        
        
        
    }
    
    
}