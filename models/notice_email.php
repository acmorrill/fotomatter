<?php

class NoticeEmail extends AppModel {
	var $name = 'NoticeEmail';
	public $useTable = false;
	
	public function send_all_unsent_notice_emails() {
		$class_methods = get_class_methods($this);
		foreach ($class_methods as $key => &$class_method) {
			if (!$this->startsWith($class_method, 'email_')) {
				unset($class_methods[$key]);
			}
		}
		
//		$email = new CakeEmail();
//		$email->config('postmark');
//		$email->emailFormat('html');
//		$email->template('beta_credit_notice', 'default');
//		$email->to($account_to_bill['Account']['email']);
//		$email->subject(__('$10 credited to your account', true));
//		$email->from(array(
//			"$FOTOMATTER_SUPPORT_EMAIL" => 'Fotomatter'
//		));
//		
//		$email->viewVars(compact('account_to_bill'));
//		$email->send();
		
		
		foreach ($class_methods as &$class_method) {
			preg_match('/email_([0-9]{1,2})_([0-9]{1,2})_([0-9]{4})_([0-9]{2})_([0-9]{2})_(.*)/', $class_method, $matches);
			$email_month = (int)$matches[1];
			$email_day = (int)$matches[2];
			$email_year = (int)$matches[3];
			$email_hours = (int)$matches[4];
			$email_mins = (int)$matches[5];
			$email_description = $matches[6];
			$datetime = date('Y-m-d H:i:s', mktime($email_hours, $email_mins, 0, $email_month, $email_day, $email_year));
			
//			$date_exists = 65
			
			
		}
		
		
		$this->log($class_methods, 'class_methods');
		
		
		// don't send to far into the past!
		
		
		return true;
	}
	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// email function format
//	private function email_MM_DD_YYYY_HH_II_descriptionofemail() {}
	
	private function email_02_09_2016_16_30_name_of_email3() {
		
	}
	private function email_02_07_2016_16_30_name_of_email3() {
		
	}
	private function email_02_05_2016_16_30_name_of_email3() {
		
	}
	

}
