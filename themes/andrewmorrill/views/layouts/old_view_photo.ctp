<!DOCTYPE html>
<?php //include './include.php'; //this is for the comments script?>
<?php 
	require_once(ROOT.DS.'app'.DS.'themes'.DS.'andrewmorrill'.DS.'views'.DS.'elements'.DS.'constants.ctp');
	echo $this->Element('dimension');
	
	//---------------------------------------------------------------
	// code for handling display options check box
	$printSizeDisplay = "";
	if (isset($_POST['printSizeDisplay'])) {
		$_SESSION['printSizeDisplay'] = $_POST['printSizeDisplay'];
		$printSizeDisplay = $_POST['printSizeDisplay'];
		unset($_POST['printSizeDisplay']);
	} else if (isset($_SESSION['printSizeDisplay'])){
		$printSizeDisplay = $_SESSION['printSizeDisplay'];
	} else {
		$printSizeDisplay = "approximate";
	}
	$imageSize = "";
	$setCookie = false;
	if (isset($_POST['imageSize'])) {
		$_SESSION['imageSize'] = $_POST['imageSize'];
		$imageSize = $_POST['imageSize'];
		unset($_POST['imageSize']);
		$setCookie = true;
	} else if (isset($HTTP_COOKIE_VARS["imageSize"])) {
		$_SESSION['imageSize'] = $HTTP_COOKIE_VARS["imageSize"];
		$imageSize = $_SESSION['imageSize'];
	} else if (isset($_SESSION['imageSize'])){
		$imageSize = $_SESSION['imageSize'];
	} else {
		if(isset($HTTP_COOKIE_VARS["usersAvailScreenWidth"]) && isset($HTTP_COOKIE_VARS["usersAvailScreenHeight"])) {
			$screenAvailWidth = $HTTP_COOKIE_VARS["usersAvailScreenWidth"];
			$screenAvailHeight = $HTTP_COOKIE_VARS["usersAvailScreenHeight"];
			if ($screenAvailWidth >= $reqLargeWidth && $screenAvailHeight >= $reqLargeHeight) {
				$imageSize = "larger";
				$_SESSION['imageSize'] = $imageSize;
			} else {
				$imageSize = "normal";
				$_SESSION['imageSize'] = $imageSize;
			}
		} else {
			$imageSize = "normal";
			$_SESSION['imageSize'] = $imageSize;
		}
	}
	
?>
<?php
		require_once(ROOT.DS.'app'.DS.'themes'.DS.'andrewmorrill'.DS.'views'.DS.'elements'.DS.'databaseConnect.ctp');
				
				// get image id
				$photo_id = $curr_photo['Photo']['id']; //$_GET["id"];
				$photo_gallery_id = $curr_gallery['PhotoGallery']['id']; //$_GET["gallery"];
				
				// retrieve image data by id
				$currImage = $curr_photo; //mysql_fetch_array(mysql_query("SELECT * FROM allimages WHERE id = $photo_id"));
				
				
				$currGallery = $curr_gallery; //mysql_fetch_array(mysql_query("SELECT * FROM galleries WHERE title = '$gallery'"));
				$gallery = 'panoramics';
?>
<html>
	<head>
		<title><?php print($currImage['Photo']['display_title']." - ".$currImage['Photo']['Photo']['display_subtitle']." - ".$currGallery['PhotoGallery']['display_name']);?></title>
		<?php echo $this->Element('theme_global_includes'); ?>
		<script type="text/javascript">
		<!--
			function pressSubmitButton() {
				var submitButton = document.getElementById("hiddenSubmitButton");
				submitButton.click();
			}
			
			function detectResolution() {
<?php
		if ($imageSize == "normal" && !isset($_SESSION['imageSize'])) {
			print ("if (screen.availWidth >= ".$reqLargeWidth." && screen.availHeight >= ".$reqLargeHeight.") {\n");
			print <<<END
					var largerRadioButton = document.getElementById('largerRadioOption');
					largerRadioButton.checked = true;
					pressSubmitButton();
				}
				// image size was not changed due to insufficient screen size
				return;
END;
		} else {
			print <<<END
				// there is no way to tell if javascript did something :) sorry (because of Ajax type interaction with PHP)
				return;

END;
		}
