<!DOCTYPE html>
<html>
<head>
	<title><?php __('Admin Dashboard'); ?></title>
	<?php echo $this->Element('admin/global_includes'); ?>
	<?php echo $this->Element('admin/global_js'); ?>
</head>
<body>
<div id="main" class='no_subnav'>
	<div id="header">
		<?php echo $this->Element('admin/logo'); ?>
	</div>
	<div id="middle">
		<?php echo $this->Session->flash(); ?>
		<?php echo $content_for_layout; ?>
	</div>
	<?php echo $this->Element('admin/global_footer'); ?>
</div>
<?php echo $this->Element('admin/global_after_footer'); ?>
</body>
</html>