<?php
class fototestcase extends CakeTestCase {
    
    public $tables = array();
    
    public $sources = array();
    
    public $live_db = array();
    
    public function before($method) {
        if (empty($this->tables)) {
            $all_sources = & ConnectionManager::sourceList();
            foreach ($all_sources as $source) {
                $this->sources[$source] = ConnectionManager::getDataSource($source);
                $this->tables[$source] = $this->sources[$source]->listSources();
            
                $config = $this->sources[$source]->config;
                foreach ($this->tables[$source] as $table) {
                    $mysql_dump_command = "mysqldump -u {$config['login']} -p{$config['password']} {$config['database']} $table --skip-comments";     
                    exec($mysql_dump_command, $this->live_db[$source][$table]);
                    $this->live_db[$source][$table] = implode("\n", $this->live_db[$source][$table]);
                }
            }
        }
        
        //get the test datasource and then drop all databases
        @$test_db =& ConnectionManager::getDataSource('test');
        $resource = $this->connect($test_db->config);
        $all_sources = & ConnectionManager::sourceList();
        
        foreach ($all_sources as $source) {
            if ($source == 'test') continue;
            
            foreach ($this->tables[$source] as $table) {
                if (mysql_query("DROP TABLE if exists $table", $resource) == false) {
                    die('problem clearing test db');
                }
                debug($this->live_db); die();
                debug(mysql_query($this->live_db[$source][$table], $resource));
            }
        }
        
        //build db
        
        
        
        
        
        /*$testDbAvailable = in_array('test', array_keys(ConnectionManager::enumConnectionObjects()));

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
    
    private function connect($config) {
        $res = mysql_connect($config['host'], $config['login'], $config['password']);
        mysql_select_db($config['database'], $res);
        return $res;
    }
    
    private function _populate_tables() {
        
        
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