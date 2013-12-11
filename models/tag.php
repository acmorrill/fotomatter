<?php
class Tag extends AppModel {
    
    public $hasAndBelongsToMany = array('Photo');
    
    public function process_new_save($tag_data) {
        $results = array();
        foreach ($tag_data as $tag_name) {
            $tag_exists = $this->find('first', array(
                'conditions'=>array(
                    'Tag.name' => $tag_name
                )
            ));
            
            if (empty($tag_exists)) {
                $tag_to_save['Tag']['name'] = $tag_name;
                $this->create();
                $this->save($tag_to_save);
            }
            $results[] = empty($tag_exists['Tag']['id'])?$this->id:$tag_exists['Tag']['id'];
        }
        return $results;
    }
}