<?php
class Tag extends AppModel {
    
	public $hasAndBelongsToMany = array('Photo');
	
	public $actsAs = array('Ordered' => array('foreign_key' => false));
	
	public function get_tags() {
		$tag_query = "
			SELECT Tag.id, Tag.weight, Tag.name, Tag.created, (SELECT count(*) FROM photos_tags WHERE tag_id = Tag.id) as photos_count
			FROM tags AS Tag
		";
		$tags = $this->query($tag_query);

		// convert tag ids to int so json will be int and sort correct in angular
		foreach ($tags as &$tag) {
			$tag['Tag']['id'] = (int) $tag['Tag']['id'];
			$tag['Tag']['photos_count'] = (int) $tag[0]['photos_count'];
			unset($tag[0]);
		}
		
		return $tags;
	}
    
}