<!DOCTYPE html>
<html>
	<head>
		<title>Kent Gigger's In Your Face Photography</title>
		<meta name="keywords" content="Kent Gigger, photography, fine art, utah photography, utah photographer, National Park, Utah, California">
		<meta name="description" content="Large format landscape photography by Utah based photographer Andrew Morrill.">
		<?php echo $this->Element('theme_global_includes'); ?>
		
		
		<!-- STUFF FROM LICKY -->
		<link rel="stylesheet" type="text/css" href="http://www.lik.com/skin/frontend/default/home/css/styles.css" media="all" />
		<link rel="stylesheet" type="text/css" href="http://www.lik.com/skin/frontend/default/home/css/widgets.css" media="all" />
		<link rel="stylesheet" type="text/css" href="http://www.lik.com/skin/frontend/default/default/aw_blog/css/style.css" media="all" />
		<link rel="stylesheet" type="text/css" href="http://www.lik.com/skin/frontend/default/home/css/print.css" media="print" />
		<script type="text/javascript" src="http://www.lik.com/js/mage/cookies.js"></script>

		<script type="text/javascript" src="http://www.lik.com/js/hrd4mli.js"></script>
		<script type="text/javascript">try {
				Typekit.load();
			} catch (e) {
			}</script>


		<script type="text/javascript" src="http://www.lik.com/js/jquery.easing.min.js"></script>

		<?php echo $this->Element('landing_slideshows/licky', array()); ?>
		<!-- END STUFF FROM LICKY -->
		
		
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