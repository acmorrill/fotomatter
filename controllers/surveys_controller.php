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

	public function admin_index() {
		$this->SiteSetting = ClassRegistry::init('SiteSetting');
		$account_id = $this->SiteSetting->getVal('account_id', false);
		$curr_page = 'site_settings';
		$curr_sub_page = 'surveys';
		$title_for_layout = 'Surveys';
		$this->set(compact(array('debugging', 'curr_page', 'curr_sub_page', 'title_for_layout', 'account_id')));
	}
	
	public function admin_test() {
		$a = $this->FotomatterBilling->getAccountDetails();
		echo '<pre>';var_dump($a);die();
	}
	
	public function admin_test2() {
		$a = $this->FotomatterBilling->get_account_info();
		echo '<pre>';var_dump($a);die();
	}
	
	public function admin_test3() {
		$this->SiteSetting = ClassRegistry::init('SiteSetting');
		$account_id = $this->SiteSetting->getVal('account_id', false);
		$a = $this->FotomatterBilling->get_survey_info(array('account_id' => $account_id));
		echo '<pre>';var_dump($a);die();
	}
}
