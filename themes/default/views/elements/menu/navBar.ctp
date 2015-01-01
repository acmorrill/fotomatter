<div id="main_nav">
	<ul>
		<?php $menu_items = $this->ThemeMenu->get_single_menu_items(); ?>
		<?php $count = 0; foreach ($menu_items as $menu_item): ?>
			<?php $menu_item_data = $this->ThemeMenu->get_menu_item_data($menu_item['SiteOneLevelMenu'], $menu_item); ?>
			<?php echo $this->Element('menu/menu_item', array('menu_item_data' => $menu_item_data, 'li_class' => 'main_menu_item')); ?>
		<?php $count++; endforeach; ?>
		<?php
			$count_items_in_cart = $this->Cart->count_items_in_cart();
			$cart_item_data = array();
			$cart_item_data['type'] = 'System';
			$cart_item_data['id'] = 0;
			$cart_item_data['name'] = "Cart";
			$cart_item_data['url'] = '/ecommerces/view_cart';
			$cart_item_data['display_type'] = 'System';
			echo $this->Element('menu/menu_item', array('menu_item_data' => $cart_item_data, 'li_class' => 'main_menu_item|cart_link')); 
		?>
	</ul>
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
		
