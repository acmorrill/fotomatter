<h1>Create Your Password</h1>
<form action="/admin/welcome/create_password" method="POST">
	<label>Login</label> <?php echo $account_email; ?><br />
	<label>Password</label> <input type="password" name="data[password]" /><br />
	<label>Confirm Password</label> <input type="password" name="data[confirm_password]" /><br />
	<input type="submit" value="Submit" />
</form>