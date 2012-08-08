<?php

echo $this->Element('dimension');
//--------------------------------------------------------------------------------------------------------------------------------
// this page creates a nav bar based on an external variable "page" that says which page needs the nav bar.
//--------------------------------------------------------------------------------------------------------------------------------

	// setup
	print ("\t\t<div>\n");
	print ("\t\t\t<img border=0 id=\"smallRedBar\" src=\"/images/smallRedBar.gif\"/>\n");
	print ("\t\t</div>\n");
	print ("<SCRIPT language=\"JavaScript\" SRC=\"/javascript/navRedBar.js\"></SCRIPT>\n");
	print ("\t\t<div id=\"nav\">\n");

	// home
	if ($page == "home") {
		print ("\t\t\t<a href=\"/site_pages/landing_page\"><span onmouseover=\"moveRedBarPos(0);\" class=\"highlight\">home</span></a><br />\n");
	} else {
		print ("\t\t\t<a onmouseover=\"moveRedBarPos(0);\" href=\"/site_pages/landing_page\">home</a><br />\n");
	}

	// bio
	if ($page == "bio") {
		print ("\t\t\t<span onmouseover=\"moveRedBarPos(1);\" class=\"highlight\">about</span><br />\n");
		print <<<END
			<script type="text/javascript">
				<!--
				moveRedBarPos(1);
				// -->
			</script>\n
END;
	} else {
		print ("\t\t\t<a onmouseover=\"moveRedBarPos(1);\" href=\"bio.php\">about</a><br />\n");
	}

	// gallery
	if ($page == "gallery") {
		print ("\t\t\t<a onmouseover=\"moveRedBarPos(2);\" href=\"/themes/choose_gallery\"><span onmouseover=\"moveRedBarPos(2);\" class=\"highlight\">image galleries</span></a><br />\n");
		print <<<END
			<script type="text/javascript">
				<!--
				moveRedBarPos(2);
				// -->
			</script>\n
END;
	} else {
		print ("\t\t\t<a onmouseover=\"moveRedBarPos(2);\" href=\"/themes/choose_gallery\">image galleries</a><br />\n");
	}
	
	// printInfo
	if ($page == "printInfo") {
		print ("\t\t\t<span onmouseover=\"moveRedBarPos(3);\" class=\"highlight\">print info</span><br />\n");
		print <<<END
			<script type="text/javascript">
				<!--
				moveRedBarPos(3);
				// -->
			</script>\n
END;
	} else {
		print ("\t\t\t<a onmouseover=\"moveRedBarPos(3);\" href=\"printInfo.php\">print info</a><br />\n");
	}
	
	// news
	if ($page == "news") {
		print ("\t\t\t<span onmouseover=\"moveRedBarPos(4);\" class=\"highlight\">news</span><br />\n");
		print <<<END
			<script type="text/javascript">
				<!--
				moveRedBarPos(4);
				// -->
			</script>\n
END;
	} else {
		print ("\t\t\t<a onmouseover=\"moveRedBarPos(4);\" href=\"news.php\">news</a><br />\n");
	}
	
	// blog
//	print ("\t\t\t<a onmouseover=\"moveRedBarPos(5);\" href=\"http://www.celestiallightphotography.com/blog/\" target=\"_blank\">blog</a><br />\n");
	
	// contact
/*	if ($page == "contact") {
		print ("\t\t\t<span onmouseover=\"moveRedBarPos(6);\" class=\"highlight\">contact</span><br />\n");
		print <<<END
			<script type="text/javascript">
				<!--
				moveRedBarPos(6);
				// -->
			</script>\n
END;
	} else {
		print ("\t\t\t<a onmouseover=\"moveRedBarPos(6);\" href=\"contact.php\">contact</a><br />\n");
	}*/
	
	// cart
	if (isset($_SESSION['cart'])) {
		$dimenArray = unserialize($_SESSION['cart']);
		$cartSize = 0;
		foreach ($dimenArray as $dimen) {
			$cartSize += $dimen->numInCart;
		}
		if ($cartSize > 0) {
			if ($page == "cart") {
				print ("\t\t\t<b><span onmouseover=\"moveRedBarPos(7);\" class=\"highlight\"><img style=\"position: relative; top: 6px;\" src=\"/images/misc/Shoppingcart_16x16.png\"> cart ($cartSize)</span></b><br />\n");
				print <<<END
					<script type="text/javascript">
						<!--
						moveRedBarPos(7);
						// -->
					</script>\n
END;
			} else {
				print ("\t\t\t<b><a onmouseover=\"moveRedBarPos(7);\" href=\"shoppingcart.php\"><img style=\"position: relative; top: 6px;\" src=\"/images/misc/Shoppingcart_16x16.png\"> cart ($cartSize)</a></b><br />\n");
			}
		}
	}

	// close nav bar
	print ("\t\t</div>\n\n");
?>	
