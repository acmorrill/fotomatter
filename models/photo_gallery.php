<?php
class PhotoGallery extends AppModel {
	public $name = 'PhotoGallery';
	public $hasAndBelongsToMany = array(
		'Photo'
	);
	public $actsAs = array('Ordered' => array('foreign_key' => false));
	
	
}