?>
			}
		-->
		</script>
		<script type="text/javascript" src="/javascript/animatedcollapse.js">
			/***********************************************
			* Animated Collapsible DIV v2.2- (c) Dynamic Drive DHTML code library (www.dynamicdrive.com)
			* This notice MUST stay intact for legal use
			* Visit Dynamic Drive at http://www.dynamicdrive.com/ for this script and 100s more
			***********************************************/
		</script>
		<script type="text/javascript">
		<!--
			//animatedcollapse.addDiv('printsAvailable', 'speed=300, fade=1, hide=0, persist=1');
			//animatedcollapse.addDiv('comments', 'speed=400, fade=1, hide=0, persist=1');
			animatedcollapse.addDiv('printInfo', 'speed=400, fade=1, hide=1, persist=1');
			animatedcollapse.addDiv('guarantees', 'speed=400, fade=1, hide=1, persist=1');
			animatedcollapse.addDiv('displayOptions', 'speed=300, fade=1, hide=0, persist=1');
			//animatedcollapse.addDiv('comments', 'speed=400, fade=1, hide=1, persist=1');
			
			animatedcollapse.ontoggle=function($, divobj, state){ //fires each time a DIV is expanded/contracted
				if (divobj.id=="displayOptions" || divobj.id=="printInfo" || divobj.id=="printsAvailable" || divobj.id=="guarantees" || divobj.id=="comments") { //only react to these two collapsible DIVs
					document.getElementById(divobj.id+"Gif").src=(state=="block")? "/images/misc/contractRed.gif" : "/images/misc/expandRed.gif"
				}
			}

			animatedcollapse.init();
		-->
		</script>
<?php
			if ($currImage['PhotoFormat']['ref_name'] == "panoramic") {
				print("<link rel=\"stylesheet\" type=\"text/css\" href=\"/stylesheets/panoBackground.css\" />");
			} else {
				print("<link rel=\"stylesheet\" type=\"text/css\" href=\"/stylesheets/photoBackground.css\" />");
			}
?>
		<!-- stylesheet for photo comments -->
		<!--<link rel="stylesheet" type="text/css" href="comments/template/default/style.css"/> -->
<?php
	if ($setCookie == true) {
			print <<<COOKIE
				<script language="javascript">
				<!--
					writePrintSizeSetting();

					function writePrintSizeSetting() {
						var today = new Date();
						var the_date = new Date("December 31, 2023");
						var the_cookie_date = the_date.toGMTString();
COOKIE;
						print ("var the_cookie = \"imageSize=".$_SESSION['imageSize']."\";"); 
			print <<<COOKIE
						var the_cookie = the_cookie + ";expires=" + the_cookie_date;
						document.cookie=the_cookie;
					}
				//-->
				</script>
COOKIE;
	}
?>
	</head>
	<body onload="detectResolution()">
		<?php // figure out the image hover text ?>
		<script type="text/javascript">
		<!--
			jQuery(window).load(function() {
				var img = jQuery('img#mainImage');
				var text = jQuery('h1#imageHoverText');
				var imgWidth = img.width();
				var imgHeight = img.height();
				var textWidth = text.width();
				var textHeight = text.height();
				var imgPos = img.offset();
				var left = ((imgWidth - textWidth)/2) + imgPos.left;
				var top = ((imgHeight - textHeight)/2) + imgPos.top;
				text.css({'top' : top, 'left' : left});
				text.css('opacity', 0);
				img.hover(
					function() {
						text.stop(true);
						text.show();
						text.animate({opacity: 1}, 200);
					},
					function() {
						text.stop(true);
						text.animate({opacity: 0}, 200);
					}
				);
				text.hover(
					function() {
						text.stop(true);
						text.show();
						text.animate({opacity: 1}, 200);
					},
					function() {
						
					}
				);
			});
		-->
		</script>
		<h1 style="cursor: pointer;" onclick="switchImageSize();" id="imageHoverText"><?php
			if ($imageSize == "normal") {
				echo "Click to View Larger";
			} else if ($imageSize == "larger") {
				echo "Click to View Smaller";
			}
?></h1>
		
<?php echo $this->Element('nameTitle'); ?>
<?php
			if ($currImage['PhotoFormat']['ref_name'] == "panoramic") {
				print("<a class=\"arrow leftArrow\" style=\"top:294px;\"");
			} else {
				print("<a class=\"arrow leftArrow\"");
			}
			
			// figure out source for previous image button
			// DREW TODO - make the following work
//			$currPosition = 1; //$currImage['position'];
//			$prevImageQuery = "SELECT * FROM allimages WHERE (position < $currPosition AND galleries LIKE '%$gallery%') ORDER BY position DESC";
//			$prevImage = mysql_fetch_array(mysql_query($prevImageQuery));
			if ($prevImage != null) {
				$prevImageSrc = "/photos/view_photo?id=2&gallery=".$gallery;
			} else {
				$prevImageSrc = "/photo_galleries/view_gallery"."?gallery=".$gallery;
			}
			
			print ("href=\"$prevImageSrc\">\n");
