<?php
class ServerSettingFixture extends CakeTestFixture {
    var $name = 'ServerSetting';
    var $table = 'server_settings';
    public $useDbConfig = 'server_global';
    var $import = array('model'=>'ServerSetting', 'connection'=>'server_global', 'records'=>true);
    
    var $fields = array(
                'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
                'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 30, 'key' => 'unique', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
                'value' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 128, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
                'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
                'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
                'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'name' => array('column' => 'name', 'unique' => 1)),
                'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
        );
}