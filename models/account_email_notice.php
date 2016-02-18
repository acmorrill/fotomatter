<?php

class AccountEmailNotice extends AppModel {
	var $name = 'AccountEmailNotice';
	
	public function get_already_sent_notice_emails($time_built) {
		$query = "
			SELECT * FROM account_email_notices
			WHERE to_send_date > '$time_built'
		";
		$sent_emails = $this->query($query);
		
		$this->log($sent_emails);
		return $sent_emails;
	}
	

}
