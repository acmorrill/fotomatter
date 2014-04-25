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
		<link rel="stylesheet" type="text/css" href="/css/andrewmorrill_style.css" />
		<link rel="stylesheet" type="text/css" href="/stylesheets/introBackground.css" />
		<link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
	</head>
	<body>
		<?php echo $this->Element('nameTitle'); ?>
		<?php //echo $this->Element('newsLetter'); ?>
		<?php //require_once("php/newsLetter.php"); ?>

		
		
		<div id="slideShowDiv" style="width: 556px; height: 453px;">
			<?php echo $this->Element('landing_slideshows/basic', array(
				'width' => 556,
				'height' => 453,
				'background_color' => '#efefef',
			)); ?>
			
			<?php echo $this->Element('global_theme_footer_copyright'); ?>
		</div>
		
		<p id="introBlurb">&nbsp;&nbsp;<b>Welcome</b> to the online gallery of fine art photographer Andrew Morrill. For tips on viewing this site click <a href="viewingTips.php">here</a>. To purchase prints, navigate to an image in the image galleries section. Thank you for visting. Enjoy!
		</p>
	
		<div id="side_menu_bg"></div>
<?php
		echo $this->Element('menu/navBar', array( 'page' => 'home' ));
?>
 
<?php
			//include("php/googleAnalytics.php");
?>
	</body>
</html>