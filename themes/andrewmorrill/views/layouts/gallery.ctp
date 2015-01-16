<!DOCTYPE html>
<?php
	require_once(ROOT.DS.'app'.DS.'themes'.DS.'andrewmorrill'.DS.'views'.DS.'elements'.DS.'constants.ctp');
	//require("php/constants.php"); 
	//require_once("php/errorHandling.php");
?>
<?php			
	require_once(ROOT.DS.'app'.DS.'themes'.DS.'andrewmorrill'.DS.'views'.DS.'elements'.DS.'databaseConnect.ctp');
	//echo $this->Element('databaseConnect');
				
	$gallery = $curr_gallery['PhotoGallery']['display_name']; //$_GET["gallery"];

//	$currGallery = mysql_fetch_array(mysql_query("SELECT * FROM galleries WHERE title = '$gallery'"));
?>	
<html>
	<head>
		<title><?php echo $curr_gallery['PhotoGallery']['display_name']; ?> &mdash; <?php echo $this->Theme->get_frontend_html_title(); ?></title>
		<?php echo $this->Element('theme_global_includes'); ?>
		<link href='//fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" type="text/css" href="/css/andrewmorrill_style.css" />
		<link rel="stylesheet" type="text/css" href="/stylesheets/contentReadableBackground.css" />
		<?php echo $this->Theme->get_theme_dynamic_background_style($theme_config); ?>
