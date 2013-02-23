<?php

class PhotoPrintType extends AppModel {
	public $name = 'PhotoPrintType';
	
	public $hasMany = array(
		'PhotoAvailSizesPhotoPrintType'
	);
	
	public $actsAs = array('Ordered' => array(
		'foreign_key' => false,
		'field' => 'order',
	));
	
}