?>
			<img onmouseover="this.src='/images/misc/arrowLeftRed.png';" onmouseout="this.src='/images/misc/arrowLeft.png';" src="/images/misc/arrowLeft.png">
		</a>
<?php 
				if ($imageSize == "normal") {
					$currImageWidth = $currImage['webWidth'];
				} else if ($imageSize == "larger") {
					$currImageWidth = $currImage['largeWebWidth'];
				}
?>
<?php
				// decide on photo path and print opening div(large or extra large)
				if ($imageSize == "normal") {
					print("<div id=\"largePhotoPos\" style=\"width: 892px\">");
					$photoDivWidth = 892;
					$photoDivHeight = 1000;
					$imgSrc = $this->Photo->get_photo_path($currImage['Photo']['id'], $photoDivHeight, $photoDivWidth);  //'/'.$largePhotoPath.$currImage['title'];
				} else if ($imageSize == "larger") {
					print("<div id=\"largePhotoPos\" style=\"width: 1160px\">");
					$photoDivWidth = 1160;
					$photoDivHeight = 1160;
					$imgSrc = $this->Photo->get_photo_path($currImage['Photo']['id'], $photoDivHeight, $photoDivWidth); //'/'.$extraLargePhotoPath.$currImage['title'];
				}
?>
				<div id="photoNavChain" <?php print ("style = \"top: -21px; width: ".(($currImageWidth >= 509) ? $currImageWidth : (500))."px;\""); ?>><b><a <?php /* onclick="animatedcollapse.show('printsAvailable'); animatedcollapse.hide(['displayOptions', 'printInfo', 'guarantees', 'comments']);"*/ ?> style="color: #ECEDEF; font-size: 14px;" href="#availablePrints"><?php (($currImageWidth >= 509) ? print("&mdash; Purchase Options &mdash;") : print("Purchase Options"))?></a></b></div>
<?php			if ($imageSize == "normal") { ?>
					<script type="text/javascript">
					<!--
						function switchImageSize() {
							jQuery("#largerRadioOption").attr('checked', 'checked');
							jQuery('#displayOptions form').submit();
						}
					-->
					</script>
<?php			} else if ($imageSize == "larger") { ?>
					<script type="text/javascript">
					<!--
						function switchImageSize() {
							jQuery("#smallerRadioOption").attr('checked', 'checked');
							jQuery('#displayOptions form').submit();
						}
					-->
					</script>
<?php 			} ?>
<?php
				
				// print out image (large or extra large)
				if ($imageSize == "normal") {
					print("<img style=\"cursor: pointer;\" id=\"mainImage\" onclick=\"switchImageSize();\" src=\"$imgSrc\" alt=\"$currImage[altText]\" border=\"0\" width=\"".$currImage['webWidth']."\" height=\"".$currImage['webHeight']."\"/><BR />\n");
				} else if ($imageSize == "larger") {
					print("<img style=\"cursor: pointer;\" id=\"mainImage\" onclick=\"switchImageSize();\" src=\"$imgSrc\" alt=\"$currImage[altText]\" border=\"0\" width=\"".$currImage['largeWebWidth']."\" height=\"".$currImage['largeWebHeight']."\"/><BR />\n");
				}
?>
			<div id="photoNavChain" class="lowercase" <?php print ("style = \"width: ".$currImageWidth."px;\""); ?>><a href="/site_pages/landing_page">home</a> > <a href="/photo_galleries/choose_gallery">image galleries</a> > <a href="/photo_galleries/view_gallery?gallery=<?php print ("{$currGallery['PhotoGallery']['display_name']}"); ?>"><?php  print ("{$currGallery['PhotoGallery']['display_name']}") ?></a>
<?php
				if (isset($_SESSION['cart'])) {
					$dimenArray = unserialize($_SESSION['cart']);
					$cartSize = 0;
					foreach ($dimenArray as $dimen) {
						$cartSize += $dimen->numInCart;
					}
					if ($cartSize > 0) {
						print ("\t\t\t> <b><a href=\"shoppingcart.php\"><img style=\"position: relative; top: 6px;\" src=\"images/misc/Shoppingcart_16x16.png\"> cart ($cartSize)</a></b>\n");
					}
				}
