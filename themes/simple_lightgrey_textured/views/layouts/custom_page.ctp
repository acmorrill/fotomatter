<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $this->Theme->get_frontend_html_title(); ?></title>
		<?php echo $this->Element('theme_global_includes'); ?>
		<link href="/css/simple_lightgrey_textured_style.css" rel="stylesheet" type="text/css" />
		<?php echo $this->Theme->get_theme_dynamic_background_style($theme_config); ?>
	</head>
	<body>
		<div class="container">
			<?php echo $this->Element('nameTitle'); ?>
			<?php echo $this->Element('menu/two_level_navbar'); ?>
			
			<div id="page_content_container">
				<?php echo $content_for_layout; ?>
			</div>
		</div>
		
		<?php echo $this->Element('global_theme_footer_copyright', array(
			'inverse' => true,
		)); ?>
	</body>
</html>