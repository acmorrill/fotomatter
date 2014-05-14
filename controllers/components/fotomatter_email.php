<?php

class FotomatterEmailComponent extends Object {
	
	public $from_email = '<support@fotomatter.net>';
	
	// DREW TODO - upgrade this to work with admin users (and change the layout for admin users)
	public function send_forgot_password_email(&$controller, $change_password_user) {
		$modified_hash = openssl_digest($change_password_user['User']['modified'].FORGOT_PASSWORD_SALT, 'sha512');
		$user_id = $change_password_user['User']['id'];
		$return_link = "http://fotomatter.dev/ecommerces/change_fe_password/$user_id/$modified_hash/"; // DREW TODO - change this to the live domain
		
		$controller->set(compact('modified_hash', 'user_id', 'return_link'));
		
		$to_email = $change_password_user['User']['email_address'];
		
		$controller->Postmark->delivery = 'postmark';
		$controller->Postmark->from = $this->from_email;
		$controller->Postmark->replyTo = $this->from_email;
		$controller->Postmark->to = "<$to_email>";
		$controller->Postmark->subject = 'Change Password Requested';
		$controller->Postmark->template = 'forgot_password';
		$controller->Postmark->sendAs = 'html'; // because we like to send pretty mail
		if ($change_password_user['User']['admin'] == true) {
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