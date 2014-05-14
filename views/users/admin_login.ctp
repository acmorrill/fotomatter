<?php
echo $this->element("admin/global_js"); 
echo $form->create('User', array('action' => 'login'));
echo $form->input('email_address',array('between'=>'<br>','class'=>'text'));
echo $form->input('password',array('between'=>'<br>','class'=>'text'));
echo $form->end('Sign In');

?>

<form action="/users/request_admin_password_change" method="post">
	<div class="input">
		<label><?php __('Email'); ?>:</label>
		<input name="data[User][forgot_password_email]" /><br />
	</div>
	<div class="submit">
		<input type="submit" value="Send Forgot Password Email" />
	</div>
</form>
