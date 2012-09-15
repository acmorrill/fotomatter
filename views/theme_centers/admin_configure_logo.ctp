<div id="configure_logo_cont" style="padding: 20px; margin: 20px; margin-top: 0px; margin-left: 0px;">
	<?php // class="content-background" ?>
	<?php // echo $this->Element('admin/theme_center/main_menu/list_main_menu_items'); ?>
	<?php echo $this->Element('admin/sub_submenu', array( 
		'tabs' => array(
			'Choose Logo' => 'admin/theme_center/configure_logo/logo_upload',
			'Logo Dimensions in Theme' => 'admin/theme_center/configure_logo/theme_logo_position',
			'Logo Position in Theme' => 'admin/theme_center/configure_logo/theme_logo_size'
		),
		'width' => 770
	)); ?>
	
	
	<div class="clear"></div>
</div>