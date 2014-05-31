<!DOCTYPE html>
<html>
	<head>
		<title>Kent Gigger's In Your Face Photography</title>
		<meta name="keywords" content="Kent Gigger, photography, fine art, utah photography, utah photographer, National Park, Utah, California">
		<meta name="description" content="Large format landscape photography by Utah based photographer Andrew Morrill.">
		<?php echo $this->Element('theme_global_includes'); ?>
		
		<?php echo $this->Element('landing_slideshows/supersize', array(
			'width' => 2500,
			'height' => 2500,
			'crop' => false
		)); ?>
		
		<link rel="stylesheet" type="text/css" href="/css/large_image_gray_bar.css" />
	</head>
	<body>
		<?php //echo $content_for_layout; ?>
			<div class="content">
				<div class="outer_nav">
					<?php echo $this->Element('nameTitle'); ?>
					<div class="nav">
						<?php echo $this->Element('menu/two_level_navbar'); ?>											
					</div>					
				</div>
				<!--Control Bar-->
					<div id="controls-wrapper" class="load-item">
						<div id="controls">

							<!--Navigation-->
							<ul id="slide-list"></ul>

						</div>
					</div>
				<div class="footer">
					<div class="inner_footer">
						<?php echo $this->Element('global_theme_footer_copyright'); ?>
					</div>
<!--					<div style="float: right; margin-right: 40px; background-color: inherit; font-size: 11px; z-index: 1100; width:160px; text-align:right;">
						<p style="padding-top:13px;" border="0">Kent is awesome</p>
					</div>-->
				</div>
			</div>
	</body>
</html>