<?php
class AccountsController extends AppController {
    
	public $uses = array('GlobalCountry', 'GlobalCountryState', 'Photo', 'SitePage', 'PhotoGallery');

	public $layout = 'admin/accounts';
   
	public $components = array(
		'FotomatterBilling',
		'Session',
		'Validation',
	);
   
	public function admin_account_details() {
		$accountDetails = $this->FotomatterBilling->getAccountDetails();
		$photo_count = $this->Photo->find('count');
		$site_page_count = $this->SitePage->find('count');
		$photo_gallery_count = $this->PhotoGallery->find('count');

		$this->set('accountDetails', $accountDetails['data']);
		$this->set(compact('photo_count', 'site_page_count', 'photo_gallery_count'));
	}
    
	/**
	* action for the page to add/remove line items. 
	* @author Adam Holsinger
	*/
	public function admin_index() {
		$overlord_account_info = $this->FotomatterBilling->get_account_info();
		
		$this->Session->delete('account_line_items');
		$this->Session->write('account_line_items', array('checked'=>array(), 'unchecked'=>array()));

		$this->Session->delete('account_info');
		$this->Session->write('account_info', $overlord_account_info);

		$this->set(compact(array('overlord_account_info')));

		$this->layout = 'admin/accounts';
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
			$this->Session->setFlash(__('There has been a problem while undoing your cancellation. Please contact us at support@fotomatter.net for help.', true), 'admin/flashMessage/error');
			$this->return_json(array('code'=>false));
		}
		exit();
	}
   
	   public function admin_ajax_remove_item($line_item_id) {
		   $result = $this->FotomatterBilling->remove_item($line_item_id);
		   if($result['code']){
			   $this->return_json(array('code'=>true));
		   } else {
			   $this->Session->setFlash(__('There has been a problem while undoing your cancellation. Please contact us at support@fotomatter.net for help.', true), 'admin/flashMessage/error');
			   $this->return_json(array('code'=>false));
		   }
		   exit();
	   }
   
	/**
	* Gets called when a line item is selected or deselected
	* @return Json indicating that we have recorded whether that item is checked on unchecked
	* @author Adam Holsinger
	*/
	public function admin_ajax_setItemChecked() {
		$line_items = $this->Session->read('account_line_items');

		$line_item_id = $this->params['form']['id'];
		if ($this->params['form']['checked']) {
			if (isset($line_items['unchecked'][$line_item_id])) {
				unset($line_items['unchecked'][$line_item_id]);
			}
			$line_items['checked'][$line_item_id] = 'checked';
		} else {
			if (isset($line_items['checked'][$line_item_id])) {
				unset($line_items['checked'][$line_item_id]);
			}
			$line_items['unchecked'][$line_item_id] = 'unchecked';
		}
		$this->Session->write('account_line_items', $line_items);
		print(json_encode(array('code'=>true)));
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
	$return['html'] = $this->get_add_profile_form($currentData['data'], $this->params['named']['closeWhenDone']);
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
	* @author Adam Holsinger
	*/
	public function admin_ajax_save_client_billing() {
		if (empty($this->data) == false) {
			try {
				$this->validatePaymentProfile();
			} catch (Exception $e) {
				if (empty($this->params['named']['closeWhenDone']) === false && $this->params['named']['closeWhenDone'] == 'true') {
				$this->params['named']['closeWhenDone'] = true;
				} else {
				$this->params['named']['closeWhenDone'] = false;
				}
				$return['html'] = $this->get_add_profile_form($this->data,$this->params['named']['closeWhenDone'], $e->getMessage());
				$return['message'] = $e->getMessage();
				$return['result'] = false;
				print(json_encode($return));
				exit();
			}

			$this->data['AuthnetProfile']['payment_cc_last_four'] = substr($this->data['AuthnetProfile']['payment_cardNumber'], -4, 4);
			$profile_id = $this->FotomatterBilling->save_payment_profile($this->data);

			if (empty($this->params['named']['closeWhenDone']) === false && $this->params['named']['closeWhenDone'] == 'true') {
				$this->Session->setFlash(__('Your billing details have been successfully updated.', true), 'admin/flashMessage/success');
				$this->return_json(array('result'=>true));
				exit();
			}

			$account_info = $this->Session->read('account_info');
			$account_info['Account']['authnet_profile_id'] = $profile_id;
			$this->Session->write('account_info', $account_info);
			$this->admin_ajax_finishLineChange();
			exit();
		}
		$this->major_error('admin_ajax_save_client_billing was called without data');
		exit();
	}
   
	private function get_add_profile_form($current_data=array(), $closeWhenDone=false, $error_message='') {
		$return = array();
		$countries = $this->GlobalCountry->get_available_countries();   
		return $this->element('admin/accounts/add_profile', compact(array('countries', 'current_data', 'closeWhenDone', 'error_message')));
	}
   
	private function rekey_account_info($account_info) {
		//rekey the original array
		$tmp_array = array();
		$current_bill = 0;
		foreach($account_info['items'] as $line_item) {
			if ($line_item['AccountLineItem']['active']) {
				$current_bill += $line_item['AccountLineItem']['current_cost'];
			}
			$tmp_array['items'][$line_item['AccountLineItem']['id']] = $line_item;
		}
		$tmp_array['Account'] = $account_info['Account'];
		return $tmp_array;
	}
   
	private function findAmountDueToday($account_changes, $account_info) {
		//add everything being added
		$whole_month_cost = 0;
		foreach ($account_changes['checked'] as $id => $change) {
		   $whole_month_cost += $account_info['items'][$id]['AccountLineItem']['current_cost'];
		}

		if (empty($account_info['Account']['next_bill_date'])) {
			return $whole_month_cost;
		}

		//figure out how many days we are billing for
		$next_billing_date = strtotime($account_info['Account']['next_bill_date']);
		$now = time();

		if ($now > $next_billing_date) {
			$this->major_error('Billing date is in the past, probably billing problem.', $account_changes, 'high');
			return 0;
		}

		$days_difference = ($next_billing_date - $now) / (60 * 60 * 24);
		if ($days_difference > 35) {
			$this->major_error('Trying to prorate bill for too many days!', compact('account_changes', 'account_info'), 'high');
			return 0;
		}

		//figure out cost per day for features added
		$year_cost_for_features_added = $whole_month_cost * 12;
		$cost_per_day = $year_cost_for_features_added / 365;
		$pro_rated_amount = round($days_difference * $cost_per_day, 2);
		
		if ($pro_rated_amount > $whole_month_cost) {
			$this->major_error('Prorated monthly cost higher than monthly cost!', compact('account_changes', 'account_info'), 'high');
			return $whole_month_cost;
		} else {
			return $pro_rated_amount;
		}
	}
   
   
	/**
	* Figure out exactly what changed from what they have selected and display that to the user. They will be warned as far
	* as how their bill is changing. 
	* @return Function will get the html for the account_change_finish_element. 
	* @author Adam Holsinger
	*/
	public function admin_ajax_finishLineChange() {
		//If payment needed collect authnet 

		$account_info = $this->Session->read('account_info');
		$account_changes = $this->Session->read('account_line_items');


		$account_info = $this->rekey_account_info($account_info);
		$current_bill = 0;
		foreach($account_info['items'] as $line_item) {
			if ($line_item['AccountLineItem']['active']) {
				$current_bill += $line_item['AccountLineItem']['customer_cost'];
			}
		}

		//compare checked items
		foreach ($account_changes['checked'] as $id => $change) {
			if ($account_info['items'][$id]['AccountLineItem']['active']) {
				unset($account_changes['checked'][$id]);
			}
		}

		//compare unchecked items
		foreach ($account_changes['unchecked'] as $id => $change) {
			if ($account_info['items'][$id]['AccountLineItem']['active'] == false) {
				unset($account_changes['unchecked'][$id]);
			}
		}

		$bill_today = $this->findAmountDueToday($account_changes, $account_info);
//		$this->log('===============', 'authnet_profile');
//		$this->log($bill_today, 'authnet_profile');
//		$this->log('===============', 'authnet_profile');
//		$this->log($account_info['Account']['promo_credit_balance'], 'authnet_profile');

		if (empty($this->params['named']['noCCPromoConfirm']) && empty($account_info['Account']['authnet_profile_id'])) {
			if ($account_info['Account']['promo_credit_balance'] >= $bill_today) {
				$return = array();
				$return['html'] = $this->element('admin/accounts/promo_notification_form', compact(array('account_info', 'bill_today', 'current_bill', 'account_changes')));
				print(json_encode($return));
				exit();
			} else {
				$return = array();
				$return['html'] = $this->get_add_profile_form();
				print(json_encode($return));
				exit();
			}
		}
		

		$this->Session->delete('final_account_changes');
		$this->Session->write('final_account_changes', $account_changes);
		$payment_profile = $this->FotomatterBilling->getPaymentProfile();

		$return = array();
		$return['html'] = $this->element('admin/accounts/account_change_finish', array(
			'current_bill'=>$current_bill,
			'account_changes'=>$account_changes,
			'account_info'=>$account_info,
			'bill_today'=>$bill_today,
			'payment_profile'=>$payment_profile)
		);
		print(json_encode($return));
		exit();
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

		$account_info = $this->rekey_account_info($account_info);
		

		foreach($account_changes['checked'] as $key => $item_to_add) {
			$change_to_send['add'][] = $account_info['items'][$key];
		}

		foreach ($account_changes['unchecked'] as $key => $item_to_remove) {
			$change_to_send['remove'][] = $account_info['items'][$key];
		}
		$change_to_send['amount_due_today'] = $this->findAmountDueToday($account_changes, $account_info);
		if ($change_to_send['amount_due_today'] === false) {
			$this->major_error('Someone tried to send billing even though their billing date was in the past, probably a hacking attempt.', $change_to_send, 'high');
			$result['code'] = false;
			$this->return_json($return);
		}

		$return = $this->FotomatterBilling->makeAccountChanges($change_to_send);
		if ($return == false) {
			$this->Session->setFlash(__('Your credit card has been declined, if you need help please contact us at support@fotomatter.net for help.', true), 'admin/flashMessage/error');
			$result['code'] = false;
			$this->return_json($return);
		} else {
			$this->Session->setFlash(__('We have successfully added new ala-cart items to your account.', true), 'admin/flashMessage/success');
			$result['code'] = true;
			$this->return_json($return);
		}
		exit();
	}

}