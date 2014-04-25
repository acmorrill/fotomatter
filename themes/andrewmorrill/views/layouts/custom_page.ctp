<!DOCTYPE html>
<html>
	<head>
		<title>About Andrew Morrill -- Celestial Light Photography</title>
		<meta name="keywords" content="Andrew Morrill, photography, fine art, utah photography, utah photographer, National Park, Utah, California, Large Format">
		<meta name="description" content="Large format landscape photography by Utah based photographer Andrew Morrill.">
		<link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" type="text/css" href="/css/andrewmorrill_style.css" />
		<?php echo $this->Element('theme_global_includes'); ?>
	</head>
	<body>
		<?php echo $this->Element('nameTitle'); ?>
		<?php //echo $this->Element('newsLetter'); ?>
		<a name="bio"></a>
		<div class="standardContent">
			<div class="contentBackgroundInside">
				<?php echo $content_for_layout; ?>
				<?php echo $this->Element('global_theme_footer_copyright'); ?>
			</div>
		</div>
		
		<?php /*<p id="introBlurb" class="introLinks">
			<a href="#bio">Biography</a><br/>
			<a href="#philosophy">Philosophy</a><br/>
			<a href="#equipment">Equipment</a><br/>
			<a href="#largeFormat">Why Large Format?</a>
		</p>*/ ?>

		<?php echo $this->Element('menu/navBar', array( 'page' => 'custom_1' )); ?>
	</body>
</html>