<?php
if(!isset($HTTP_COOKIE_VARS["usersAvailScreenWidth"]) || !isset($HTTP_COOKIE_VARS["usersAvailScreenHeight"])) {
?>
		<script language="javascript">
		<!--
			jQuery(document).ready(function () {

				writeWidthCookie();
				writeHeightCookie();

				function writeWidthCookie() {
					var today = new Date();
					var the_date = new Date("December 31, 2023");
					var the_cookie_date = the_date.toGMTString();
					var the_cookie = "usersAvailScreenWidth="+ jQuery(document).width();
					var the_cookie = the_cookie + ";expires=" + the_cookie_date;
					document.cookie=the_cookie;
				}
				
				function writeHeightCookie() {
					var today = new Date();
					var the_date = new Date("December 31, 2023");
					var the_cookie_date = the_date.toGMTString();
					var the_cookie = "usersAvailScreenHeight="+ jQuery(document).height();
					var the_cookie = the_cookie + ";expires=" + the_cookie_date;
					document.cookie=the_cookie;
				}
			});
		//-->
		</script>
<?php
}
?>
	</head>
	<body>		
		<div id="side_menu_bg"></div>
		<?php echo $this->Element('nameTitle'); ?>
		<?php //echo $this->Element('newsLetter'); ?>
		<div class="galleryContent">
			<div class="galleryContentInner">
				<br />
				<h1><?php echo "<b>",$curr_gallery['PhotoGallery']['display_name'],"</b>"; ?></h1>
				<p><?php echo $curr_gallery['PhotoGallery']['description']; ?><br /></p>
				<img src="/images/misc/horiz_gradientline.png" alt="" />
					<?php if (count($photos) > 0): ?>
						<?php echo $this->Element('gallery/pagination_links', array('extra_css' => 'margin-top: 10px; margin-bottom: 10px;')); ?>
						<?php echo $this->Element('gallery/gallery_image_lists/2_column', array('gallery_id' => $curr_gallery['PhotoGallery']['id'], 'photos' => $photos, 'top_message' => __('click on a thumbnail image to enlarge . . .', true), 'image_max_size' => 185)); ?>
						<?php echo $this->Element('gallery/pagination_links', array('extra_css' => 'margin-top: 10px; margin-bottom: 10px;')); ?>
					<?php else: ?>
						<h4 style="font-weight: bold; font-style: italic; margin: 10px;"><?php __('This gallery does not have any images yet'); ?></h4><?php // DREW TODO - make this seccion look good ?>
					<?php endif; ?>

	<?php /*
	<?php 
		//Include the PS_Pagination class
		echo $this->Element('ps_pagination');
	?>
	<?php				
					//Connect to mysql db
					//$con;
					$pagerSql = "SELECT * FROM allimages WHERE galleries LIKE '%$gallery%' ORDER BY position ASC";
					$extraParams = "gallery=$gallery";
					//Create a PS_Pagination object
					$pager = new PS_Pagination($con, $pagerSql, 6, 5, $extraParams);


					//The paginate() function returns a mysql
					//result set for the current page
					$pagerResult = $pager->paginate();
					$currImage =  mysql_fetch_array($pagerResult);
					$numImages = mysql_num_rows($pagerResult);
					$colSpan = 1;

					//Display the navigation
					if ($pager->hasEnoughForPages()) 
						echo "<div class=\"paginationDiv\">".$pager->renderFullNav()."</div><br/>";
	?>
				<table width="100%" border="0" cellspacing="10" cellpadding="5" align="center">
					<tr>
						<td colspan="2" align="left" valign="bottom"><h4 style="font-size: 14px;"><i><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;click on a thumbnail image to enlarge . . .</b></i></h4></td>
					</tr>
	<?php
					if ($gallery == "panoramics") {
						$numPerRow = 1;
					}
					while ($currImage) {
						echo "\t\t\t\t<tr>\n";

						if ($numImages < $numPerRow) {
							// this code needs more tweaking to work with any num rows.
							$numPerRow--;
							$colSpan++;
						}
						for ($count = 0; $count < $numPerRow; $count++) {
							echo "\t\t\t\t\t<td colspan=\"$colSpan\" align=\"center\" valign=\"middle\">\n";
							echo "\t\t\t\t\t\t<div class=\"galleries\">\n";
							$photoUrl = "\"/photos/view_photo?id=".$currImage['id']."&gallery=$gallery\"";
							print ("\t\t\t\t\t\t\t<a href=".$photoUrl.">");
							$imgSrc = $thumbPhotoPath.$currImage['title'];
							print ("<img src=\"/".$imgSrc."\" alt=\"$currImage[altText]\"></a>\n");
							print ("\t\t\t\t\t\t\t<div class=\"galleriesLink\"><a href=".$photoUrl.">");
							print ("\"".$currImage['displayTitle']."\""."</a><br/>".$currImage['displaySubtitle']."</div>\n");
							echo "\t\t\t\t\t\t</div>\n";
							echo "\t\t\t\t\t</td>\n";
							if (!($currImage = mysql_fetch_array($pagerResult))) {
								break;
							} else {
								$numImages--;
							}
						}
						echo "\t\t\t\t</tr>\n";
					}
	?>
				</table>
	<?php if ($pager->hasEnoughForPages()) echo "<div class=\"paginationDiv\" style=\"margin-bottom: 14px;\">".$pager->renderFullNav()."</div>"; ?>

	 */ ?>




				<img src="/images/misc/horiz_gradientline.png" alt="" />
				<?php echo $this->Element('global_theme_footer_copyright'); ?>
			</div>
		</div>
		
		<div id="navChain" class="lowercase">
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="/site_pages/landing_page">home</a>&nbsp;>&nbsp;<a href="/photo_galleries/choose_gallery">image galleries</a>&nbsp;>&nbsp;<?php print ("{$curr_gallery['PhotoGallery']['display_name']}\n"); ?>
			<img style="padding-top: 8px;" src="/images/misc/horiz_gradientline.png" alt="" />
		</div>
		
		<p id="sideBlurb"><b>To purchase a print, navigate to an image and add to cart.</b><br /><br/>
		</p>
		
		<?php echo $this->Element('menu/navBar', array( 'page' => 'gallery' )); ?>
<?php
			//include("php/googleAnalytics.php");
?>		
	</body>
</html>
