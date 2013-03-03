<?php

class PhotoAvailSizesPhotoPrintType extends AppModel {
	public $name = 'PhotoAvailSizesPhotoPrintType';
	
	public $belongsTo = array(
		'PhotoAvailSize',
		'PhotoPrintType'
	);
	
	public $hasMany = array(
		'PhotoSellablePrint'
	);
	
	public function afterDelete() {
		$this->PhotoSellablePrint->deleteAll(array(
			'PhotoSellablePrint.photo_avail_sizes_photo_print_type_id' => $this->id
		), true, true);
	}
}