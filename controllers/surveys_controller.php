<?php

/**
 * Description of domains_controller
 *
 * @author aholsinger
 */
class SurveysController extends Appcontroller {

	public $uses = array('MajorError');
	public $layout = 'admin/sidebar_less';
	public $components = array('NameCom');

	public function admin_index() {
		////////////////////////////////////////////////////////////
		// FOR DEBUGGING - ONLY IN DEBUG MODE
		$debugging = array();
//		if (Configure::read('debug') > 0) {
//			$debugging['hello'] = $this->NameCom->hello();
//			$debugging['account'] = $this->NameCom->get_account();
//			$debugging['list_domains'] = $this->NameCom->list_domains();
//		}

		$curr_page = 'site_settings';
		$curr_sub_page = 'surveys';
		$title_for_layout = 'Surveys';
		$this->set(compact(array('debugging', 'curr_page', 'curr_sub_page', 'title_for_layout')));
	}
}
