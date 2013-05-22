<?php
class FotomatterEmailComponent extends Object {

	public function send_forgot_password_email(&$controller, $change_password_user) {
		$salt = 'a0YngDg079JmYJ5ahCxWV6PFovsyGn';
		
		$modified_hash = openssl_digest($change_password_user['User']['modified'].$salt, 'sha512');
		$user_id = $change_password_user['User']['id'];
		$return_link = "http://fotomatter.dev/ecommerces/change_frontend_password/$user_id/$modified_hash/"; // DREW TODO - change this to the live domain
		
		
		$controller->set(compact('modified_hash', 'user_id', 'return_link'));
		
		$controller->Email->reset();
		$controller->Email->smtpOptions = array(
			 'port'=>'465',
			 'timeout'=>'30',
			 'host' => 'ssl://smtp.gmail.com',
			 'username' => 'support@fotomatter.net',
			 'password' => '0923587kK',
		);
		$controller->Email->to = $change_password_user['User']['email_address'];
		$controller->Email->bcc = array('richykimball@gmail.com');
		$controller->Email->subject = 'Change Password';
		$controller->Email->replyTo = 'support@fotomatter.net';
		$controller->Email->from = '"Support" <support@fotomatter.net>';
		$controller->Email->template = 'forgot_password'; // note no '.ctp'
		$controller->Email->delivery = 'smtp';
		//Send as 'html', 'text' or 'both' (default is 'text')
		$controller->Email->sendAs = 'html'; // because we like to send pretty mail
		
		//Do not pass any args to send()
		$controller->Email->send();
		
		/* Check for SMTP errors. */
		if (!empty($controller->Email->smtpError)) {
			$controller->major_error('Failed to send smtp email.', array('email_errors' => $controller->Email->smtpError));
		}
	}
}