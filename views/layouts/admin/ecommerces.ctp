<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Element('admin/meta_and_tags/title', array('layout_default' => 'E-commerce')); // can also $title_for_layout in the controller ?>
	<?php echo $this->Element('admin/global_includes'); ?>
	<?php echo $this->Element('admin/global_js'); ?>
</head>
<body>
<div id="main">
	<div id="header">
		<?php echo $this->Element('admin/logo'); ?>
		<?php $curr_page = 'sell'; ?>
		<?php echo $this->Element('admin/menu', array( 'curr_page' => $curr_page )); ?>
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