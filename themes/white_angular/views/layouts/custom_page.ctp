<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $this->Theme->get_frontend_html_title(); ?></title>
		<?php echo $this->Element('theme_global_includes'); ?>
		<link rel="stylesheet" type="text/css" href="/css/white_angular_style.css" />
		<script src="/js/php_closure/white_angular.min.js"></script>
	</head>
	<body>
<!--		<div style="width: 650px; height: 100px; z-index: 3000; position: fixed; outline: 1px solid orange;"></div>-->
		
		<?php echo $this->Element('nameTitle'); ?>
		
		<?php echo $this->Element('menu/navBar'); ?>
		
		<div id="custom_page_content_container">
			<?php echo $content_for_layout; ?>
			<?php echo $this->Element('global_theme_footer_copyright'); ?>
		</div>
		
	</body>
</html>