<div id="configure_main_menu_cont" style="width: 462px; float: left; padding: 20px; min-height: 500px; margin: 20px; margin-top: 0px; margin-left: 0px;">
	<?php // class="content-background" ?>
	<?php // echo $this->Element('admin/theme_center/main_menu/list_main_menu_items'); ?>
	<?php echo $this->Element('admin/sub_submenu', array( 
		'tabs' => array(
			'Single Level Menu' => 'admin/theme_center/main_menu/single_level_menu_listing',
			'Two Level Menu' => 'admin/theme_center/main_menu/two_level_menu_listing'
		)
	)); ?>
	
	
	<div class="clear"></div>
</div>

<div class="custom_ui" style="float: left; margin-left: 20px;">
	<button id="add_menu_item_button" class="add_button"><?php __('Add Menu Item'); ?></button>
</div>

<div class="clear"></div>


