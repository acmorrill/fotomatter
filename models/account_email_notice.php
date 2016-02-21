<?php

class AccountEmailNotice extends AppModel {
	var $name = 'AccountEmailNotice';
	
	public function get_already_sent_notice_emails($time_built) {
		$query = "
			SELECT * FROM account_email_notices
			WHERE to_send_date > '$time_built'
		";
		$sent_emails = $this->query($query);
		$final_sent_emails = array();
		foreach ($sent_emails as &$sent_email) {
			$final_sent_emails[$sent_email['account_email_notices']['email_key']] = $sent_email['account_email_notices'];
		}
		
		return $final_sent_emails;
	}
	

}
