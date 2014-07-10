<div id="nav_location">
	<div id="main_nav">
		<ul class="top_level">
			<?php $two_level_menu_items = $this->ThemeMenu->get_two_level_menu_items(); ?>
			<?php //debug($two_level_menu_items); ?>
			<?php $count = 0;
			foreach ($two_level_menu_items as $two_level_menu_item):
				?>
				<?php $menu_item_data = $this->ThemeMenu->get_menu_item_data($two_level_menu_item['SiteTwoLevelMenu'], $two_level_menu_item); ?>
					<?php if (isset($menu_item_data['submenu_items'])): ?>
						<li class="main_menu_item menu_item_container">
								<?php echo $menu_item_data['name']; ?><span class="extra"></span>
							<ul class="second_level">
								<?php if (!empty($menu_item_data['submenu_items'])): ?>
									<?php foreach ($menu_item_data['submenu_items'] as $submenu_item): ?>
										<?php echo $this->Element('menu/menu_item', array('menu_item_data' => $submenu_item, 'li_class' => 'sub_menu_item')); ?>
									<?php endforeach; ?>
								<?php else: ?>
									<li class="sub_menu_item hover highlight">
										Empty-Add menu items <span class="extra"></span>
									</li>
								<?php endif; ?>
								
								
							</ul>
						</li>
					<?php else: ?>
						<?php echo $this->Element('menu/menu_item', array('menu_item_data' => $menu_item_data, 'li_class' => 'main_menu_item')); ?>
					<?php endif; ?>
				<?php $count++;
			endforeach;
			?>
		</ul>
	</div>
</div>