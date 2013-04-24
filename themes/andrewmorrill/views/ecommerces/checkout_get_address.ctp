<?php echo $this->Session->flash(); ?>

<h1>Billing Address</h1>
<style type="text/css">
	/* temp styles */
	#shipping_address_container {
		margin-top: 30px;
		outline: 1px solid black;
		padding: 20px;
	}
	#shipping_address_container label {
		display: inline-block;
		vertical-align: top;
		width: 100px;
		text-align: right;
		margin-right: 10px;
	}
	#shipping_address_container .input {
		margin-bottom: 10px;
		
	}
	#shipping_address_container .submit {
		padding-left: 114px;
	}
</style>
<br/>
<button>Checkout as Guest</button>


'firstName' 
'lastName'
'address'
'city'
'state'
'zip'
'country'
'phoneNumber

// START HERE TOMORROW


<div id="shipping_address_container">
	<form action="" method="post">
		<div class="input">
			<label>First Name:</label> <input type="text" name="firstname" value="" /><br/>
		</div>
		<div class="input">
			<label>Last Name:</label> <input type="text" name="lastname" value="" /><br/>
		</div>
		<div class="input">
			<label>Password:</label> <input type="text" value="" />
		</div>
		<div class="submit">
			<input type="submit" value="Login" />
		</div>
	</form>
	
</div>