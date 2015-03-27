<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php echo $this->Theme->get_frontend_html_title(); ?></title>
	<?php echo $this->Element('theme_global_includes'); ?>
	<link rel="stylesheet" type="text/css" href="/css/white_slider_subone.css" />
	
	<script src="/js/php_closure/white_slider_subone.min.js"></script>
</head>
<body>
	<div id="header_background"></div>
	
	<div class="container">
		<?php echo $this->Element('nameTitle'); ?>

		<?php echo $this->Element('menu/two_level_navbar'); ?>

		
		<div style="clear: both"></div>
		<div id="page_content_container">
			<div id="#custom_page_content_container_inner">
				<?php echo $content_for_layout; ?>
			</div>
		</div>
		
		<?php echo $this->Element('global_theme_footer_copyright'); ?>
	</div>
</body>
</html>