?>
			</div>
			<a name="availablePrints"></a>
			<h2 class="photoTitle"><?php print("\"<b>$currImage[displayTitle]</b>\""); ?></h2>
			<p style="margin-bottom: 13px;"><?php print("$currImage[displaySubtitle]"); ?><br/>
<?php 			
			$phpdate = strtotime( $currImage['shotDate'] );
			echo date("F Y",$phpdate);
?>
			</p>
				<!-- <script type="text/javascript" src="http://www.googleadservices.com/pagead/conversion.js"> -->
				</script>
				<!-- Google Code for Add To Cart Conversion Page -->
				<script type="text/javascript">
				<!--
					/*function trackAddToCartConv() {
						var google_conversion_id = 1040119663;
						var google_conversion_language = "en";
						var google_conversion_format = "3";
						var google_conversion_color = "ffffff";
						var google_conversion_label = "_jBhCPm5mwEQ7-777wM";
						var google_conversion_value = 0;
						if (10) {
						  google_conversion_value = 10;
						}
						var itemAddedPrice = parseInt(jQuery("#optionSelectBox :selected").text().split("$")[1], 10);
						google_conversion_value = itemAddedPrice;
						//alert(google_conversion_value);
						var cart_image = new Image(1,1); 
						cart_image.src = "http://www.googleadservices.com/pagead/conversion/"+google_conversion_id+"/?value="+google_conversion_value+"&amp;?label="+google_conversion_label+"&amp;guid=ON&amp;script=0"; 
						//alert("conversion tracked");
					} */
				//-->
				</script>
<?php
				$sizesArray = array();
				$sizesArray = unserialize($currImage['sizePriceArray']);
				if (sizeof($sizesArray) > 0) {
					echo "\t\t\t<form method=post action=\"shoppingcart.php\">\n";
					echo "\t\t\t\t<span style=\"font-size: 14px;\" class=\"highlight\">Available Sizes:</span><br />\n";
					echo "\t\t\t\t<SELECT id=\"optionSelectBox\" style=\"position: relative; top: -3px; font-size: 16px;\" name=\"sizeID\" size=\"1\">\n";
					for ($i = 0; $i < sizeof($sizesArray); $i++) {
						$dimension = $sizesArray[$i];
						if ($currImage['PhotoFormat']['ref_name'] == "panoramic") {
							if ($i == 0) {
								print ("\t\t\t\t\t<optgroup label=\"small\">\n");
							} else if ($i == 1) {
								print ("\t\t\t\t\t<optgroup label=\"popular\">\n");
							} else if ($i == 3) {
								print ("\t\t\t\t\t<optgroup label=\"very large\">\n");
							}
							
							if ($printSizeDisplay == "approximate") {
								print ("\t\t\t\t\t<option value=\"".$dimension->toHash()."\" ".($i == 1?"selected=\"selected\"":"").">".$dimension->getInDimenAsText()."</option>\n");
							} else {
								print ("\t\t\t\t\t<option value=\"".$dimension->toHash()."\" ".($i == 1?"selected=\"selected\"":"").">".$dimension->getExactInDimenAsText()."</option>\n");
							}
						} else {
							if ($i == 0) {
								print ("\t\t\t\t\t<optgroup label=\"small\">\n");
							} else if ($i == 2) {
								print ("\t\t\t\t\t<optgroup label=\"popular\">\n");
							} else if ($i == 7) {
								print ("\t\t\t\t\t<optgroup label=\"very large\">\n");
							}
							
							if ($printSizeDisplay == "approximate") {
								print ("\t\t\t\t\t<option value=\"".$dimension->toHash()."\" ".($i == 3?"selected=\"selected\"":"").">".$dimension->getInDimenAsText()."</option>\n"); // : $".$dimension->getPrice()."
							} else {
								print ("\t\t\t\t\t<option value=\"".$dimension->toHash()."\" ".($i == 3?"selected=\"selected\"":"").">".$dimension->getExactInDimenAsText()."</option>\n"); // " : $".$dimension->getPrice().
							}
						}
					}
					echo "\t\t\t\t</SELECT>\n";
					echo "\t\t\t\t<input type=\"hidden\" name=\"quantity\" value=\"1\">\n";
					echo "\t\t\t\t<input type=\"hidden\" name=\"added\" value=\"true\">\n";
					echo "<br/>\t\t\t\t\n<div style='position: relative; margin-left: 8px; margin-top: 4px; font-size: 16px; font-weight: bold; width: 500px;'>NOTICE: The \"Add to Cart\" button is temporarily disabled while my website is being upgraded to better reflect limited edition and Aluma Print pricing structure. Please contact me for purchasing and pricing at acmorrill@gmail.com</div>";
					echo "\t\t\t\t<INPUT id=\"addToCartButton\" onclick=\"//trackAddToCartConv();\" style=\"display: none; position: relative; top: 5px; margin-left: 8px;\" TYPE=\"image\" SRC=\"images/misc/addToCart.gif\" HEIGHT=\"28\" WIDTH=\"120\" BORDER=\"0\" ALT=\"Add to Cart\">\n";
					//echo "\t\t\t\t<input onclick=\"trackAddToCartConv();\" type=\"submit\" name=\"submit\" value=\"Add to Cart\">\n";
					echo "\t\t\t\t<br />\n";
					echo "\t\t\t</form>\n";
				}
