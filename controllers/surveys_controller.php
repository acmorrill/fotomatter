<?php

class SurveysController extends AppController {

	public $uses = array();
	public $layout = 'admin/sidebar_less';

	public function admin_index() {
		$curr_page = 'site_settings';
		$curr_sub_page = 'surveys';
		$title_for_layout = 'Surveys';
		$this->set(compact(array('debugging', 'curr_page', 'curr_sub_page', 'title_for_layout')));
	}
}
