<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $this->Theme->get_frontend_html_title(); ?></title>
<!--		<meta name="keywords" content="Andrew Morrill, photography, fine art, utah photography, utah photographer, National Park, Utah, California, Large Format">
		<meta name="description" content="Large format landscape photography by Utah based photographer Andrew Morrill.">-->
		<link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" type="text/css" href="/css/andrewmorrill_style.css" />
		<?php echo $this->Element('theme_global_includes'); ?>
		
		<link rel="stylesheet" type="text/css" href="/stylesheets/contentReadableBackground.css" />
		<?php echo $this->Theme->get_theme_dynamic_background_style($theme_config); ?>
	</head>
	<body>
		<div id="side_menu_bg"></div>
		<?php echo $this->Element('nameTitle'); ?>
		<?php //echo $this->Element('newsLetter'); ?>
		<a name="bio"></a>
		<div class="standardContent">
			<div class="contentBackgroundInside">
				<?php echo $content_for_layout; ?>
				<?php echo $this->Element('global_theme_footer_copyright'); ?>
			</div>
		</div>
		
		<?php echo $this->Element('menu/navBar', array( 'page' => 'custom_1' )); ?>
	</body>
</html>