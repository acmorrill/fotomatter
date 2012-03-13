<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<title><?php __('Admin Dashboard'); ?></title>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<style media="all" type="text/css">@import "/css/admin.css";</style>
	<script type="text/javascript" src="/js/jquery-1.6.min.js"></script>
	<script type="text/javascript" src="/js/jquery-ui-1.8.12/js/jquery-ui-1.8.12.custom.min.js"></script>
	<script type="text/javascript" src="/js/jquery-validation-1.8.0/jquery.validate.min.js"></script>
</head>
<body>
<div id="main">
	<div class="top_links">

	</div>
	<div class="below_links">
		<div id="header">
			<?php echo $this->Element('admin/logo'); ?>
			<?php echo $this->Element('admin/menu', array( 'curr_page' => 'galleries' )); ?>
		</div>
		<div id="middle" class="rounded-corners">
			<?php echo $content_for_layout; ?>
		</div>
		<div id="footer"></div>
	</div>
</div>


</body>
</html>