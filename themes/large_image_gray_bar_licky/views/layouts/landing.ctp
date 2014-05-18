<!DOCTYPE html>
<html>
	<head>
		<title>Kent Gigger's In Your Face Photography</title>
		<meta name="keywords" content="Kent Gigger, photography, fine art, utah photography, utah photographer, National Park, Utah, California">
		<meta name="description" content="Large format landscape photography by Utah based photographer Andrew Morrill.">
		<?php echo $this->Element('theme_global_includes'); ?>
		<link rel="stylesheet" type="text/css" href="/css/large_image_gray_bar.css" />
	</head>
	<body>
		<?php //echo $content_for_layout; ?>
			<div class="content">
				<div class="outer_nav">
					<?php echo $this->Element('nameTitle'); ?>
					<div class="nav">
						<?php echo $this->Element('menu/navBar', array( 'page' => 'home' )); ?>
					</div>
				</div>
			<div> <!--	id="slideShowDiv" style="width: 556px; height: 453px;"-->
					<?php echo $this->Element('landing_slideshows/basic', array(
						'width' => 1555,
						'height' => 650,
						'background_color' => '#efefef',
					)); ?>
				</div>
				<div style="width: 100%; background-color: #000; color: #666; line-height: 45px; position: fixed; bottom: 0px; left: 0px; clear: both; z-index: 1100;">
					<div style="float: left; margin-left: 40px; background-color: inherit; font-size: 11px; z-index: 1100; line-height: 45px;">
						<?php echo $this->Element('global_theme_footer_copyright'); ?>
					</div>
					<div style="float: right; margin-right: 40px; background-color: inherit; font-size: 11px; z-index: 1100; width:160px; text-align:right;">
						<p style="padding-top:13px;" border="0">Kent is awesome</p>
					</div>
				</div>
			</div>
	</body>
</html>