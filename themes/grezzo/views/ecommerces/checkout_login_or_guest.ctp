
<br/>
<div class="guest_button"
	 <a href="/ecommerces/checkout_get_address"><button><?php echo __('Checkout as Guest',true); ?></button></a>
</div>
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
	<form action="/ecommerces/checkout_login_or_guest" method="post">
		<?php echo $this->Session->flash(); ?>
		<div class="input">
			<label><?php echo __('Email',true); ?>:</label> <input type="text" name="data[User][email_address]" value="" /><br/>
		</div>
		<div class="input">
			<label><?php echo __('Password',true); ?>:</label> <input type="password" name="data[User][password]" value="" />
		</div>
		<div class="submit">
			<input type="submit" value="<?php echo __('Login'); ?>" />
		</div>
	</form>
	<div id="forgot_your_password_container">
		<div class="step_1 submit">
			<a id="forgot_password_link"><?php echo __('Forgot your password?',true); ?></a>
		</div>
		<div class="step_2">
			<hr />
			<form action="/ecommerces/checkout_login_or_guest" method="post">
				<div class="input">
					<label><?php echo __('Email',true); ?>:</label>
					<input name="data[User][forgot_password_email]" /><br />
				</div>
				<div class="submit">
					<input type="submit" value="<?php echo __('Send Forgot Password Email',true); ?>" />
				</div>
			</form>
		</div>
	</div>


</div>