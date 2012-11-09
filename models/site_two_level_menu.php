<?php
class SiteTwoLevelMenu extends AppModel {
	public $name = "SiteTwoLevelMenu";
	public $actsAs = array('Ordered' => array('foreign_key' => false));
	public $belongsTo = array(
		'SitePage' => array(
			'className' => 'SitePage',
			'foreignKey' => 'external_id',
			'conditions' => "SiteTwoLevelMenu.external_model = 'SitePage'"
		),
		'PhotoGallery' => array(
			'className' => 'PhotoGallery',
			'foreignKey' => 'external_id',
			'conditions' => "SiteTwoLevelMenu.external_model = 'PhotoGallery'"
		),
		'SiteTwoLevelMenuContainer' => array(
			'className' => 'SiteTwoLevelMenuContainer',
			'foreignKey' => 'external_id',
			'conditions' => "SiteTwoLevelMenu.external_model = 'SiteTwoLevelMenuContainer'"
		)
	);
	
	public $validate = array(
		'external_model' => array(
			'valid_options' => array(
				'rule' => array('inList', array('SitePage', 'PhotoGallery', 'SiteTwoLevelMenuContainer'))
			)
		)
	);
	
}