?>
			<script type="text/javascript">
				jQuery('#addToCartButton').hover(
					function () {
						jQuery(this).attr("src", "/images/misc/addToCartGreen.gif");
					},
					function () {
						jQuery(this).attr("src", "/images/misc/addToCart.gif");
					}
				);
			</script>
			<p style="width: 520px"><?php print("$currImage[description]"); ?></p>
			<p>Also, be sure to check out the <a onclick="animatedcollapse.show('printInfo')" href="#printInfo">Print Information</a> and <a onclick="animatedcollapse.show('guarantees')" href="#guarantees">Purchasing Guarantees</a> below.</p>
			<img src="/images/misc/horiz_gradientline.png"><br/><br/>

			<!-- expand and collapse all links -->
			<!--<div class="section" style="width: 20em; left: 30px; border: none; text-align: center; padding-left: 0;">
				<h2>| &nbsp;<a href="javascript:animatedcollapse.show(['printsAvailable', 'displayOptions', 'printInfo'])">Expand All</a> &nbsp;|&nbsp; <a href="javascript:animatedcollapse.hide(['printsAvailable', 'displayOptions', 'printInfo'])">Collapse All</a> &nbsp;|&nbsp;</h2>
			</div>-->
			
		<!-- OPTION AND INFO BARS BELOW -->
			<!-- Prints Available -->
