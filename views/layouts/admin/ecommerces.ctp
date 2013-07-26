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
	<div class="top_links">

	</div>
	<div class="below_links">
		<div id="header">
			<?php echo $this->Element('admin/logo'); ?>
			<?php echo $this->Element('admin/menu', array( 'curr_page' => 'sell' )); ?>
		</div>
		<div id="middle" class="rounded-corners">
			<?php 
				$subnav = array(); 

				$subnav['title'] = array(
					'name' => 'Sell',
					'url' => "/admin/ecommerces"
				);
				$subnav['pages'][] = array(
					'name' => __('Manage Print Sizes', true),
					'url' => "/admin/ecommerces/manage_print_sizes/"
				);
				$subnav['pages'][] = array(
					'name' => __('Manage Print Types and Default Pricing', true),
					'url' => "/admin/ecommerces/manage_print_types_and_pricing"
				);
				$subnav['pages'][] = array(
					'name' => __('Order Management', true),
					'url' => "/admin/ecommerces/order_management"
				);
				$subnav['pages'][] = array(
					'name' => __('Get Paid', true),
					'url' => "/admin/ecommerces/get_paid/"
				);

				echo $this->Element('/admin/submenu', array( 'subnav' => $subnav ));
			?>
			<?php echo $content_for_layout; ?>
		</div>
		<div id="footer"></div>
	</div>
</div>


</body>
</html>