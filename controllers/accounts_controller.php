<?php
class AccountsController extends AppController {
    
	public $uses = array('GlobalCountry', 'GlobalCountryState', 'Photo', 'SitePage', 'PhotoGallery', 'Account');

	public $layout = 'admin/sidebar_less';
   
	public $components = array(
		'FotomatterBilling',
		'Session',
		'Validation',
		'FotomatterEmail'
	);
	
	public function  beforeFilter() {
		if ($this->action == 'clear_billing_cache') {
			$this->is_mobile = false;
			$this->layout = false;
			$this->Auth->allow('clear_billing_cache');
		} else {
			parent::beforeFilter();
		}
	}
//	
//	public function admin_test_email(){
//		$end_user_data ['contact_us_email']= 'giggerkent@gmail.com';
//		$end_user_data ['contact_us_name'] = 'kent boss';
//		$end_user_data ['contact_us_content'] = 'Hey I just want to slap you in the face.';
//		$this->FotomatterEmail->send_end_user_contact_us_email($this, $end_user_data);
//		die('who who sucka yes');
//	}
		public function admin_test_email(){
		$change_password_user ['User']= 'giggerkent@gmail.com';
		$change_password_user ['email_address'] = 'giggerkent@gmail.com';
		$change_password_user ['return_link'] = 'fotomatter.net';
		$change_password_user ['modified_hash'] = 'ppakdjf;alkjdpofjapodjfa';
		$change_password_user ['modified'] = 'ooialkdf;lwe';
		$change_password_user ['id'] = 'giggerkent@gmail.com';
		$change_password_user ['admin'] = 'giggerkent@gmail.com';
		$change_password_user ['admin'] = 'giggerkent@gmail.com';
		$this->FotomatterEmail->send_forgot_password_email($this, $change_password_user);
		die('sucka yes');
	}
	
	
	
	public function clear_billing_cache() {
		$this->FotomatterBilling->clear_billing_apc();
		exit();
	}
	
	public function admin_record_frontend_major_error() {
		$data = array();
		if (!empty($this->data)) {
			$data = json_decode($this->data, true);
		}
		if (!empty($data['message'])) {
			$this->major_error("javascript error: " . $data['message'], compact('data'));
		}
		$this->return_json(array( 'code' => true ));
	}
   
	public function admin_account_details() {
		$accountDetails = $this->FotomatterBilling->getAccountDetails();
		$photo_count = $this->Photo->find('count');
		$site_page_count = $this->SitePage->find('count');
		$photo_gallery_count = $this->PhotoGallery->find('count');
		
		$curr_page = 'site_settings';
		$curr_sub_page = 'account_details';

		$this->set('accountDetails', $accountDetails['data']);
		$this->set(compact('photo_count', 'site_page_count', 'photo_gallery_count', 'curr_page', 'curr_sub_page'));
	}
    
	/**
	* action for the page to add/remove line items. 
	*/
	public function admin_index($add_feature_ref_name = null) {
		$this->FotomatterBilling->clear_billing_apc();
		$overlord_account_info = $this->FotomatterBilling->get_account_info();
		print_r($overlord_account_info);
		die();
		
		$this->Session->delete('account_line_items');
		$this->Session->write('account_line_items', array('checked'=>array(), 'unchecked'=>array()));

		$this->Session->delete('account_info');
		$this->Session->write('account_info', $overlord_account_info);

		///////////////////////////////////////////////////////////////////////////////////////////////////////////
		// set item checked if have passed in $add_feature_ref_name
		// grab the starting element popup html if $add_feature_ref_name set
		$add_feature_ref_name_popup_html = '';
		if (!empty($add_feature_ref_name)) {
			$parsed_features_data = Set::combine($overlord_account_info['items'], '{n}.AccountLineItem.ref_name', '{n}.AccountLineItem');
			if (isset($parsed_features_data[$add_feature_ref_name]['id'])) {
				$this->Account->set_item_checked($parsed_features_data[$add_feature_ref_name]['id'], true);
			}
			$add_feature_ref_name_popup_html = $this->Account->finish_line_change($this, $this->FotomatterBilling->getPaymentProfile());
		}
		
		$curr_page = 'add_features';
		$this->set(compact(array('overlord_account_info', 'add_feature_ref_name', 'add_feature_ref_name_popup_html', 'curr_page')));
		
		$this->layout = 'admin/mass_upload';
		$this->render('/accounts/admin_index'); // required to overcome the element calls in finish_line_changes
	}
   
	public function admin_ajax_addPreviousItems() {
		$overlord_account_info = $this->Session->read('account_info');
		$line_items = $this->Session->read('account_line_items');

		foreach($overlord_account_info['items'] as $item) {
			if ($item['AccountLineItem']['is_pay_fail'] == false) {
				continue;
			}

			if (isset($line_items['unchecked'][$item['AccountLineItem']['id']])) {
				unset($line_items['unchecked'][$item['AccountLineItem']['id']]);
			}
			$line_items['checked'][$item['AccountLineItem']['id']] = 'checked';
		}
		$this->Session->write('account_line_items', $line_items);
		print(json_encode(array('code'=>true)));
		exit();
	}
   
	public function admin_ajax_undo_cancellation($line_item_id) {
		$result = $this->FotomatterBilling->undo_cancellation($line_item_id);
		if($result['code']){
			$this->return_json(array('code'=>true));
		} else {
			$this->Session->setFlash(sprintf(__("There has been a problem while undoing your cancellation. Please contact us at %s for help.", true), FOTOMATTER_SUPPORT_EMAIL), 'admin/flashMessage/error');
			$this->return_json(array('code'=>false));
		}
		exit();
	}
   
