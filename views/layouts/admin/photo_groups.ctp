<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<title><?php __('Admin Dashboard'); ?></title>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<style media="all" type="text/css">@import "/css/admin.css";</style>
	<?php echo $this->Element('admin/global_js'); ?>
</head>
<body>
<div id="main">
	<div class="top_links">

	</div>
	<div class="below_links">
		<div id="header">
			<?php echo $this->Element('admin/logo'); ?>
			<?php echo $this->Element('admin/menu', array( 'curr_page' => 'photo_groups' )); ?>
		</div>
		<div id="middle" class="rounded-corners">
			<?php echo $content_for_layout; ?>
		</div>
		<div id="footer"></div>
	</div>
</div>


</body>
</html>