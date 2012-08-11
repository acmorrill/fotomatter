<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<title><?php __('Photo Management'); ?></title>
	<script type="text/javascript" src="/js/jquery-1.7.1.min.js"></script>
	<?php echo $this->Element('admin/global_includes'); ?>
	<?php echo $this->Element('admin/global_js'); ?>
</head>
<body>
<div id="main">
	<div class="top_links">

	</div>
	<div class="below_links">
		<div id="header">
			<?php echo $this->Element('admin/logo'); ?>
			<?php echo $this->Element('admin/menu', array( 'curr_page' => 'theme_center' )); ?>
		</div>
		<div id="middle" class="rounded-corners">
			<?php 
				$subnav = array(); 

				$subnav['title'] = array(
					'name' => __('Theme Center', true),
					'url' => "/admin/theme_centers"
				);
				$subnav['pages'][] = array(
					'name' => __('Main Menu', true),
					'url' => "/admin/theme_centers/main_menu/"
				);

				echo $this->Element('/admin/submenu', array( 'subnav' => $subnav ));
			?>
			<?php echo $content_for_layout; ?>
		</div>
		<div id="footer"></div>
	</div>
</div>


</body>
</html>