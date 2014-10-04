<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<title><?php __('Theme Center'); ?></title>
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
			$subnav = array();

			$subnav['title'] = array(
				'name' => __('Theme Center', true),
				'url' => "/admin/theme_centers",
			);
			$subnav['pages'][] = array(
				'name' => __('Choose Theme', true),
				'url' => "/admin/theme_centers/choose_theme/",
				'icon_css' => 'monitor',
			);
			$subnav['pages'][] = array(
				'name' => __('Current Theme Settings', true),
				'url' => "/admin/theme_centers/theme_settings/",
				'icon_css' => 'settings',
			);
			$subnav['pages'][] = array(
				'name' => __('Main Menu', true),
				'url' => "/admin/theme_centers/main_menu/",
				'icon_css' => 'menu',
			);
			$subnav['pages'][] = array(
				'name' => __('Configure Logo', true),
				'url' => "/admin/theme_centers/configure_logo/",
				'icon_css' => 'blah',
				'hide_on_mobile' => true,
			);
			$subnav['pages'][] = array(
				'name' => __('Configure Theme Background', true),
				'url' => "/admin/theme_centers/configure_background/",
				'icon_css' => 'picture',
				'hide_on_mobile' => true,
			);

			echo $this->Element('/admin/submenu', array( 'subnav' => $subnav ));
		?>
		<?php echo $this->Session->flash(); ?>
		<?php echo $content_for_layout; ?>
	</div>
	<?php echo $this->Element('admin/global_footer'); ?>
</div>
<?php echo $this->Element('admin/global_after_footer'); ?>


</body>
</html>