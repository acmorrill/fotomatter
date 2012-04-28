<?php
class PhotoGalleriesPhotoFixture extends CakeTestFixture {

	var $name = "PhotoGalleriesPhoto";
	var $table = "photo_galleries_photos";	
	public $useDbConfig = 'default';
	var $import = array("table"=>"photo_galleries_photos", "records"=>"true");
	
	var $fields = array(
                'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
                'photo_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
                'photo_gallery_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
                'photo_order' => array('type' => 'integer', 'null' => false, 'default' => NULL),
                'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'photo_id' => array('column' => array('photo_id', 'photo_gallery_id'), 'unique' => 1)),
                'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
        );

}