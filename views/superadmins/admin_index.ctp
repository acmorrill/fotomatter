<h1><?php echo __('Super Admin Tools', true); ?></h1>
<p><?php echo __("Don't break anything OR ELSE!!!!", true); ?></p>

<ol style="font-size: 30px;" class="custom_ui">
	<li><a href="/admin/superadmins/delete_all_photo_caches">Delete All Photo Caches</a></li>
	<li><a href="/admin/superadmins/unlink_local_master_caches">Clear Local Master Cache Files</a></li>
	<li>TODO - clear all apc (db, overlord account, billing etc) and view cache</li>
	<li>
		<form method="post" action="https://fotomatter.net:7859/index.php?db=<?php echo $_SERVER['local']['database']; ?>" name="login_form" target="_blank">
			<input type="hidden" name="pma_username" id="input_username" value="<?php echo $_SERVER['local']['login']; ?>" size="24" class="textfield">
			<input type="hidden" name="pma_password" id="input_password" value="<?php echo $_SERVER['local']['password']; ?>" size="24" class="textfield">

			<input type="hidden" name="server" value="1">
			<input type="hidden" name="target" value="https://fotomatter.net:7859/index.php">
			
			<div id="upload_photos" class="add_button minor_highlight javascript_submit">
				<div class="content">Login To DB</div>
				<div class="right_arrow_lines icon-arrow-01"><div></div></div>
			</div>
		</form>
	</li>
</ol>




<?php /*
<br />
<br />
<br />
<div class="generic_palette_container">
	<div class="fade_background_top"></div>
	<div class="generic_inner_container">
		<div class="generic_dark_cont fotomatter_form">
			<h1>Login to Database</h1>
			<form method="post" action="https://fotomatter.net:7859/index.php?db=<?php echo $_SERVER['local']['database']; ?>" name="login_form" target="_blank">
				<div class="input text">
					<label for="input_username">Username:</label>
					<input type="text" name="pma_username" id="input_username" value="<?php echo $_SERVER['local']['login']; ?>" size="24" class="textfield">
				</div>
				<div class="input text">
					<label for="input_password">Password:</label>
					<input type="password" name="pma_password" id="input_password" value="<?php echo $_SERVER['local']['password']; ?>" size="24" class="textfield">
				</div>

				<input type="hidden" name="server" value="1">
				<input type="hidden" name="target" value="https://fotomatter.net:7859/index.php">

				<div class="input submit">
					<input value="Login To DB" type="submit" id="input_go">
				</div>
			</form>
		</div>
	</div>
</div>
 * 
 */  ?>
