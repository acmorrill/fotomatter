<?php
class SiteMenusController extends AppController {
     var $name = 'SiteMenus';
     var $uses = array('SiteOneLevelMenu', 'SiteTwoLevelMenu', 'SiteTwoLevelMenuContainer', 'SiteTwoLevelMenuContainerItem');
	 var $helpers = array('ThemeMenu');
	 
	 public function admin_ajax_set_site_single_level_order($site_one_level_menu_id, $order) {
		$returnArr = array();
		
		$this->SiteOneLevelMenu->id = $site_one_level_menu_id;
		if ($this->SiteOneLevelMenu->moveto($site_one_level_menu_id, $order)) {
			$returnArr['code'] = 1;
		} else {
			$returnArr['code'] = -1;
			$returnArr['message'] = 'failed to arrange site_one_level_menu item element in page';
			$this->SiteOneLevelMenu->major_error('failed to arrange site_one_level_menu item element in page');
		}
		
		$this->return_json($returnArr);
	}
	
	public function admin_ajax_set_site_two_level_order($site_two_level_menu_id, $order) {
		$returnArr = array();
		
		$this->SiteTwoLevelMenu->id = $site_two_level_menu_id;
		if ($this->SiteTwoLevelMenu->moveto($site_two_level_menu_id, $order)) {
			$returnArr['code'] = 1;
		} else {
			$returnArr['code'] = -1;
			$returnArr['message'] = 'failed to arrange site_two_level_menu item element';
			$this->SiteTwoLevelMenu->major_error('failed to arrange site_two_level_menu item element');
		}
		
		$this->return_json($returnArr);
	}
	
	
	public function admin_ajax_set_menu_item_order_in_container($container_item_id, $new_order) {
		$returnArr = array();
		
		$this->SiteTwoLevelMenuContainerItem->id = $container_item_id;
		if ($this->SiteTwoLevelMenuContainerItem->moveto($container_item_id, $new_order)) {
			$returnArr['code'] = 1;
			$returnArr['message'] = '';
		} else {
			$returnArr['code'] = -1;
			$returnArr['message'] = 'failed to arrange SiteTwoLevelMenuContainerItem';
			$this->Photo->major_error('failed to arrange SiteTwoLevelMenuContainerItem', compact('container_item_id', 'new_order'));
		}
		
		$this->return_json($returnArr);
	}
	
	public function admin_ajax_delete_sub_menu_item($site_two_level_menu_container_id) {
		$returnArr = array();
		
		if ($this->SiteTwoLevelMenuContainerItem->delete($site_two_level_menu_container_id)) {
			$returnArr['code'] = 1;
			$returnArr['message'] = '';
		} else {
			$returnArr['code'] = -1;
			$returnArr['message'] = 'Failed to delete the SiteTwoLevelMenuContainerItem';
			$this->SiteTwoLevelMenuContainerItem->major_error('failed to arrange SiteTwoLevelMenuContainerItem', compact('site_two_level_menu_container_id'));
		}
		
		$this->return_json($returnArr);
	}
	
	public function admin_ajax_delete_one_level_menu_item($site_one_level_menu_id) {
		$returnArr = array();
		
		if ($this->SiteOneLevelMenu->delete($site_one_level_menu_id)) {
			$returnArr['code'] = 1;
		} else {
			$returnArr['code'] = -1;
			$returnArr['message'] = 'failed to delete site_one_level_menu item element in page';
			$this->SiteOneLevelMenu->major_error('failed to delete site_one_level_menu item element in page');
		}
		
		
		$this->return_json($returnArr);
	}
	
	public function admin_ajax_delete_two_level_menu_item($site_two_level_menu_id) {
		$returnArr = array();
		
		if ($this->SiteTwoLevelMenu->delete($site_two_level_menu_id)) {
			$returnArr['code'] = 1;
		} else {
			$returnArr['code'] = -1;
			$returnArr['message'] = 'failed to delete site_two_level_menu item element in page';
			$this->SiteTwoLevelMenu->major_error('failed to delete site_two_level_menu item element in page');
		}
		
		
		$this->return_json($returnArr);
	}
	
