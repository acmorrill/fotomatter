<!DOCTYPE html>
<?php
//	if(!isset($_SESSION)) { 
//		session_start();	
//	} 
?>
<?php
	require_once(ROOT.DS.'app'.DS.'themes'.DS.'andrewmorrill'.DS.'views'.DS.'elements'.DS.'constants.ctp');
	//require("php/constants.php"); 
	//require_once("php/errorHandling.php");
?>
<?php			
	require_once(ROOT.DS.'app'.DS.'themes'.DS.'andrewmorrill'.DS.'views'.DS.'elements'.DS.'databaseConnect.ctp');
	//echo $this->Element('databaseConnect');
				
	$gallery = $_GET["gallery"];

	$currGallery = mysql_fetch_array(mysql_query("SELECT * FROM galleries WHERE title = '$gallery'"));
?>	
<html>
	<head>
		<title><?php echo $currGallery['displayTitle']; ?> -- Celestial Light Photography</title>
		<?php echo $this->Element('theme_global_includes'); ?>
		<link rel="stylesheet" type="text/css" href="/stylesheets/contentReadableBackground.css" />
		<script src="/js/jquery-1.7.1.min.js"></script>
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
		<?php echo $this->Element('nameTitle'); ?>
		<?php echo $this->Element('newsLetter'); ?>
		<div class="galleryContent">
			<br />
			<h1><?php echo "<b>",$currGallery['displayTitle'],"</b>"; ?></h1>
			<p><?php echo $currGallery['description']; ?><br /></p>
			<?php if ($currGallery['title'] && $currGallery['title'] == "temples"): ?>
				<!-- Google Code for Request Temple Conversion Page -->
				<script type="text/javascript">
				<!--
					/*function trackRequestTemple() {
						var google_conversion_id = 1040119663;
						var google_conversion_language = "en";
						var google_conversion_format = "3";
						var google_conversion_color = "ffffff";
						var google_conversion_label = "NAJFCLO9nQEQ7-777wM";
						var google_conversion_value = 0;
						if (.05) {
						  google_conversion_value = .05;
						}
						var templeRequest_image = new Image(1,1); 
						templeRequest_image.src = "http://www.googleadservices.com/pagead/conversion/"+google_conversion_id+"/?value="+google_conversion_value+"&amp;?label="+google_conversion_label+"&amp;guid=ON&amp;script=0"; 
					}*/
				//-->
				</script>
			
				<script type="text/javascript">
					jQuery(document).ready(function() {
						jQuery("img#templeRequestButton").click(function() {
							var templeVal = jQuery("#templeInput").val();
							jQuery.post("templeRequest.php", { temple : templeVal } );
							jQuery(this).attr('src', '/email/loading.gif');
							setTimeout("jQuery('#templeInput').val('');", 500);
							setTimeout("jQuery('#templeInput').attr('title', 'enter another ...').blur();", 510);
							setTimeout("jQuery('img#templeRequestButton').attr('src', '/images/misc/sendButton.gif');", 510);
							trackRequestTemple();
						});
						
						jQuery('input#templeInput').each(function(){
							this.value = jQuery(this).attr('title');
							jQuery(this).addClass('text-label');

							jQuery(this).focus(function(){
								if(this.value == jQuery(this).attr('title')) {
									this.value = '';
									jQuery(this).removeClass('text-label');
								}
			  
								jQuery(this).unbind('keypress').keypress(function(e) {
									if(e.keyCode == 9) {
										e.preventDefault();
									}
								});
								jQuery(this).unbind('keyup').keyup(function(e) {
									if(e.keyCode == 13) {
										jQuery("#templeRequestButton").click();
									} else if (e.keyCode == 9) {
										e.preventDefault();
									}
								});
							});
							jQuery(this).blur(function(){
								if(this.value == '') {
									this.value = jQuery(this).attr('title');
									jQuery(this).addClass('text-label');
								}
								jQuery(this).unbind('keyup');
								jQuery(this).unbind('keypress');
							});
						});
					});
				</script>
						
				<div id="whatTemple">
					<span style="font-size: 13px; font-weight: bold;">What Temple Are You Looking For?</span><br/>
					<input id="templeInput" title="enter a temple..." type='text' name='temple' size='30' maxlength='60'>
					<img id='templeRequestButton' src='/images/misc/sendButton.gif'  alt='Send'><br/>
					<span id="templeAnswer"></span>
				</div>
			<?php endif; ?>
			<img src="/images/misc/horiz_gradientline.png"><br /><br />
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
			<img src="/images/misc/horiz_gradientline.png">
			<?php echo $this->Element('footer'); ?>
		</div>
		
		<div id="navChain" class="lowercase">
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="/site_pages/landing_page">home</a>&nbsp;>&nbsp;<a href="/photo_galleries/choose_gallery">image galleries</a>&nbsp;>&nbsp;<?php print ("$currGallery[displayTitle]\n"); ?>
			<img style="padding-top: 8px;" src="/images/misc/horiz_gradientline.png">
		</div>
		
		<p id="sideBlurb"><b>To purchase a print, navigate to an image and add to cart.</b><br /><br/>Before viewing images, consider checking out the <a href="viewingTips.php">viewing tips page</a>.<br />
		</p>
		
		<?php echo $this->Element('navBar', array( 'page' => 'gallery' )); ?>
<?php
			//include("php/googleAnalytics.php");
?>		
	</body>
</html>