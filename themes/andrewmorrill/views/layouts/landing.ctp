<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $this->Theme->get_frontend_html_title(); ?></title>
		<?php echo $this->Element('theme_global_includes'); ?>
		<link rel="stylesheet" type="text/css" href="/css/andrewmorrill_style.css" />
		<link href='//fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
		<?php echo $this->Theme->get_theme_dynamic_background_style($theme_config); ?>
	</head>
	<body>
		<?php echo $this->Element('nameTitle'); ?>
		<div id="slideShowDiv" style="width: 556px; height: 453px;">
			<?php echo $this->Element('landing_slideshows/basic', array(
				'width' => 556,
				'height' => 453,
				'background_color' => '#efefef',
			)); ?>
			
			<?php echo $this->Element('global_theme_footer_copyright'); ?>
		</div>
		
		<?php $intro_text = $this->Util->get_not_empty_theme_setting_or($theme_custom_settings, 'landing_page_into_text'); ?>
		<p id="introBlurb"><?php echo strip_tags($intro_text); ?></p>
	
		<div id="side_menu_bg"></div>
		<?php echo $this->Element('menu/navBar', array( 'page' => 'home' )); ?>
	</body>
</html>