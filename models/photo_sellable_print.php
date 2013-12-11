<?php

class PhotoSellablePrint extends AppModel {
	public $name = 'PhotoSellablePrint';
	
	public $belongsTo = array(
		'Photo',
		'PhotoAvailSizesPhotoPrintType'
	);
	
}