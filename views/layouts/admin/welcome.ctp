<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<title><?php __('Admin Dashboard'); ?></title>
	<script type="text/javascript" src="/js/jquery-1.7.1.min.js"></script>
	<?php echo $this->Element('admin/global_includes'); ?>
	<?php echo $this->Element('admin/global_js'); ?>
</head>
<body>
<div id="main">
	<div id="header">
		<?php //echo $this->Element('admin/logo'); ?>
	</div>
	<div id="middle" class="rounded-corners">
		<?php echo $this->Session->flash(); ?>
		<br/><br/>
		<?php echo $content_for_layout; ?>
	</div>
	<div id="footer"></div>
</div>
<div id="admin_background"></div>


</body>
</html>