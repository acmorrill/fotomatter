<?php

class PhotoAvailSizesPhotoPrintType extends AppModel {
	public $name = 'PhotoAvailSizesPhotoPrintType';
	
	public $belongsTo = array(
		'PhotoAvailSize',
		'PhotoPrintType'
	);
	
	
}