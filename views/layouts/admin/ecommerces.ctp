<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Element('admin/meta_and_tags/title', array('layout_default' => 'E-commerce')); // can also $title_for_layout in the controller ?>
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
	<div id="middle">
		<?php 
			$subnav = array(); 

			$subnav['title'] = array(
				'name' => 'Sell',
				'url' => "/admin/ecommerces",
			);
			$subnav['pages'][] = array(
				'name' => __('E-commerce Settings', true),
				'url' => "/admin/ecommerces/index/",
				'icon_css' => 'PageSettings-01',
			);
//			$subnav['pages'][] = array(
//				'name' => __('Manage Print Sizes', true),
//				'url' => array(
//					"/admin/ecommerces/manage_print_sizes/",
//					"/admin/ecommerces/add_print_size/",
//				),
//				'icon_css' => 'ManagePrintSize-01-01',
//			);
			$subnav['pages'][] = array(
				'name' => __('Manage Print Types & Default Pricing', true),
				'url' => array(
					"/admin/ecommerces/manage_print_types_and_pricing",
					"/admin/ecommerces/add_print_type_and_pricing",
					"/admin/ecommerces/manage_print_sizes/",
					"/admin/ecommerces/add_print_size/",
				),
				'icon_css' => 'ManagePrintMaterial-01-01',
			);
			$subnav['pages'][] = array(
				'name' => __('Manage Orders', true),
				'url' => array(
					"/admin/ecommerces/order_management",
					"/admin/ecommerces/fulfill_order",
				),
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
		<?php echo $content_for_layout; ?>
	</div>
	<?php echo $this->Element('admin/global_footer'); ?>
</div>
<?php echo $this->Element('admin/global_after_footer'); ?>

</body>
</html>