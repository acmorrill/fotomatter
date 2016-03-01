<?php

class FotomatterNoticeEmailComponent extends Object {
	
	public $from_email = FOTOMATTER_SUPPORT_EMAIL_REPLYTO;
	
	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// ACTUAL NOTICE EMAILS
	// example format: 
	//	public function email_MM_DD_YYYY_HH_II_descriptionofemail(&$controller) {}
	//	whatever is returned gets logged
	//--------------------------------------------------------------------------------------------------------------------------------------------
	public function email_03_01_2016_12_30_going_out_of_beta_email(&$controller) {
		$this->SiteSetting = ClassRegistry::init('SiteSetting');
		$first_name = $this->SiteSetting->getVal('first_name', '');
		$site_domain = $this->SiteSetting->getVal('site_domain');
		$controller->Postmark->subject = 'Fotomatter.net Beta Survey!';
		$controller->set(compact('first_name', 'site_domain'));
		$controller->Postmark->template = 'notice_emails/going_out_of_beta_survey';
		return compact('first_name', 'site_domain');
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// HELPER FUNCTIONS
	//--------------------------------------------------------------------------------------------------------------------------------------------
	public function startsWith($haystack, $needle) { $length = strlen($needle); return (substr($haystack, 0, $length) === $needle); }
	
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
		$time_built = $this->SiteSetting->getVal('time_built', false);
		if ($time_built !== false) {
			$time_built_timestamp = strtotime($time_built);
			$sent_emails = $this->AccountEmailNotice->get_already_sent_notice_emails($time_built);

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
						$this->AccountEmailNotice->create();
						$this->AccountEmailNotice->save($new_email_notice);
					} else {
						$this->SiteSetting->major_error('failed to send actual email in fotomatter notice emails');
						return false;
					}
					break;
				}
			}
		} else {
			$this->SiteSetting->major_error('time_build not set for fotomatter notice emails');
			return false;
		}
		
		
		return true;
	}
	
	
	public function send_actual_email(&$controller, $function) {
		$this->SiteSetting = ClassRegistry::init('SiteSetting');
		$account_email = $this->SiteSetting->getVal('account_email', false);
		$controller->Postmark->delivery = 'postmark';
		$controller->Postmark->from = $this->from_email;
		$controller->Postmark->replyTo = $this->from_email;
		$controller->Postmark->to = "<$account_email>";
		$controller->Postmark->sendAs = 'html'; // because we like to send pretty mail
		$controller->Postmark->tag = $function;
		$return_values = $this->$function($controller);
		$result = $controller->Postmark->send();
		if (!isset($result['ErrorCode']) || $result['ErrorCode'] != 0) {
			$controller->major_error('failed to send notice email', compact('function', 'result'));
			return false;
		}
		return $return_values;
	}
	
}