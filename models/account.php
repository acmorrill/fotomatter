<?php

class Account extends AppModel {

	public $useTable = false;

	public function set_item_checked($line_item_id, $checked) {
		$this->Session = $this->get_session();
		$line_items = $this->Session->read('account_line_items');

		if ($checked) {
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

		return true;
	}

	public function finish_line_change(&$controller, $payment_profile, $noCCPromoConfirm) {
		//If payment needed collect authnet 
		$this->Session = $this->get_session();

		
		/////////////////////////////////////////////////////////////////////////////////////////////
		// account info is the account info from overlord side
		// -- this is set in /admin/accounts/index
		//
		// account_line_items ($account_changes)
		// -- this is set in account.php - set_item_checked
		// -- this is basically the items that are pending add (deletes happen on reload)
		$account_info = $this->Session->read('account_info');
		$this->log($account_info, 'new_account_info');
		$account_changes = $this->Session->read('account_line_items');
		

		////////////////////////////////////////////////////////////////////////////////////////
		// rekey account items - this just changes the items array keys to match the account_line_item id's
		$account_info = $this->rekey_account_info($account_info);

		
		////////////////////////////////////////////////////////////////////////////////////////
		// calculate the current bill based on all active items
		// -- this does not count new items
		$current_bill = 0;
		foreach ($account_info['items'] as $line_item) {
			if ($line_item['AccountLineItem']['active']) {
				$current_bill += $line_item['AccountLineItem']['customer_cost'];
			}
		}
//		$this->log($current_bill, 'finish_line_change');

		//////////////////////////////////////////////////////////
		// compare checked items
		// -- this just assures that you can't add an item you already have
		foreach ($account_changes['checked'] as $id => $change) {
			if ($account_info['items'][$id]['AccountLineItem']['active']) {
				unset($account_changes['checked'][$id]);
			}
		}
		
		/////////////////////////////////////////////////////////////////////////////////////////////
		// compare unchecked items
		// -- this just assures you can't uncheck items you don't have
		foreach ($account_changes['unchecked'] as $id => $change) {
			if ($account_info['items'][$id]['AccountLineItem']['active'] == false) {
				unset($account_changes['unchecked'][$id]);
			}
		}

		
		////////////////////////////////////////////////////////////////////////////////////////////
		// figure out the prorated bill today
		// -- this is the prorated amount for just the new items as
		// -- as other items are charged in the monthly bill
		$items_to_add = array();
		foreach ($account_changes['checked'] as $id => $change) {
			$items_to_add[] = $account_info['items'][$id];
		}
		$bill_today_data = $controller->FotomatterBilling->find_amount_due_today($items_to_add);
		
		
		
		$this->Session->delete('final_account_changes');
		$this->Session->write('final_account_changes', $account_changes);
		
		
		
		///////////////////////////////////////////////////////////////////////////////////////////
		// if a cc card is needed then either collect the cc
		// -- OR give a choice to collect depending on if enough credit is available
		// -- OR is free account
		if ($noCCPromoConfirm === false && empty($account_info['Account']['authnet_profile_id'])) {
			// if have free account then give free notice
			if (!empty($account_info['is_free_account'])) {
				return $controller->element('admin/accounts/free_notification_form', compact(array('account_info', 'current_bill', 'account_changes')));
			}
			
			// if have enough promo money then give promo notice
			if (empty($bill_today_data['total'])) {
				$bill_today = $bill_today_data['amount'];
				return $controller->element('admin/accounts/promo_notification_form', compact(array('account_info', 'bill_today', 'current_bill', 'account_changes')));
			}
			
			return $this->get_add_profile_form($controller, $payment_profile['data']);
		}



		
		$return_data = array();
		$return_data = $controller->element('admin/accounts/account_change_finish', array(
			'current_bill' => $current_bill,
			'account_changes' => $account_changes,
			'account_info' => $account_info,
			'bill_today' => $bill_today_data['amount'], // the amount you would pay without promo
			'bill_today_promo' => $bill_today_data['promo_total'], // the amount of promo being used
			'total' => $bill_today_data['total'], // the actual total you have to pay
			'payment_profile' => $payment_profile,
		));
		return $return_data;
	}

	public function get_add_profile_form(&$controller, $current_data = array(), $closeWhenDone = false) {
		$return = array();
		$countries = $controller->GlobalCountry->get_available_countries();
		
		
		if (isset($_SESSION['finalize_features_payment_data'])) {
			foreach ($_SESSION['finalize_features_payment_data']['AuthnetProfile'] as $payment_data_name => $payment_data_value) {
				if (isset($current_data['AuthnetProfile'][$payment_data_name])) {
					$current_data['AuthnetProfile'][$payment_data_name] = $payment_data_value;
				}
			}
			unset($_SESSION['finalize_features_payment_data']);
		}
		
		return $controller->element('admin/accounts/add_profile', compact(array('countries', 'current_data', 'closeWhenDone')));
	}

	public function rekey_account_info($account_info) {
		//rekey the original array
		$tmp_array = $account_info;
		unset($tmp_array['items']);
		foreach ($account_info['items'] as $line_item) {
			$tmp_array['items'][$line_item['AccountLineItem']['id']] = $line_item;
		}
		return $tmp_array;
	}

}
