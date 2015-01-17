<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title><?php echo $this->Theme->get_frontend_html_title(); ?></title>
		<?php echo $this->Element('theme_global_includes'); ?>
		<script type='text/javascript' src='/js/php_closure/simple_lightgrey_textured.min.js'></script>
		<link href="/css/simple_lightgrey_textured_style.css" rel="stylesheet" type="text/css" />
	</head>
<body>
	<?php echo $this->Element('nameTitle'); ?>
	<?php echo $this->Element('menu/two_level_navbar'); ?>

	<div id="bigimage"><img src="/img/A-Tangerine-Blue.jpg" alt="A Tagerine Blue" /></div>
</body>
</html>