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
	
	public function afterDelete() {
		$this->PhotoAvailSizesPhotoPrintType->deleteAll(array(
			'PhotoAvailSizesPhotoPrintType.photo_print_type_id' => $this->id
		), true, true);
	}
}