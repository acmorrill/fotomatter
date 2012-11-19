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
	
	public function get_containers() {
		$containers = $this->find('all', array(
			'contain' => false
		));
		
		foreach ($containers as $key => $container) {
			$item_data = $this->SiteTwoLevelMenu->find('first', array(
				'conditions' => array(
					'SiteTwoLevelMenu.external_id' => $container['SiteTwoLevelMenuContainer']['id'],
					'SiteTwoLevelMenu.external_model' => 'SiteTwoLevelMenuContainer'
				),
				'contain' => false
			));
			
			$containers[$key]['SiteTwoLevelMenu'] = $item_data['SiteTwoLevelMenu'];
		}
		
		return $containers;
	}
}