	public function admin_add_one_level_menu_item($external_model, $external_id) {
		if ($external_model == 'SitePage') {
			$this->FeatureLimiter->limit_function($this, 'page_builder'); // $controller, $feature_ref_name
		}
		
		
		$returnArr = array();
		
		$data = array();
		$data['SiteOneLevelMenu']['external_id'] = $external_id; 
		$data['SiteOneLevelMenu']['external_model'] = $external_model; 
		
		$this->SiteOneLevelMenu->create();
		if ($this->SiteOneLevelMenu->save($data)) {
			$returnArr['code'] = 1;
			
			$new_menu_item = $this->SiteOneLevelMenu->find('all', array(
				'conditions' => array(
					'SiteOneLevelMenu.id' => $this->SiteOneLevelMenu->id
				)
			));
			$returnArr['new_menu_item_html'] = $this->Element('admin/theme_center/main_menu/single_level_menu_item', array('single_menu_items' => $new_menu_item));
		} else {
			$returnArr['code'] = -1;
			$returnArr['message'] = 'failed to add a one level menu item';
			$this->SiteOneLevelMenu->major_error('failed to add a one level menu item', compact('data'));
		}
		
		$this->return_json($returnArr);
	}
	
	public function admin_add_two_level_menu_item($external_model, $external_id) {
		if ($external_model == 'SitePage') {
			$this->FeatureLimiter->limit_function($this, 'page_builder'); // $controller, $feature_ref_name
		}
		
		
		$returnArr = array();
		
		$data = array();
		$data['SiteTwoLevelMenu']['external_id'] = $external_id; 
		$data['SiteTwoLevelMenu']['external_model'] = $external_model; 
		
		$this->SiteTwoLevelMenu->create();
		if ($this->SiteTwoLevelMenu->save($data)) {
			$returnArr['code'] = 1;
			
			$new_menu_item = $this->SiteTwoLevelMenu->find('all', array(
				'conditions' => array(
					'SiteTwoLevelMenu.id' => $this->SiteTwoLevelMenu->id
				)
			));
			$returnArr['new_menu_item_html'] = $this->Element('admin/theme_center/main_menu/two_level_menu_item', array('two_level_menu_items' => $new_menu_item));
		} else {
			$returnArr['code'] = -1;
			$returnArr['message'] = 'failed to add a two level menu item to the top level';
			$this->SiteTwoLevelMenu->major_error('failed to add a two level menu item to the top level', compact('data'));
		}
		
		$this->return_json($returnArr);
	}
	
	public function admin_add_two_level_menu_container() {
		$returnArr = array();
		
		// make sure the container name is set
		$new_container_name = (isset($this->params['form']['new_container_name'])) ? $this->params['form']['new_container_name'] : null ;
		if (!isset($new_container_name)) {
			$returnArr['code'] = -1;
			$returnArr['message'] = 'Error trying to add a two level container item without a name.';
			$this->SiteTwoLevelMenu->major_error('Error trying to add a two level container item without a name.', array());
			$this->return_json($returnArr);
		}
		
		
		// create the actual container
		$new_container = array();
		$new_container['SiteTwoLevelMenuContainer']['display_name'] = $new_container_name;
		$this->SiteTwoLevelMenuContainer->create();
		if(!$this->SiteTwoLevelMenuContainer->save($new_container)) {
			$returnArr['code'] = -1;
			$returnArr['message'] = 'Failed to add a menu container to two level menu top level';
			$this->SiteTwoLevelMenu->major_error('Failed to add a menu container to two level menu top level', compact('new_container'));
			$this->return_json($returnArr);
		}
		$new_container_id = $this->SiteTwoLevelMenuContainer->id;
		
		
		// add the site two level menu and connect the new container to it
		$data = array();
		$data['SiteTwoLevelMenu']['external_id'] = $new_container_id; 
		$data['SiteTwoLevelMenu']['external_model'] = 'SiteTwoLevelMenuContainer'; 
		$this->SiteTwoLevelMenu->create();
		if ($this->SiteTwoLevelMenu->save($data)) {
			$returnArr['code'] = 1;
			
			$new_menu_item = $this->SiteTwoLevelMenu->find('all', array(
				'conditions' => array(
					'SiteTwoLevelMenu.id' => $this->SiteTwoLevelMenu->id
				)
			));
			$returnArr['new_menu_item_html'] = $this->Element('admin/theme_center/main_menu/two_level_menu_item', array('two_level_menu_items' => $new_menu_item));
		} else {
			$returnArr['code'] = -1;
			$returnArr['message'] = 'Failed to add a two level menu item with a container to the top level';
			$this->SiteTwoLevelMenu->major_error('Failed to add a two level menu item with a container to the top level', compact('data', 'new_container', 'new_container_id'));
		}
		
		$this->return_json($returnArr);
	}
	
