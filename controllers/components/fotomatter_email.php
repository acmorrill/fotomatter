<?php

class FotomatterEmailComponent extends Object {
	
	public $from_email = FOTOMATTER_SUPPORT_EMAIL_REPLYTO;
	
	
	public function send_first_login_email(&$controller) {
		$this->SiteSetting = ClassRegistry::init('SiteSetting');
		
		$account_email = $this->SiteSetting->getVal('account_email', false);
		$site_domain = $this->SiteSetting->getVal('site_domain', false);
		$controller->set(compact('site_domain'));
		
		
		if ($account_email === false) {
			$controller->major_error('failed to send first login email 1', compact('site_domain'));
			return false;
		}
		
		$controller->Postmark->delivery = 'postmark';
		$controller->Postmark->from = "$this->from_email";
		$controller->Postmark->replyTo = "$this->from_email";
		$controller->Postmark->to = "<$account_email>";
		$controller->Postmark->subject = "Congrats! Your website is built - below are some useful links to keep in mind for the future.";
		$controller->Postmark->template = 'first_login_congrats';
		$controller->Postmark->sendAs = 'html'; // because we like to send pretty mail
		$controller->Postmark->tag = 'first_login_congrats';
		$result = $controller->Postmark->send();

		if (!isset($result['ErrorCode']) || $result['ErrorCode'] != 0) {
			$controller->major_error('failed to send first login email 2', compact('site_domain'));
			return false;
		}
		
		return true;
	}
	
	public function send_end_user_contact_us_email(&$controller, $end_user_data) {
		$this->SiteSetting = ClassRegistry::init('SiteSetting');
		
		$account_email = $this->SiteSetting->getVal('account_email', false);
		$site_domain = $this->SiteSetting->getVal('site_domain', false);
		$website_url = $site_domain . ".fotomatter.net";
		$controller->set(compact('account_email', 'website_url', 'end_user_data'));
		
		if ($account_email === false) {
			$controller->major_error('failed to send end user contact us email 1', compact('website_url', 'end_user_data'));
			return false;
		}
		
		$controller->Postmark->delivery = 'postmark';
		$controller->Postmark->from = "$this->from_email";
		$controller->Postmark->replyTo = "<{$end_user_data['contact_us_email']}>";
		$controller->Postmark->to = "<$account_email>";
		$controller->Postmark->subject = "fotomatter.net contact email from {$end_user_data['contact_us_name']} ({$end_user_data['contact_us_email']})";
		$controller->Postmark->template = 'end_user_contact_us';
		$controller->Postmark->sendAs = 'html'; // because we like to send pretty mail
		$controller->Postmark->tag = 'frontend_contact_us';
		$result = $controller->Postmark->send();

		if (!isset($result['ErrorCode']) || $result['ErrorCode'] != 0) {
			$controller->major_error('failed to send end user contact us email 2', compact('result', 'website_url', 'end_user_data'));
			return false;
		}
		
		return true;
	}
	
	
	public function send_cron_working_email(&$controller) {
		$this->SiteSetting = ClassRegistry::init('SiteSetting');
		
		$account_email = $this->SiteSetting->getVal('account_email', false);
		$site_domain = $this->SiteSetting->getVal('site_domain', false);
		$controller->set(compact('account_email', 'site_domain'));
		
		if ($account_email !== false && $site_domain !== false) {
			$controller->Postmark->delivery = 'postmark';
			$controller->Postmark->from = "$this->from_email";
			$controller->Postmark->replyTo = "$this->from_email";
			$controller->Postmark->to = "<$account_email>";
			$controller->Postmark->subject = 'Your Crons are Working! :)';
			$controller->Postmark->template = 'cron_working';
			$controller->Postmark->sendAs = 'html'; // because we like to send pretty mail
			$controller->Postmark->tag = 'admin_test_cron_working';
			$result = $controller->Postmark->send();

			if (!isset($result['ErrorCode']) || $result['ErrorCode'] != 0) {
				$controller->major_error('failed to send crons working email 1', compact('result', 'account_email', 'site_domain'));
			}
		} else {
			$controller->major_error('failed to send crons working email 2', compact('account_email', 'site_domain'));
		}
	}
	
