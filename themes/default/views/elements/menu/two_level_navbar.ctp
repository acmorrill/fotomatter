<div id="nav_location">
	<div id="main_nav">
		<ul class="top_level">
			<?php 
				$show_home_in_menu = $this->Theme->get_theme_global_setting('show_home_link_in_menu', true); 
				if ($show_home_in_menu == true) {
					$cart_item_data = array();
					$cart_item_data['type'] = 'System';
					$cart_item_data['id'] = 0;
					$cart_item_data['name'] = "Home";
					$cart_item_data['url'] = '/';
					$cart_item_data['basic_url'] = '/';
					$cart_item_data['display_type'] = 'System';
					echo $this->Element('menu/menu_item', array('menu_item_data' => $cart_item_data, 'li_class' => 'main_menu_item')); 
				}
			?>
			
			
			<?php $two_level_menu_items = $this->ThemeMenu->get_two_level_menu_items(); ?>
			<?php $count = 0; foreach ($two_level_menu_items as $two_level_menu_item): ?>
				<?php $menu_item_data = $this->ThemeMenu->get_menu_item_data($two_level_menu_item['SiteTwoLevelMenu'], $two_level_menu_item); ?>
				<?php if (isset($menu_item_data['submenu_items'])): ?>
					<?php
						$in_current_menu = false;
						if (!empty($menu_item_data['submenu_items'])) {
							foreach ($menu_item_data['submenu_items'] as $submenu_item) {
								$test_url = $submenu_item['url'];
								if ($this->Util->startsWith(trim($this->here, '/'), trim($test_url, '/'))) {
									$in_current_menu = true;
									break;
								} 
							}
						}
						$sub_menu_classes = '';
						if ($in_current_menu) {
							$sub_menu_classes = 'highlight last_hover';
						}
					?>
					<li class="main_menu_item menu_item_container <?php echo $sub_menu_classes; ?>">
						<?php echo $menu_item_data['name']; ?><span class="extra"></span>
						<div class="second_level">
							<ul class="second_level_inner">
								<?php if (!empty($menu_item_data['submenu_items'])): ?>
									<?php foreach ($menu_item_data['submenu_items'] as $submenu_item): ?>
										<?php echo $this->Element('menu/menu_item', array('menu_item_data' => $submenu_item, 'li_class' => 'sub_menu_item', 'in_sub_sub' => true)); ?>
									<?php endforeach; ?>
								<?php else: ?>
									<li class="sub_menu_item hover highlight">
										Empty - Add menu items <span class="extra"></span>
									</li>
								<?php endif; ?>
							</ul>
						</div>
					</li>
				<?php else: ?>
					<?php echo $this->Element('menu/menu_item', array('menu_item_data' => $menu_item_data, 'li_class' => 'main_menu_item')); ?>
				<?php endif; ?>
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
					$cart_item_data['basic_url'] = '/ecommerces/';
					$cart_item_data['force_clickable'] = 1;
					$cart_item_data['display_type'] = 'System';
					echo $this->Element('menu/menu_item', array('menu_item_data' => $cart_item_data, 'li_class' => 'main_menu_item|cart_link')); 
				}
			?>
		</ul>
	</div>
</div>