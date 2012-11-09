<?php
class SiteTwoLevelMenuContainer extends AppModel {
	public $name = "SiteTwoLevelMenuContainer";
	
//	public $hasOne = array(
//		'SiteTwoLevelMenu'
//	);
	
	public $hasMany = array(
		'SiteTwoLevelMenuContainerItem' => array(
			'dependent' => true
		),
		'SiteTwoLevelMenu'
	);
}