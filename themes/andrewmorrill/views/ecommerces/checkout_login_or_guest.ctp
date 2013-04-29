<style type="text/css">
	/* temp styles */
	#login_container {
		margin-top: 30px;
		outline: 1px solid black;
		padding: 20px;
	}
	#login_container label {
		display: inline-block;
		vertical-align: top;
		width: 100px;
		text-align: right;
		margin-right: 10px;
	}
	#login_container .input {
		margin-bottom: 10px;
		
	}
	#login_container .submit {
		padding-left: 114px;
	}
</style>
<br/>
<a href="/ecommerces/checkout_get_address"><button><?php __('Checkout as Guest'); ?></button></a>


<div id="login_container">
	<form action="" method="post">
		<div class="input">
			<label><?php __('Email'); ?>:</label> <input type="text" value="" /><br/>
		</div>
		<div class="input">
			<label><?php __('Password'); ?>:</label> <input type="text" value="" />
		</div>
		<div class="submit">
			<input type="submit" value="<?php __('Login'); ?>" />
		</div>
	</form>
	
</div>