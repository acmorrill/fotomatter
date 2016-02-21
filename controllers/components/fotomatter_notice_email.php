<?php

class FotomatterNoticeEmailComponent extends Object {
	
	public $from_email = FOTOMATTER_SUPPORT_EMAIL_REPLYTO;
	
	public function startsWith($haystack, $needle) {
		$length = strlen($needle);
		return (substr($haystack, 0, $length) === $needle);
	}

	public function endsWith($haystack, $needle) {
		$length = strlen($needle);
		$start  = $length * -1; //negative
		return (substr($haystack, $start) === $needle);
	}
	
	public function send_all_unsent_notice_emails(&$controller) {
		$class_methods = get_class_methods($this);
		foreach ($class_methods as $key => &$class_method) {
			if (!$this->startsWith($class_method, 'email_')) {
				unset($class_methods[$key]);
			}
		}
		
		$parsed_class_methods_apc_key = 'notice_emails_parsed_class_methods_apc_key';
		apc_delete($parsed_class_methods_apc_key); // DREW TODO - get rid of this
		if (apc_exists($parsed_class_methods_apc_key)) {
			$parsed_class_methods = apc_fetch($parsed_class_methods_apc_key);
		} else {
			$parsed_class_methods = array();
			foreach ($class_methods as &$class_method) {
				preg_match('/email_([0-9]{1,2})_([0-9]{1,2})_([0-9]{4})_([0-9]{2})_([0-9]{2})_(.*)/', $class_method, $matches);
				$email_month = (int)$matches[1];
				$email_day = (int)$matches[2];
				$email_year = (int)$matches[3];
				$email_hours = (int)$matches[4];
				$email_mins = (int)$matches[5];
				$email_description = $matches[6];
				$timestamp = mktime($email_hours, $email_mins, 0, $email_month, $email_day, $email_year);
				$datetime = date('Y-m-d H:i:s', $timestamp);
				

				$parsed_class_methods[$timestamp]['function'] = $class_method;
				$parsed_class_methods[$timestamp]['email_description'] = $email_description;
				$parsed_class_methods[$timestamp]['datetime'] = $datetime;
			}
			ksort($parsed_class_methods);
			apc_store($parsed_class_methods_apc_key, $parsed_class_methods, 604800); // store for one week
		}
		
		$this->AccountEmailNotice = ClassRegistry::init('AccountEmailNotice');
		$this->SiteSetting = ClassRegistry::init('SiteSetting');
		$time_built = $this->SiteSetting->getVal('time_built', false); // TODO - fail here if need be
		$time_built_timestamp = strtotime($time_built);
		$sent_emails = $this->AccountEmailNotice->get_already_sent_notice_emails($time_built); // TODO - fail here if need be
		
		foreach ($parsed_class_methods as $timestamp => $parsed_class_method) {
			if ($timestamp > $time_built_timestamp && $timestamp < time() && empty($sent_emails[$parsed_class_method['function']])) {
				// send the email
				$notice_data = $this->send_actual_email($controller, $parsed_class_method['function']);
				
//				// save the email in the db
				if ($notice_data !== false) {
					$new_email_notice = array();
					$new_email_notice['AccountEmailNotice']['email_key'] = $parsed_class_method['function'];
					$new_email_notice['AccountEmailNotice']['to_send_date'] = $parsed_class_method['datetime'];
					$new_email_notice['AccountEmailNotice']['sent_date'] = date('Y-m-d H:i:s');
					$new_email_notice['AccountEmailNotice']['email_data'] = print_r($notice_data, true);
					$this->log($new_email_notice, 'new_email_notice');
					$this->AccountEmailNotice->create();
					$this->AccountEmailNotice->save($new_email_notice);
				} else {
					// record a major error here
				}
				break;
			}
		}
		
		
		return true;
	}
	
	
	private function send_actual_email(&$controller, $function) {
		$this->SiteSetting = ClassRegistry::init('SiteSetting');
		$account_email = $this->SiteSetting->getVal('account_email', false);
		$controller->Postmark->delivery = 'postmark';
		$controller->Postmark->from = $this->from_email;
		$controller->Postmark->replyTo = $this->from_email;
		$controller->Postmark->to = "<$account_email>";
		$controller->Postmark->sendAs = 'html'; // because we like to send pretty mail
		$controller->Postmark->tag = $function;
		return $this->$function($controller);
	}
	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// email function format
//	private function email_MM_DD_YYYY_HH_II_descriptionofemail(&$controller) {}
	
//	private function email_02_09_2016_16_30_name_of_email3($email) {
//		
//	}
//	private function email_02_07_2016_16_30_name_of_email3($email) {
//		
//	}
	private function email_02_05_2016_16_30_name_of_email3(&$controller) {
		$controller->Postmark->subject = 'This is a test email';
		$testing_var = 'something cool';
		$controller->set(compact('testing_var'));
		$controller->Postmark->template = 'test_notice_email';
		$result = $controller->Postmark->send();
		return compact('testing_var');
	}
	
}