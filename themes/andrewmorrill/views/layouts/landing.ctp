<!DOCTYPE html>
<?php
	/*if(!isset($_SESSION)) { 
		session_start();	
	} 
	$_SESSION['cart'];*/
?>

<html>
	<head>
		<title>Photography by Andrew Morrill</title>
		<meta name="keywords" content="Andrew Morrill, photography, fine art, utah photography, utah photographer, National Park, Utah, California">
		<meta name="description" content="Large format landscape photography by Utah based photographer Andrew Morrill.">
		<?php echo $this->Element('theme_global_includes'); ?>
		<link rel="stylesheet" type="text/css" href="/stylesheets/stylesheet.css" />
		<link rel="stylesheet" type="text/css" href="/stylesheets/introBackground.css" />
		<script src="/javascript/preloadImages.js"></script>
		<script src="/javascript/slideShow.js"></script>
		<script src="/javascript/blendtrans.js"></script>
	</head>
	<body onload="preloadSlideShowImages(); startSlideShow();">
		<?php echo $this->Element('nameTitle'); ?>
		<?php echo $this->Element('newsLetter'); ?>
		<?php //require_once("php/newsLetter.php"); ?>
	
		<div onclick="toggleSLStartStop();" id="slideShowDiv" style="background-image: url('/slideShow/A-Tangerine-Blue.jpg'); background-repeat: no-repeat; width: 556px; height: 453px;">
			<img src="/slideShow/A-Tangerine-Blue.jpg" id="currSlideImage" style="width: 556px; height: 453px; border: 0 none; filter: alpha(opacity=0); -moz-opacity: 0; opacity: 0;" alt="" />
			
			<?php echo $this->Element('footer'); ?>
		</div>
		
		<p id="introBlurb">&nbsp;&nbsp;<b>Welcome</b> to the online gallery of fine art photographer Andrew Morrill. For tips on viewing this site click <a href="viewingTips.php">here</a>. To purchase prints, navigate to an image in the image galleries section. Thank you for visting. Enjoy!
		</p>
		
<?php
		echo $this->Element('menu/navBar', array( 'page' => 'home' ));
?>
 
<?php
			//include("php/googleAnalytics.php");
?>
	</body>
</html>