<?php /*			<a name="availablePrints"></a>
			<div class="section" style="border-top: 1px solid #F4F5F7;">
				<a href="javascript:animatedcollapse.toggle('printsAvailable')"><img id="printsAvailableGif" src="images/misc/contractRed.gif" /><h2><li><span class="printsAvailable"><b>Prints Available for Purchase</b></span></li></h2></a>
			</div>
			
			
	        <div class="pageOptionBox" id="printsAvailable">
				<div class="dropDownBoxContent">
					<p style="position: absolute; left: 415px; width: 150px">Be sure to check out the <a onclick="animatedcollapse.show('printInfo')" href="#printInfo">Print Information</a> and <a onclick="animatedcollapse.show('guarantees')" href="#guarantees">Purchasing Guarantees</a> below.</p>
					<div class="c5t_comment_form_background lessmargin" style="width: 384px;">
					<div class="c5t_comment_item extrapadding">
<?php
				require_once("php/dimension.php");
				$sizesArray = array();
				echo "<br>";
				$sizesArray = unserialize($currImage['sizePriceArray']);
				if (sizeof($sizesArray) > 0) {
					echo "\t\t\t<form method=post action=\"shoppingcart.php\">\n";
					echo "\t\t\t\t<span style=\"font-size: 14px;\" class=\"highlight\">Available Sizes:</span><br />\n";
					echo "\t\t\t\t<SELECT style=\"position: relative; top: -5px; font-size: 14px;\" name=\"sizeID\" size=\"1\">\n";
					for ($i = 0; $i < sizeof($sizesArray); $i++) {
						$dimension = $sizesArray[$i];
						if ($currImage['format'] == "panoramic") {
							if ($i == 0) {
								print ("\t\t\t\t\t<optgroup label=\"small\">\n");
							} else if ($i == 1) {
								print ("\t\t\t\t\t<optgroup label=\"popular\">\n");
							} else if ($i == 3) {
								print ("\t\t\t\t\t<optgroup label=\"very large\">\n");
							}
							
							if ($printSizeDisplay == "approximate") {
								print ("\t\t\t\t\t<option value=\"".$dimension->toHash()."\" ".($i == 1?"selected=\"selected\"":"").">".$dimension->getInDimenAsText()."</option>\n");
							} else {
								print ("\t\t\t\t\t<option value=\"".$dimension->toHash()."\" ".($i == 1?"selected=\"selected\"":"").">".$dimension->getExactInDimenAsText()."</option>\n");
							}
						} else {
							if ($i == 0) {
								print ("\t\t\t\t\t<optgroup label=\"small\">\n");
							} else if ($i == 2) {
								print ("\t\t\t\t\t<optgroup label=\"popular\">\n");
							} else if ($i == 7) {
								print ("\t\t\t\t\t<optgroup label=\"very large\">\n");
							}
							
							if ($printSizeDisplay == "approximate") {
								print ("\t\t\t\t\t<option value=\"".$dimension->toHash()."\" ".($i == 3?"selected=\"selected\"":"").">".$dimension->getInDimenAsText()." : $".$dimension->getPrice()."</option>\n");
							} else {
								print ("\t\t\t\t\t<option value=\"".$dimension->toHash()."\" ".($i == 3?"selected=\"selected\"":"").">".$dimension->getExactInDimenAsText()." : $".$dimension->getPrice()."</option>\n");
							}
						}
					}
					echo "\t\t\t\t</SELECT>\n";
					echo "\t\t\t\t<input type=\"hidden\" name=\"quantity\" value=\"1\">\n";
					echo "\t\t\t\t<input type=\"hidden\" name=\"added\" value=\"true\">\n";
					echo "\t\t\t\t<INPUT style=\"position: relative; top: 5px; margin-left: 3px;\" TYPE=\"image\" SRC=\"images/misc/addToCart.gif\" HEIGHT=\"30\" WIDTH=\"120\" BORDER=\"0\" ALT=\"Add to Cart\">\n";
					//echo "\t\t\t\t<input type=\"submit\" name=\"submit\" value=\"Add to Cart\">\n";
					echo "\t\t\t\t<br /><br />\n";
					echo "\t\t\t</form>\n";
				}
?>
					</div></div>
				</div>
			</div> */ ?>
		
			<? /*
			<!-- PRINT COMMENTS -->
			<div class="section">
				<a href="javascript:animatedcollapse.toggle('comments')"><img id="commentsGif" src="images/misc/contractRed.gif" /><h2><b><li>
<?php
	$currPageUrl = $_SERVER['REQUEST_URI'];
	$currImageNumComments = mysql_num_rows(mysql_query("SELECT * FROM c5t_comment WHERE comment_identifier = '$currPageUrl'"));
	
	if ($currImageNumComments <= 0) {
		print ("Leave a Comment&nbsp;&nbsp;&nbsp;(be the first to comment)");
	} else if($currImageNumComments == 1) {
		print ("Leave or Read Comment&nbsp;&nbsp;&nbsp;($currImageNumComments comment so far) ");
	} else {
		print ("Leave or Read Comments&nbsp;&nbsp;&nbsp;($currImageNumComments comments so far) ");
	}
?>
</li></b></h2></a>
			</div>

			<div class="pageOptionBox" id="comments" style="border-bottom: 1px solid #C9C9C9;">
				<div class="dropDownBoxContent">
<?php
$c5t_output = substr($c5t_output, 0, -369); 
echo $c5t_output; 
?>
				</div>
			</div>
			*/?>
			
			<!-- PRINT INFO BAR -->
			<a name="printInfo"></a>
			<div class="section">
				<a href="javascript:animatedcollapse.toggle('printInfo')"><img id="printInfoGif" src="/images/misc/contractRed.gif" /><h2><b><li>Print Information</li></b></h2></a>
			</div>

			<div class="pageOptionBox" id="printInfo" style="border-bottom: 1px solid #C9C9C9;">
				<div class="dropDownBoxContent">
					<div class="c5t_comment_form_background lessmargin">
					<div class="c5t_comment_item extrapadding" style="padding-top: 0px;">
					<h2 style="margin-top: 0px;"><b>Color Prints</b></h2>
					<img style="float: left; margin-left: 0px; margin-right: 10px; margin-bottom: 4px;" src="/images/supergloss.jpg"/>
					<p>All limited editions are now printed as Aluma prints
