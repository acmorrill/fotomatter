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
		$bill_today = $this->find_amount_due_today($account_changes, $account_info);
		
		
		
		$this->Session->delete('final_account_changes');
		$this->Session->write('final_account_changes', $account_changes);
		
		
		
		///////////////////////////////////////////////////////////////////////////////////////////
		// if a cc card is needed then either collect the cc
		// -- OR give a choice to collect depending on if enough credit is available
		if ($noCCPromoConfirm === false && empty($account_info['Account']['authnet_profile_id'])) {
			if ($account_info['Account']['promo_credit_balance'] >= $bill_today) {
				return $controller->element('admin/accounts/promo_notification_form', compact(array('account_info', 'bill_today', 'current_bill', 'account_changes')));
			} else {
				return $this->get_add_profile_form($controller, $payment_profile['data']);
			}
		}



		
		$return_data = array();
		$return_data = $controller->element('admin/accounts/account_change_finish', array(
			'current_bill' => $current_bill,
			'account_changes' => $account_changes,
			'account_info' => $account_info,
			'bill_today' => $bill_today,
			'payment_profile' => $payment_profile)
		);
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
		$tmp_array = array();
		foreach ($account_info['items'] as $line_item) {
			$tmp_array['items'][$line_item['AccountLineItem']['id']] = $line_item;
		}
		$tmp_array['Account'] = $account_info['Account'];
		return $tmp_array;
	}

	public function find_amount_due_today($account_changes, $account_info) {
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// $whole_month_cost - this is the cost for the month just for the new items
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

		
		///////////////////////////////////////////////////////////////////////////
		// means the next bill date is in the past
		// -- so change the bill date to tomorrow so at least something is charged
		if ($now > $next_billing_date) {
			$this->major_error('Billing date is in the past, probably billing problem.', $account_changes, 'high');
			$next_billing_date = $now + 86400; // date tomorrow
		}

		
		$days_difference = ($next_billing_date - $now) / (60 * 60 * 24);
		
		//////////////////////////////////////////////////////////////////////////////
		// means the next bill date is more than a month away
		// change the bill date to 31 days ahead
		if ($days_difference > 35) {
			$this->major_error('Trying to prorate bill for too many days!', compact('account_changes', 'account_info'), 'high');
			$next_billing_date = $now + 2678400; // date in 31 days
			$days_difference = ($next_billing_date - $now) / (60 * 60 * 24);
		}

		// figure out cost per day for features added
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

}