	public function admin_add_two_level_menu_container_item($site_two_level_menu_container_id, $external_model, $external_id) {
		$returnArr = array();
		
		$data = array();
		$data['SiteTwoLevelMenuContainerItem']['ref_name'] = 'custom'; 
		$data['SiteTwoLevelMenuContainerItem']['site_two_level_menu_container_id'] = $site_two_level_menu_container_id; 
		$data['SiteTwoLevelMenuContainerItem']['external_id'] = $external_id; 
		$data['SiteTwoLevelMenuContainerItem']['external_model'] = $external_model; 
		
		$this->SiteTwoLevelMenuContainerItem->create();
		if ($this->SiteTwoLevelMenuContainerItem->save($data)) {
			$returnArr['code'] = 1;
			
			$new_menu_item = $this->SiteTwoLevelMenuContainerItem->find('first', array(
				'conditions' => array(
					'SiteTwoLevelMenuContainerItem.id' => $this->SiteTwoLevelMenuContainerItem->id
				)
			));
			
			App::import('Helper', 'ThemeMenu'); 
			$this->ThemeMenu = new ThemeMenuHelper();
			
			$menu_item_data = $this->ThemeMenu->get_menu_item_data($new_menu_item['SiteTwoLevelMenuContainerItem'], $new_menu_item);
			
			$returnArr['new_menu_item_html'] = $this->Element('admin/theme_center/main_menu/two_level_menu_container_item', array('submenu_items' => array($menu_item_data)));
		} else {
			$returnArr['code'] = -1;
			$returnArr['message'] = 'failed to add a two level menu container item to a container';
			$this->SiteTwoLevelMenuContainerItem->major_error('failed to add a two level menu item to the top level', compact('data'));
		}
		
		$this->return_json($returnArr);
	}
	
	public function admin_ajax_get_site_two_level_menu_containers() {
		$returnArr = array();
		
		$containers = $this->SiteTwoLevelMenuContainer->get_containers();
		
		$hide_top_level = (isset($this->params['form']['hide_top_level']) && $this->params['form']['hide_top_level'] == true) ? true : null ;

		$params = array(
			'all_containers' => $containers
		);
		
		if ($hide_top_level == true) {
			$params['hide_top_level'] = true;
		}
		
		$returnArr['code'] = 1;
		$returnArr['select_html'] = $this->Element('admin/theme_center/main_menu/container_select_box', $params);
		
		
		$this->return_json($returnArr);
	}
	
	public function admin_ajax_rename_site_two_level_menu_container() {
		$returnArr = array();
		
		$new_container_name = isset($this->params['form']['new_container_name']) ? $this->params['form']['new_container_name'] : null ;
		$container_to_rename_id = isset($this->params['form']['container_to_rename_id']) ? $this->params['form']['container_to_rename_id'] : null ;
		
		$data = array();
		$data['SiteTwoLevelMenuContainer']['id'] = $container_to_rename_id;
		$data['SiteTwoLevelMenuContainer']['display_name'] = $new_container_name;
		
		if ($this->SiteTwoLevelMenuContainer->save($data)) {
			$returnArr['code'] = 1;
		} else {
			$returnArr['code'] = -1;
			$returnArr['message'] = 'Failed to rename container.';
			$this->SiteTwoLevelMenuContainer->major_error('Failed to rename container.', compact('data'));
		}
		
		$this->return_json($returnArr);
	}
}