<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Element('admin/meta_and_tags/title', array('layout_default' => 'Theme Center')); // can also $title_for_layout in the controller ?>
	<?php echo $this->Element('admin/global_includes'); ?>
	<?php echo $this->Element('admin/global_js'); ?>
</head>
<body>
<div id="main">
	<div id="header">
		<?php echo $this->Element('admin/logo'); ?>
		<?php echo $this->Element('admin/menu', array( 'curr_page' => 'theme_center' )); ?>
	</div>
	<div id="middle" class="rounded-corners">
		<?php 
//			$subnav = array();
//
//			$subnav['title'] = array(
//				'name' => __('Theme Center', true),
//				'url' => "/admin/theme_centers",
//			);
//			$subnav['pages'][] = array(
//				'name' => __('Choose Theme', true),
//				'url' => "/admin/theme_centers/choose_theme/",
//				'icon_css' => 'ChooseTheme_icon',
//			);
//			$subnav['pages'][] = array(
//				'name' => __('Current Theme Settings', true),
//				'url' => "/admin/theme_centers/theme_settings/",
//				'icon_css' => 'settings',
//				'help_step' => array(
//					'url' => "/admin/theme_centers/choose_theme/",
//					'step_code' => 'data-step="3" data-intro="' . __("After you’ve selected your theme, click “Current Theme Settings” to begin your customizations, or select from the top menu to add galleries, photos, and pages.", true) . '" data-position="right"',
//				),
//			);
//			$subnav['pages'][] = array(
//				'name' => __('Main Menu', true),
//				'url' => "/admin/theme_centers/main_menu/",
//				'icon_css' => 'menu',
//			);
//			$subnav['pages'][] = array(
//				'name' => __('Configure Logo', true),
//				'url' => "/admin/theme_centers/configure_logo/",
//				'icon_css' => 'ConfigureLogo-01',
//			);
//			$subnav['pages'][] = array(
//				'name' => __('Configure Theme Background', true),
//				'url' => "/admin/theme_centers/configure_background/",
//				'icon_css' => 'picture',
//			);
//
//			echo $this->Element('/admin/submenu', array( 'subnav' => $subnav ));
		?>
		<?php echo $this->Session->flash(); ?>
		<?php echo $content_for_layout; ?>
	</div>
	<?php echo $this->Element('admin/global_footer'); ?>
</div>
<?php echo $this->Element('admin/global_after_footer'); ?>


</body>
</html>