<?php
/**
 * Description of domains_controller
 *
 * @author aholsinger
 */
class DomainsController extends Appcontroller {

	public $uses = array();
	
	public $layout = 'admin/accounts';
	
	public $components = array('FotomatterDomain');
	
	public function admin_index() {
		
	}
	
	public function search() {
		if (isset($this->params['form']['q'])) {
			$domains = $this->FotomatterDomain->check_availability($this->params['form']['q']);
			$this->return_json($domains);
		}
		
		exit();
	}
	
	public function domain_checkout() {
		$this->layout = 'ajax';
	}
}