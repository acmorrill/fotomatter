<div style='clear: both;'></div>
<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('#forgot_password_link').click(function(e) { 
			e.preventDefault();
			
			jQuery('#forgot_your_password_container .step_1').hide();
			jQuery('#forgot_your_password_container .step_2').show();
		});
	});
</script>
<div id="login_container">
	
	<form id='checkout_as_guest_form' action='/ecommerces/checkout_get_address' method='get'>
		<div class="submit">
			<div id='checkout_as_guest_button' class="frontend_form_submit_button submit_button"><span class='content'><?php echo __('Checkout as Guest', true); ?></span><span class='extra'></span></div>
		</div>
	</form>
	<hr />


	<form action="/ecommerces/checkout_login_or_guest" method="post">
		<?php echo $this->Session->flash(); ?>
		<div class="input">
			<label><?php echo __('Email', true); ?></label> <input type="text" name="data[User][email_address]" value="" /><br/>
		</div>
		<div class="input">
			<label><?php echo __('Password', true); ?></label> <input type="password" name="data[User][password]" value="" />
		</div>
		<div class="submit">
			<div class="frontend_form_submit_button submit_button"><span class='content'><?php echo __('Login', true); ?></span><span class='extra'></span></div>
		</div>
	</form>
	
	
	<div id="forgot_your_password_container" class="input">
		<div class="step_1">
			<a id="forgot_password_link">Forgot your password?</a>
		</div>
		<div class="step_2">
			<hr />
			<form id="send_forgot_password_button" action="/ecommerces/checkout_login_or_guest" method="post">
				<div class="input">
					<label><?php echo __('Email', true); ?></label>
					<input name="data[User][forgot_password_email]" /><br />
				</div>
				<div class="submit">
					<div id="send_forgot_password_button" class="frontend_form_submit_button submit_button"><span class='content'><?php echo __('Send Forgot Password Email', true); ?></span><span class='extra'></span></div>
				</div>
			</form>
		</div>
	</div>
	<hr />
</div>