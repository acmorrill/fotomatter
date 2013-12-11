<?php
require_once(ROOT . '/app/tests/fototestcase.php');
class TagSettingTestCase extends fototestcase {
	
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
        
        
        $first_photo = $this->Photo->find('first');
        $this->assertEqual($number_of_tags == count($first_photo['Tag']), true);
    }
    
    function test_new_save_did_not_exist() {
        $new_tags = array('Bob', 'fred', 'phone');
        $tag_result = $this->Tag->process_new_save($new_tags);
        $this->assertEqual(empty($tag_result), false);
        
        $tags = $this->Tag->find('all', array(
            'conditions'=>array(
                'Tag.name'=>$new_tags
            )
        ));
        $this->assertEqual(count($tags), 3);
        
        $tag_result = $this->Tag->process_new_save($new_tags);
        $this->assertEqual(empty($tag_result), false);
        $tags = $this->Tag->find('all', array(
            'conditions'=>array(
                'Tag.name'=>$new_tags
            )
        ));
        $this->assertEqual(count($tags), 3);
    }
    

    
}