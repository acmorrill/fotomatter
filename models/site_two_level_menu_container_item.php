<?php
class SiteTwoLevelMenuContainerItem extends AppModel {
	public $name = "SiteTwoLevelMenuContainerItem";
	
	public $actsAs = array('Ordered' => array('foreign_key' => 'site_two_level_menu_container_id'));
	public $belongsTo = array(
		'SitePage' => array(
			'className' => 'SitePage',
			'foreignKey' => 'external_id',
			'conditions' => "SiteTwoLevelMenuContainerItem.external_model = 'SitePage'"
		),
		'PhotoGallery' => array(
			'className' => 'PhotoGallery',
			'foreignKey' => 'external_id',
			'conditions' => "SiteTwoLevelMenuContainerItem.external_model = 'PhotoGallery'"
		),
		'SiteTwoLevelMenuContainer'
	);
	
	public $validate = array(
		'external_model' => array(
			'valid_options' => array(
				'rule' => array('inList', array('SitePage', 'PhotoGallery'))
			)
		)
	);
	
}