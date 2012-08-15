<?php 
//	if(!isset($_SESSION)) { 
//		session_start();	
//	} 
?>
<html>
	<head>
		<title>Picture Gallery -- Celestial Light Photography</title>
		<meta name="keywords" content="Andrew Morrill, online gallery, fine art, utah photography, utah photography, National Park, Utah, California, LDS temples, temple photography">
		<meta name="description" content="The online gallery of Utah based photographer Andrew Morrill.">
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<link rel="stylesheet" type="text/css" href="/stylesheets/stylesheet.css" />
		<link rel="stylesheet" type="text/css" href="/stylesheets/contentReadableBackground.css" />
	<script type="text/javascript">
		function setImage( id, path ) {
			var image = document.getElementById(id);
			image.src=src=path;
		}
	</script>
	</head>
	<body>
		<?php echo $this->Element('nameTitle'); ?>
		<?php echo $this->Element('newsLetter'); ?>
		<div class="galleryContent">
			<br /><br />
			
			<div class="portfolioLinks">
				<h2><b>Choose a Gallery</b></h2>
				<img id="portfolioThumb" src="/photos/portfolioThumbs/A-Tangerine-Blue.jpg" />
				<a onmouseover="setImage('portfolioThumb', '/photos/portfolioThumbs/A-Tangerine-Blue.jpg');" href="/photo_galleries/view_gallery?gallery=largeFormatColor">Color Landscapes</a><br />
				<a onmouseover="setImage('portfolioThumb', '/photos/portfolioThumbs/Celestial-ArrayBW.jpg');" href="/photo_galleries/view_gallery?gallery=largeFormatBW">Black &amp; White Landscapes</a><br />
				<a onmouseover="setImage('portfolioThumb', '/photos/portfolioThumbs/Solar-Migrations.jpg');" href="/photo_galleries/view_gallery?gallery=panoramics">Panoramic Landscapes</a><br />
				<a onmouseover="setImage('portfolioThumb', '/photos/portfolioThumbs/Provo-Temple-Winter.jpg');" href="/photo_galleries/view_gallery?gallery=temples">LDS Temple Pictures</a><br />
				<!--<a onmouseover="setImage('portfolioThumb', 'photos/portfolioThumbs/Winter-Berries.jpg');" href="/photo_galleries/view_gallery?gallery=digitalIdeas">Other</a><br />-->
				<!--<a onmouseover="setImage('portfolioThumb', 'photos/portfolioThumbs/Passing-Rain.jpg');" href="/photo_galleries/view_gallery?gallery=noPano">All Images</a><br />-->
				<br/><br/><br/>
			</div>
			<br />
			<img src="/images/misc/horiz_gradientline.png">
			<?php echo $this->Element('footer'); ?>
		</div>
		
		<p id="sideBlurb"><b>To purchase a print, navigate to an image and add to cart.</b><br /><br/>Before viewing images, consider checking out the <a href="viewingTips.php">viewing tips page</a>.<br />
		</p>
		
		<div id="navChain">
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="/site_pages/landing_page">home</a> > image galleries
			<img style="padding-top: 8px;" src="/images/misc/horiz_gradientline.png">
		</div>	
		
		<?php
			echo $this->Element('navBar', array( 'page' => 'gallery' ));
		?>
<?php
//			include("php/googleAnalytics.php");
?>		
	</body>
</html>