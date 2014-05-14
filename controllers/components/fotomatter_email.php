<?php

class FotomatterEmailComponent extends Object {
	
	public $from_email = '<support@fotomatter.net>';
	
	// DREW TODO - upgrade this to work with admin users (and change the layout for admin users)
	public function send_forgot_password_email(&$controller, $change_password_user) {
		$this->SiteSetting = ClassRegistry::init('SiteSetting');
		$site_domain = $this->SiteSetting->getVal('site_domain', false);
		
		$modified_hash = openssl_digest($change_password_user['User']['modified'].FORGOT_PASSWORD_SALT, 'sha512');
		$user_id = $change_password_user['User']['id'];
		if ($change_password_user['User']['admin'] == 1) {
			$return_link = "http://$site_domain.fotomatter.net/users/change_admin_password/$user_id/$modified_hash/"; 
		} else {
			$return_link = "http://$site_domain.fotomatter.net/ecommerces/change_fe_password/$user_id/$modified_hash/"; 
		}
		
		$controller->set(compact('modified_hash', 'user_id', 'return_link'));
		
		$to_email = $change_password_user['User']['email_address'];
		
		$controller->Postmark->delivery = 'postmark';
		$controller->Postmark->from = $this->from_email;
		$controller->Postmark->replyTo = $this->from_email;
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
}