-- the highest quality prints available today. A process with unparalleled depth
, color vibrance, and sharpness &mdash; perfect for my images. They have to be s
een to be believed.</p></div></div>
					<div class="c5t_comment_form_background lessmargin">
					<div class="c5t_comment_item extrapadding" style="padding-top: 0px;">
					<h2 style="margin-top: 0px;"><b>Black &amp; White Prints</b></h2>
					<img style="float: left; margin-left: 0px; margin-right: 10px; margin-bottom: 5px; top: 20px;" src="/images/silverRag.jpg"/>
					<p>Black &amp; white images are printed on either Hahnem�hle Fine Art Pearl or Museo Silver Rag paper (depending on the image). Both are heavy fine art papers that look and feel just like traditional silver gelatin prints, but with the benefits of modern processes. Both black and white papers have a tested permanence rating exceeding 100 years<span class="highlight">*</span>.  -- For more info see the <a href="/printInfo.php">print info</a> page --</p>
					</div></div>
					<br/><br/><p><span class="highlight">*</span> when framed behind glass and not subject to direct sunlight (Wilhelm Imaging Research)</p>
				</div>
			</div>
			
			<!-- PRINT GUARANTEES BARS -->
			<a name="guarantees"></a>
			<div class="section">
				<a href="javascript:animatedcollapse.toggle('guarantees')"><img id="guaranteesGif" src="/images/misc/contractRed.gif" /><h2><b><li>Purchasing Guarantees</li></b></h2></a>
			</div>

			<div class="pageOptionBox" id="guarantees" style="border-bottom: 1px solid #C9C9C9;">
				<div class="dropDownBoxContent">
					<div class="c5t_comment_form_background lessmargin">
					<div class="c5t_comment_item extrapadding">
					<p>I stand behind the quality of my work and am eager that you be completely satisfied with any prints that you order.</p>
<p><span class="highlight"><b>Quality Guarantee</b></span>: In the unlikely event of print defects or damage, including damage caused in transit, the print will be replaced without question. <b>Please contact me within 3 days of receiving the print</b>.</p>
<p><span class="highlight"><b>Satisfaction Guarantee</b></span>: If, for any reason, you are unsatisfied with a print, you may exchange the print for any other print of equal or lesser value (or pay the difference for a more expensive print). Simply pay to ship the print back to me in <b>"as new" condition in original packaging within 7 days of receiving the print</b>, pay shipping and handling on the new print, and the new print will be shipped to you.</p>
<p><span class="highlight"><b>Money Back Guarantee</b></span>: You may return the print for a refund (<b>less shipping and handling and a 10% fee</b>) for any reason, provided you pay to ship the print back to me in <b>"as new" condition in original packaging within 7 days of receiving the print.</b></p>
					</div></div>
				</div>
			</div>
			
			<!-- DISPLAY OPTION BAR -->
			<div class="section">
				<a href="javascript:animatedcollapse.toggle('displayOptions')"><img id="displayOptionsGif" src="/images/misc/contractRed.gif" /><h2><b><li>Page Display Options</li></b></h2></a>
			</div>

			<div class="pageOptionBox" id="displayOptions">
				<div class="dropDownBoxContent">
					<div class="c5t_comment_form_background lessmargin"  style="width: 18em;">
					<div class="c5t_comment_item extrapadding">
					<form name="displayOptions" action="" method="post">
						<label for="imageSize"><b>Image Size:</b></label><br />
						&nbsp;&nbsp;&nbsp;<input id="smallerRadioOption" onclick="pressSubmitButton()" type="radio" name="imageSize" value="normal" <?php if ($imageSize == "normal") echo "checked"?>/> Normal
						<input id="largerRadioOption" onclick="pressSubmitButton()" type="radio" name="imageSize" value="larger" <?php if ($imageSize == "larger") echo "checked"?>/> Larger
						<br/>
						<label for="printSizeDisplay"><b>Prints Available Dimensions:</b></label><br />
						&nbsp;&nbsp;&nbsp;<input onclick="pressSubmitButton()" type="radio" name="printSizeDisplay" value="approximate" <?php if ($printSizeDisplay == "approximate") echo "checked"?>/> Approximate
						<input onclick="pressSubmitButton()" type="radio" name="printSizeDisplay" value="exact" <?php if ($printSizeDisplay == "exact") echo "checked"?>/> Exact
						<input style="position: absolute; border: 0 none; filter: alpha(opacity=0); -moz-opacity: 0; opacity: 0;" type="submit" name= "submitButtonName" id="hiddenSubmitButton"/>
					</form>
					</div></div>
				</div>
			</div>
			<br/><br/><br/><br/><br/>
			<br/><br/><br/><br/><br/>
			<br/><br/><br/><br/><br/>
			<br/><br/><br/><br/><br/>
	
			<?php echo $this->Element('global_theme_footer_copyright'); ?>
		</div>
