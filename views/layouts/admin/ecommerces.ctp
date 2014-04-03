<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<title><?php __('Admin Dashboard'); ?></title>
	<script type="text/javascript" src="/js/jquery-1.7.1.min.js"></script>
	<?php echo $this->Element('admin/global_includes'); ?>
	<?php echo $this->Element('admin/global_js'); ?>
</head>
<body>
<div id="main">
	<div id="header">
		<?php echo $this->Element('admin/logo'); ?>
		<?php $curr_page = 'sell'; ?>
		<?php echo $this->Element('admin/menu', array( 'curr_page' => $curr_page )); ?>
	</div>
	<div id="middle" class="rounded-corners" data-step="1" data-intro="<?php echo __('CONTENT HERE', true); ?>" data-position="left">
		<?php 
			$subnav = array(); 

			$subnav['title'] = array(
				'name' => 'Sell',
				'url' => "/admin/ecommerces",
			);
			$subnav['pages'][] = array(
				'name' => __('Manage Print Sizes', true),
				'url' => "/admin/ecommerces/manage_print_sizes/",
				'icon_css' => 'ManagePrintSize-01-01',
			);
			$subnav['pages'][] = array(
				'name' => __('Manage Print Types & Default Pricing', true),
				'url' => "/admin/ecommerces/manage_print_types_and_pricing",
				'icon_css' => 'ManagePrintMaterial-01-01',
			);
			$subnav['pages'][] = array(
				'name' => __('Manage Orders', true),
				'url' => "/admin/ecommerces/order_management",
				'icon_css' => 'Clipboard-01-01',
			);
			$subnav['pages'][] = array(
				'name' => __('Receive Payment', true),
				'url' => "/admin/ecommerces/get_paid/",
				'icon_css' => 'receiveMoney-01',
			);

			echo $this->Element('/admin/submenu', array( 'subnav' => $subnav, 'curr_page' => $curr_page ));
		?>
		<?php echo $this->Session->flash(); ?>
		<br/><br/>
		<?php echo $content_for_layout; ?>
	</div>
	<div id="footer"></div>
</div>
<div id="admin_background"></div>

</body>
</html>