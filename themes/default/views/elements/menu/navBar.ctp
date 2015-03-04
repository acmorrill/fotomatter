<div id="main_nav">
	<ul>
		<?php 
			$show_home_in_menu = $this->Theme->get_theme_global_setting('show_home_link_in_menu', true); 
			if ($show_home_in_menu == true) {
				$cart_item_data = array();
				$cart_item_data['type'] = 'System';
				$cart_item_data['id'] = 0;
				$cart_item_data['name'] = "Home";
				$cart_item_data['url'] = '/';
				$cart_item_data['display_type'] = 'System';
				echo $this->Element('menu/menu_item', array('menu_item_data' => $cart_item_data, 'li_class' => 'main_menu_item')); 
			}
		?>
		<?php $menu_items = $this->ThemeMenu->get_single_menu_items(); ?>
		<?php $count = 0; foreach ($menu_items as $menu_item): ?>
			<?php $menu_item_data = $this->ThemeMenu->get_menu_item_data($menu_item['SiteOneLevelMenu'], $menu_item); ?>
			<?php echo $this->Element('menu/menu_item', array('menu_item_data' => $menu_item_data, 'li_class' => 'main_menu_item')); ?>
		<?php $count++; endforeach; ?>
		<?php
			if (!empty($current_on_off_features['basic_shopping_cart'])) {
				$count_items_in_cart = $this->Cart->count_items_in_cart();
				$cart_item_data = array();
				$cart_item_data['type'] = 'System';
				$cart_item_data['id'] = 0;
				$cart_item_data['name'] = "";
				$cart_item_data['name_html'] = "<span class='icon-cart'></span>";
				$cart_item_data['url'] = '/ecommerces/view_cart';
				$cart_item_data['display_type'] = 'System';
				echo $this->Element('menu/menu_item', array('menu_item_data' => $cart_item_data, 'li_class' => 'main_menu_item|cart_link')); 
			}
		?>
	</ul>
</div>