	public function send_forgot_password_email(&$controller, $change_password_user) {
		$this->SiteSetting = ClassRegistry::init('SiteSetting');
		$site_domain = $this->SiteSetting->getVal('site_domain', false);
		
		$modified_hash = openssl_digest($change_password_user['User']['modified'].FORGOT_PASSWORD_SALT, 'sha512');
		$user_id = $change_password_user['User']['id'];
		if ($change_password_user['User']['admin'] == 1) {
			$return_link = "https://$site_domain.fotomatter.net/users/change_admin_password/$user_id/$modified_hash/"; 
		} else {
			$return_link = "https://$site_domain.fotomatter.net/ecommerces/change_fe_password/$user_id/$modified_hash/"; 
		}
		
		$controller->set(compact('modified_hash', 'user_id', 'return_link'));
		
		$to_email = $change_password_user['User']['email_address'];
		
		$controller->Postmark->delivery = 'postmark';
		$controller->Postmark->from = "$this->from_email";
		$controller->Postmark->replyTo = "$this->from_email";
		$controller->Postmark->to = "<$to_email>";
		$controller->Postmark->subject = 'Change Password Requested';
		if ($change_password_user['User']['admin'] == 1) {
			$controller->Postmark->template = 'forgot_password_admin';
		} else {
			$controller->Postmark->template = 'forgot_password_frontend';
		}
		$controller->Postmark->sendAs = 'html'; // because we like to send pretty mail
		if ($change_password_user['User']['admin'] == 1) {
			$controller->Postmark->tag = 'admin_user_forgot_password';
		} else {
			$controller->Postmark->tag = 'end_user_forgot_password';
		}
		$result = $controller->Postmark->send();
		
		if (!isset($result['ErrorCode']) || $result['ErrorCode'] != 0) {
			$controller->major_error('failed to send forgot password email', compact('result', 'change_password_user', 'return_link'));
		}
	}
	
	public function send_domain_renew_reminder_email(&$controller, $data_to_send) {
		$this->SiteSetting = ClassRegistry::init('SiteSetting');
		$account_email = $this->SiteSetting->getVal('account_email', false);
		$site_domain = $this->SiteSetting->getVal('site_domain', false);
		$login_url = "https://$site_domain.fotomatter.net/admin/domains"; 
		
		$controller->set(compact('data_to_send', 'login_url'));
		
		$controller->Postmark->delivery = 'postmark';
		$controller->Postmark->from = $this->from_email;
		$controller->Postmark->replyTo = $this->from_email;
		$controller->Postmark->to = "<$account_email>";
		$controller->Postmark->subject = 'Fotomatter domain expiration notice';
		$controller->Postmark->template = 'domain_renew_reminder';
		$controller->Postmark->sendAs = 'html'; // because we like to send pretty mail
		$controller->Postmark->tag = 'domain_renewal_reminder';
		$result = $controller->Postmark->send();
		
		if (!isset($result['ErrorCode']) || $result['ErrorCode'] != 0) {
			$controller->major_error('failed to send domain_renewal reminder email', compact('result', 'change_password_user', 'return_link'));
		}
	}
	
	public function send_create_support_ticket_email(&$controller, $subject, $issue) {
		$this->SiteSetting = ClassRegistry::init('SiteSetting');
		$account_email = $this->SiteSetting->getVal('account_email', false);
		$first_name = $this->SiteSetting->getVal('first_name', '');
		$last_name = $this->SiteSetting->getVal('last_name', '');
		$site_domain = $this->SiteSetting->getVal('site_domain', '');
		
		$controller->set(compact('account_email', 'site_domain', 'first_name', 'last_name', 'issue'));
		
		$controller->Postmark->delivery = 'postmark';
		$controller->Postmark->from = $this->from_email;
		$controller->Postmark->replyTo = $account_email;
		$controller->Postmark->to = "<support+livechat@fotomatter.net>";
		$controller->Postmark->subject = $subject;
		$controller->Postmark->template = 'support_create_ticket';
		$controller->Postmark->sendAs = 'text';
		$controller->Postmark->tag = 'support_create_ticket';
		$result = $controller->Postmark->send();
		
		if (!isset($result['ErrorCode']) || $result['ErrorCode'] != 0) {
			$controller->major_error('failed to send support_create_ticket reminder email', compact('account_email', 'site_domain', 'first_name', 'last_name'));
		}
	}
}