<?php 
			// figure out the right arrow
			if ($imageSize == "normal") {
				$leftOffSet = $currImage['webWidth'] + 111;
			} else if ($imageSize == "larger") {
				$leftOffSet = $currImage['largeWebWidth'] + 111;
			}
			$nextImageQuery = "SELECT * FROM allimages WHERE (position > $currPosition AND galleries LIKE '%$gallery%') ORDER BY position ASC";
			$nextImage = mysql_fetch_array(mysql_query($nextImageQuery));
			if ($nextImage != null) {
				$nextImageSrc = "/photos/view_photo?id=".$nextImage['id']."&gallery=".$gallery;
			} else {
				$nextImageSrc = "/photo_galleries/view_gallery"."?gallery=".$gallery;
			}
?>
		
<?php
			print("<a class=\"arrow\" style=\"");
			if ($currImage['format'] == "panoramic") {
				print ("top:294px;"); 
			}
			print ("left: $leftOffSet"."px;\" href=\"$nextImageSrc\">");
?>
			<img onmouseover="this.src='/images/misc/arrowRightRed.png';" onmouseout="this.src='/images/misc/arrowRight.png';" src="/images/misc/arrowRight.png">
		</a>

<?php /*
		<script type="text/javascript">
		
		// Disable right click script II (on images)- By Dynamicdrive.com
		// For full source, Terms of service, and 100s DTHML scripts
		// Visit http://www.dynamicdrive.com
		// Modified here to disable IE image hover menu and
		// truly disable right click in FF by jscheuer1 in
		// http://www.dynamicdrive.com/forums
		

		var clickmessage="Sorry, right click menu disabled on larger images."

		function disableclick(e) {
		if (document.all) {
		if (event.button==2||event.button==3) {
		if (event.srcElement.tagName=="IMG"){
		alert(clickmessage);
		return false;
		}
		}
		}
		else if (document.layers) {
		if (e.which == 3) {
		alert(clickmessage);
		return false;
		}
		}
		else if (document.getElementById)
		if (e.which==3&&e.target.tagName=="IMG")
		setTimeout("alert(clickmessage)",0)
		}

		function associateimages(){
		for(i=0;i<document.images.length;i++)
		document.images[i].onmousedown=disableclick;
		}

		if (document.all){
		document.onmousedown=disableclick
		for (var i_tem = 0; i_tem < document.images.length; i_tem++)
		document.images[i_tem].galleryimg='no'
		}
		else if (document.getElementById)
		document.onmouseup=disableclick
		else if (document.layers)
		associateimages()
		</script>
*/ ?>
		<script language="JavaScript1.2">
			<!--
				// preload next image and previous image
<?php
				echo "\t\t\t\tpreload_image_object = new Image();\n";
				if ($imageSize == "normal") {
					if ($nextImage != null) {
						print("\t\t\t\tpreload_image_object.src = \"/photos/large/".$nextImage['title']."\";\n");
					}
					if ($prevImage != null) {
						print("\t\t\t\tpreload_image_object.src = \"/photos/large/".$prevImage['title']."\";\n");
					}
				} else if ($imageSize == "larger") {
					if ($nextImage != null) {
						print("\t\t\t\tpreload_image_object.src = \"/photos/extraLarge/".$nextImage['title']."\";\n");
					}
					if ($prevImage != null) {
						print("\t\t\t\tpreload_image_object.src = \"/photos/extraLarge/".$prevImage['title']."\";\n");
					}
				}
			// preload addtocart green
			print("preload_image_object.src = \"/images/misc/addToCartGreen.gif\";\n");
?>
			-->
		</script>
		
<?php if ($gallery == 'temples'): ?>
		<?php /*
		<!-- Google Code for View Temple Picture Conversion Page -->
		<script type="text/javascript">
		<!--
		var google_conversion_id = 1040119663;
		var google_conversion_language = "en";
		var google_conversion_format = "3";
		var google_conversion_color = "ffffff";
		var google_conversion_label = "LCPuCNm8nQEQ7-777wM";
		var google_conversion_value = 0;
		if (.05) {
		  google_conversion_value = .05;
		}
		//-->
		</script>
		<noscript>
		<div style="display:inline;">
		<img height="1" width="1" style="border-style:none;" alt="" src="http://www.googleadservices.com/pagead/conversion/1040119663/?value=.05&amp;label=LCPuCNm8nQEQ7-777wM&amp;guid=ON&amp;script=0"/>
		</div>
		</noscript> */ ?>
<?php endif; ?>
		
<?php
			//include("php/googleAnalytics.php");
?>
	</body>
</html>
