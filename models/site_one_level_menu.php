<?php
class SiteOneLevelMenu extends AppModel {
	public $name = "SiteOneLevelMenu";
	public $actsAs = array('Ordered' => array('foreign_key' => false));
	public $belongsTo = array(
		'SitePage' => array(
			'className' => 'SitePage',
			'foreignKey' => 'external_id',
			'conditions' => "SiteOneLevelMenu.external_model = 'SitePage'"
		),
		'PhotoGallery' => array(
			'className' => 'PhotoGallery',
			'foreignKey' => 'external_id',
			'conditions' => "SiteOneLevelMenu.external_model = 'PhotoGallery'"
		)
	);
	
	
}