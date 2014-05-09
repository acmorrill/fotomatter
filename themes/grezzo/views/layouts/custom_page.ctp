<!DOCTYPE html>
<html>
	<head>
		<title>About Kent Gigger -- Your Face Photography</title>
		<meta name="keywords" content="Kent Gigger, photography, fine art, utah photography, utah photographer, National Park, Utah, California, Large Format">
		<meta name="description" content="Large format landscape photography by Utah based photographer Andrew Morrill.">
		<link rel="stylesheet" type="text/css" href="/css/grezzo.css" />
		<?php echo $this->Element('theme_global_includes'); ?>
	</head>
	<body>
<!--		THis will help with me implementing the theme settings on all the pages.-->
		<?php 
			$background_color = $this->Util->get_not_empty_theme_setting_or($theme_custom_settings, 'background_color');
			debug($background_color);
		?>
		<div id='outer_nav' style="background-color: <?php echo $background_color; ?>">
			<div id="logo_nav_cont">
				<?php echo $this->Element('nameTitle'); ?>
				<?php echo $this->Element('menu/two_level_navbar'); ?>
			</div>	
		</div>
		<div style='clear:both'></div>
		<div class="out_page_content">
			<div id="gray_spacing_custom"></div>
			<div id="custom-page">
				<div class="page-content">					
					<?php echo $content_for_layout; ?>
					<?php echo $this->Element('global_theme_footer_copyright'); ?>
				</div>
			</div>
		</div> 
	</body>
</html>