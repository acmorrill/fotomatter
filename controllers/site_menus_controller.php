<?php
class SiteMenusController extends AppController {
     var $name = 'SiteMenus';
     var $uses = array('SiteOneLevelMenu', 'SiteTwoLevelMenu', 'SiteTwoLevelMenuContainerItem');
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
			$returnArr['message'] = 'failed to delete site_one_level_menu item element in page';
			$this->SiteTwoLevelMenu->major_error('failed to delete site_one_level_menu item element in page');
		}
		
		
		$this->return_json($returnArr);
	}
	
	public function admin_add_one_level_menu_item($external_model, $external_id) {
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
	
	// DREW TODO - finish this function
	public function add_two_level_menu_container_item($site_two_level_menu_container_id, $external_model, $external_id) {
		
	}
}