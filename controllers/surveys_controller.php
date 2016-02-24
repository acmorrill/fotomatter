<?php

class SurveysController extends AppController {

	public $uses = array();
	public $layout = 'admin/sidebar_less';
	public $components = array(
		'FotomatterBilling',
		'Session',
		'Validation',
		'FotomatterEmail'
	);

	public function admin_index($sid = null) {
		$this->SiteSetting = ClassRegistry::init('SiteSetting');
		$account_id = $this->SiteSetting->getVal('account_id', false);
		$survey_data = $this->FotomatterBilling->get_survey_info($sid);
		$curr_page = 'site_settings';
		$curr_sub_page = 'surveys';
		$title_for_layout = 'Surveys';
		$this->set(compact(array('debugging', 'curr_page', 'curr_sub_page', 'title_for_layout', 'account_id', 'survey_data', 'sid')));
	}
	
//	public function admin_test3($sid = null) {
////		$this->SiteSetting = ClassRegistry::init('SiteSetting');
////		$account_id = $this->SiteSetting->getVal('account_id', false);
//		$survey_data = $this->FotomatterBilling->get_survey_info($sid);
//		print('<pre>' . print_r($survey_data, true) . "</pre>");
//	}
}
