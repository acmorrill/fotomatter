<?php
class TagSettingTestCase extends CakeTestCase {
	
    public $fixtures = array('app.tag', 'app.photo', 'app.photo_gallery', 'app.photo_galleries_photo', 'app.major_error', 'app.user', 
	'app.group', 'app.permission', 'app.groups_permission', 'app.groups_user', 'app.site_setting', 'app.server_setting', 'app.photo_format',
	    'app.photo_cache', 'app.tag', 'app.photos_tag', 'app.photo_prebuild_cache_size');
    
    function start() {
		parent::start();
                require_once(ROOT . "/app/tests/model_helpers/tag.test.php");
                $this->helper = new TagTestCaseHelper();
                $this->Tag = ClassRegistry::init("Tag");
    }
    
    function test_tags_one_photo() {
        $this->Photo = ClassRegistry::init('Photo');
        $first_photo = $this->Photo->find('first', array(
            'contain'=>false
        ));
        $tags_to_create = array('silly', 'funny', 'angry', 'bad', 'good', 'mountain', 'blue', 'red');
        foreach ($tags_to_create as $tag) {
            $save['Tag']['name'] = $tag;
            $this->Tag->create();
            $this->Tag->save($save);
        }
        
        $number_of_tags = rand(1, count($tags_to_create));
        $tags_to_associate = $this->Tag->find('all', array(
            'limit'=>$number_of_tags,
            'order'=>'RAND()'
        ));
        $tag_ids = Set::extract("/Tag/id", $tags_to_associate);
        
        $first_photo['Tag'] = $tag_ids;
        $this->Photo->create();
        $this->Photo->save($first_photo);
        
        
        debug($first_photo = $this->Photo->find('first', array(
            
        )));
        
        
        
        
        
        
    }

    
}