	   public function admin_ajax_remove_item($line_item_id) {
		   $result = $this->FotomatterBilling->remove_item($line_item_id);
		   if($result['code']){
			   $this->return_json(array('code'=>true));
		   } else {
			   $this->Session->setFlash(sprintf(__('There has been a problem while removing an item. Please contact us at %s for help.', true),  FOTOMATTER_SUPPORT_EMAIL), 'admin/flashMessage/error');
			   $this->return_json(array('code'=>false));
		   }
		   exit();
	   }
   
	/**
	* Gets called when a line item is selected or deselected
	* @return Json indicating that we have recorded whether that item is checked on unchecked
	*/
	public function admin_ajax_setItemChecked() {
		if ($this->Account->set_item_checked($this->params['form']['id'], $this->params['form']['checked'])) {
			print(json_encode(array('code' => true)));
		} else {
			print(json_encode(array('code' => false)));
		}
		exit();
	}
   
	/**
	* Get the payment profile form to update payment details
	*/
	public function admin_ajax_update_payment() {
		if ($this->params['named']['closeWhenDone'] == 'false') {
			$this->params['named']['closeWhenDone'] = false;
		} else {
			$this->params['named']['closeWhenDone'] = true;
		}
		
		$currentData = $this->FotomatterBilling->getPaymentProfile();
		$return['html'] = $this->Account->get_add_profile_form($this, $currentData['data'], $this->params['named']['closeWhenDone']);
		$this->return_json($return);
	}
   
	/**
	* Return a html select options for the country id specified
	* @param type $country_id Will return states for this country id
	*/
	public function admin_ajax_get_states_for_country($country_code, $return_json) {
		$states = $this->GlobalCountryState->get_states_by_country_code($country_code);
		if ($return_json) {
			$this->return_json($states);
			exit();
		}

		$result['html'] = $this->element('admin/accounts/state_list', array('country_code'=>$country_code));
		$this->return_json($result);
	}
   
	/**
	* Ajax function that gets called to save client billing and send it to overlord
	* @return This function will either return a error or call ajax_finishLineChange
	*/
	public function admin_ajax_save_client_billing() {
		if (!empty($this->data)) {
			try {
				$this->validatePaymentProfile();
			} catch (Exception $e) {
				if (empty($this->params['named']['closeWhenDone']) === false && $this->params['named']['closeWhenDone'] == 'true') {
					$this->params['named']['closeWhenDone'] = true;
				} else {
					$this->params['named']['closeWhenDone'] = false;
				}
				
				$_SESSION['finalize_features_error'] = __($e->getMessage(), true);
				$_SESSION['finalize_features_payment_data'] = $this->data;
				$return['result'] = false;
				print(json_encode($return));
				exit();
			}

			$this->data['AuthnetProfile']['payment_cc_last_four'] = substr($this->data['AuthnetProfile']['payment_cardNumber'], -4, 4);
			if ($this->FotomatterBilling->save_payment_profile($this->data) === false) {
				$the_data = $this->data;
				$this->major_error('failed to save payment data in ajax_save_client_billing', compact('the_data'));
			}
			unset($_SESSION['finalize_features_payment_data']);

			if (!empty($this->params['named']['closeWhenDone']) && $this->params['named']['closeWhenDone'] == 'true') {
				$this->Session->setFlash(__('Your billing details have been successfully updated.', true), 'admin/flashMessage/success');
				$this->return_json(array('result'=>true));
				exit();
			}

			$return['result'] = true;
			print(json_encode($return));
			exit();
		}
		$this->major_error('admin_ajax_save_client_billing was called without data');
		exit();
	}
   
  
   
	/**
	* Figure out exactly what changed from what they have selected and display that to the user. They will be warned as far
	* as how their bill is changing. 
	* @return Function will get the html for the account_change_finish_element. 
	*/
	public function admin_ajax_finishLineChange() {
		$return['html'] = $this->Account->finish_line_change($this, $this->FotomatterBilling->getPaymentProfile());
		$this->return_json($return);
	}
   
	/**
	* this function is called to send the final account change to overlord
	* @return <html> The html of the summary page
	*/
	public function admin_ajax_finish_account_change() {
		$account_changes = array();
		if ($this->Session->check('final_account_changes')) {
			$account_changes = $this->Session->read('final_account_changes');
		} else {
			$return['code'] = false;
			$this->major_error('Expected account changes not set in session.');
			$this->return_json($return);
		}

		$account_info = array();
		if ($this->Session->check('account_info')){
			$account_info = $this->Session->read('account_info');
		} else {
			$return['code'] = false;
			$this->major_error('Expected to have account_line_items set in session');
			$this->return_json($return);
		}

		$account_info = $this->Account->rekey_account_info($account_info);
		

		foreach($account_changes['checked'] as $key => $item_to_add) {
			$change_to_send['add'][] = $account_info['items'][$key];
		}

		foreach ($account_changes['unchecked'] as $key => $item_to_remove) {
			$change_to_send['remove'][] = $account_info['items'][$key];
		}
		$change_to_send['amount_due_today'] = $this->Account->find_amount_due_today($account_changes, $account_info);
		if ($change_to_send['amount_due_today'] === false) {
			$this->major_error('Someone tried to send billing even though their billing date was in the past, probably a hacking attempt.', $change_to_send, 'high');
			$result['code'] = false;
			$this->return_json($return);
		}

		$return = $this->FotomatterBilling->makeAccountChanges($change_to_send);
		if ($return == false) { // DREW TODO - test this line
			$this->Session->setFlash(__('Your credit card has been declined', true), 'admin/flashMessage/error');
			$result['code'] = false;
			$this->return_json($return);
		} else {
			$this->Session->setFlash(__('New item(s) added successfully', true), 'admin/flashMessage/success');
			$result['code'] = true;
			$this->return_json($return);
		}
		exit();
	}

}