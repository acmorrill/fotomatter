<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<title><?php echo __('Login', true); ?></title>
	<?php echo $this->Element('admin/global_includes'); ?>
	<?php echo $this->Element('admin/global_js'); ?>
</head>
<body>
<div id="main" class='no_subnav login_page'>
	<div id="header">
		<?php echo $this->Element('admin/logo'); ?>
		<div id='login_tagline'><?php echo __('Photo Management Made Simple', true); ?></div>
	</div>
	<div id="middle">
		<div id='login_forms_cont'>
			<table>
				<tbody>
					<tr>
						<td>
							<?php echo $content_for_layout; ?>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<?php echo $this->Element('admin/global_footer'); ?>
</div>
<?php echo $this->Element('admin/global_after_footer'); ?>
</body>
</html>