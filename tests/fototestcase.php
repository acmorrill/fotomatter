<?php
class fototestcase extends CakeTestCase {
    
    public $tables = array();
    
    public $sources = array();
    
    public $live_db = array();
    
    public $source_list = array();
    
    public function startTest($method) {
        
        if (empty($this->source_list)) {
            $db_config = new DATABASE_CONFIG();
            $this->source_list = array_keys(get_object_vars($db_config));
            
            //remove test
            unset($this->source_list[array_search('test', $this->source_list)]);
        }

        if (empty($this->tables)) {
            foreach ($this->source_list as $source) {
                $this->sources[$source] = ConnectionManager::getDataSource($source);
                $this->tables[$source] = $this->sources[$source]->listSources();
            
                $config = $this->sources[$source]->config;
                foreach ($this->tables[$source] as $table) {
                    $mysql_dump_command = "mysqldump -u {$config['login']} -p{$config['password']} {$config['database']} $table --compact";     
                    exec($mysql_dump_command, $this->live_db[$source][$table]);
                    $this->live_db[$source][$table] = explode(";\n", implode("\n", $this->live_db[$source][$table]));
                }
            }
        }
        
        //get the test datasource and then drop all databases
        @$test_db =& ConnectionManager::getDataSource('test');
        $resource = $this->connect($test_db->config);
        
        foreach ($this->source_list as $source) {            
            foreach ($this->tables[$source] as $table) {
                if (mysql_query("DROP TABLE if exists $table", $resource) == false) {
                    die('problem clearing test db');
                }
                foreach ($this->live_db[$source][$table] as $query) {
                   mysql_query($query, $resource);
                }
            }
        }
       
        ConnectionManager::create('test_suite', $test_db->config);
        $test_db->cacheSources = false;
        ClassRegistry::config(array('ds' => 'test_suite')); 
    }
    
    public function endTest() {
      /*  @$test_db =& ConnectionManager::getDataSource('test');
        $resource = $this->connect($test_db->config);
        
        foreach ($this->source_list as $source) {
            
            foreach ($this->tables[$source] as $table) {
                if (mysql_query("DROP TABLE if exists $table", $resource) == false) {
                    die('problem clearing test db');
                }
            }
        } */
    }
    
    private function connect($config) {
        $res = mysql_connect($config['host'], $config['login'], $config['password']);
        mysql_select_db($config['database'], $res);
        return $res;
    }
    
}