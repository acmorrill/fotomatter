	<?php // DREW TODO - start here tomorrow - make the main menu more generic ?>

	<div>
		<img border=0 id="smallRedBar" src="/images/smallRedBar.gif"/>
	</div>
	<SCRIPT language="JavaScript" SRC="/javascript/navRedBar.js"></SCRIPT>
	<div id="nav">
		<?php $menu_items = $this->ThemeMenu->get_single_menu_items(); ?>
		<?php $count = 0; foreach ($menu_items as $menu_item): ?>
			<?php $menu_item_data = $this->ThemeMenu->get_menu_item_data($menu_item['SiteOneLevelMenu'], $menu_item); ?>
			<?php if (trim($menu_item_data['url'], '/') == trim($this->here, '/')): ?>
				<b><span onmouseover="moveRedBarPos(<?php echo $count; ?>);" class="highlight"><?php echo $menu_item_data['name']; ?> </span></b><br />
				<script type="text/javascript">
					moveRedBarPos(<?php echo $count; ?>);
				</script>
			<?php else: ?>
				<b><a onmouseover="moveRedBarPos(<?php echo $count; ?>);" href="<?php echo $menu_item_data['url']; ?>"><?php echo $menu_item_data['name']; ?> </a></b><br />
			<?php endif; ?>
		<?php $count++; endforeach; ?>
		<?php //debug($menu_items); ?>
	</div>

	
	
	
	
	
<?php
//	echo $this->Element('dimension');
	// cart
//	if (isset($_SESSION['cart'])) {
//		$dimenArray = unserialize($_SESSION['cart']);
//		$cartSize = 0;
//		foreach ($dimenArray as $dimen) {
//			$cartSize += $dimen->numInCart;
//		}
//		if ($cartSize > 0) {
//			if ($page == "cart") {
//				print ("\t\t\t<b><span onmouseover=\"moveRedBarPos(7);\" class=\"highlight\"><img style=\"position: relative; top: 6px;\" src=\"/images/misc/Shoppingcart_16x16.png\"> cart ($cartSize)</span></b><br />\n");
//				print <<<END
//					<script type="text/javascript">
//						<!--
//						moveRedBarPos(7);
//						// -->
//					</script>\n
//END;
//			} else {
//				print ("\t\t\t<b><a onmouseover=\"moveRedBarPos(7);\" href=\"/shoppingcart.php\"><img style=\"position: relative; top: 6px;\" src=\"/images/misc/Shoppingcart_16x16.png\"> cart ($cartSize)</a></b><br />\n");
//			}
//		}
//	}
?>
		
