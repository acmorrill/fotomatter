<div id="feature_limit">
	<h1 style='margin-bottom: 10px;'><?php echo __('Get Paid For Your Talent.', true); ?></h1>
	<h2><?php echo __('Setting Up E-Commerce Makes it Simple.', true); ?></h2>
	<?php echo $this->Element('/admin/limit_views/limit_view_add', array(
		'feature_name' => __('E-commerce', true),
		'feature_ref_name' => 'basic_shopping_cart',
		'feature_image_src' => '/img/admin/limit_feature_images/limit_ecommerce.jpg',
		'feature_reasons' => array(
			"Create custom print types to match the prints and options you offer to your customers.",
			"Use our simple pricing system to flexibly and easily set prices for your products.",
			"Manage your orders with ease. Take advantage of our unique order system.",
		),
	)); ?>
</div>
