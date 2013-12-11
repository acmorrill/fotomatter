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
	
	public function beforeDelete() {
		$site_two_level_menu = $this->find('first', array(
			'conditions' => array(
				'SiteTwoLevelMenu.id' => $this->id
			),
			'contain' => false
		));
		
		if ($site_two_level_menu['SiteTwoLevelMenu']['external_model'] == 'SiteTwoLevelMenuContainer') {
			$site_two_level_menu_container_id = $site_two_level_menu['SiteTwoLevelMenu']['external_id'];
			if (!$this->SiteTwoLevelMenuContainer->delete($site_two_level_menu_container_id)) {
				$this->major_error('failed to delete site_two_level_menu_container in beforeDelete of SiteTwoLevelMenu');
			}
		}
		
		return true;
	}
	
	public function afterDelete() {
//		$this->log($this->data, 'afterDelete');
	}
	
}