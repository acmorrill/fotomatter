<?php
class PhotosTag extends AppModel {
	public $name = 'PhotosTag';
	
	public $belongsTo = array(
		'Photo',
		'Tag'
	);
	
}