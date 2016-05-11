<div id="feature_limit">
	<h1 style='margin-bottom: 10px;'><?php echo __('Need Support With Fotomatter.net?', true); ?></h1>
	<h2><?php echo __('Get Fotomatter.net support.', true); ?></h2>
	<?php echo $this->Element('/admin/limit_views/limit_view_add', array(
		'feature_name' => __('Fotomatter Support', true),
		'feature_ref_name' => 'email_chat_support',
		'feature_image_src' => '/img/admin/limit_feature_images/limit_ecommerce.jpg',
		'feature_reasons' => array(
			"Create a support ticket and get answers in email.",
		),
	)); ?>
</div>
