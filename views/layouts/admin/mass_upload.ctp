<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<title><?php __('Pages'); ?></title>
	<?php echo $this->Element('admin/global_includes'); ?>
	<?php echo $this->Element('admin/global_js'); ?>
	
	<!-- Force latest IE rendering engine or ChromeFrame if installed -->
	<!--[if IE]>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<![endif]-->
	
	<?php echo $this->Element('admin/photo/jquery_fileupload_includes'); ?>
</head>
<body>
<div id="main" class="mass_upload">
	<div id="header">
		<?php echo $this->Element('admin/logo'); ?>
		<?php echo $this->Element('admin/menu', array( 'curr_page' => $curr_page )); ?>
	</div>
	<div id="middle" class="rounded-corners">
		<?php echo $this->Session->flash(); ?>
		<?php echo $content_for_layout; ?>
	</div>
	<div id="footer"></div>
</div>
<div id="admin_background"></div>

</body>
</html>