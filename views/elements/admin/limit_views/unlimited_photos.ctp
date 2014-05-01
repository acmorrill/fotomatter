<div style="clear: both; vertical-align: top;"></div>
<div id="feature_limit" class="center">
	<h1 style='margin-bottom: 10px;'><?php echo __('Unlimited Photos.', true); ?></h1>
	<h2><?php echo __('Upload As Many Photos As You Need To.', true); ?></h2>
	<?php $curr_limit = LIMIT_MAX_FREE_PHOTOS; echo $this->Element('/admin/limit_views/limit_view_add', array(
		'feature_name' => __('Unlimited Photos', true),
		'feature_ref_name' => 'unlimited_photos',
		'feature_image_src' => '/img/admin/limit_feature_images/limit_ecommerce.jpg',
		'feature_reasons' => array(
			"Upload an unlimited number of photos.",
			"NOTE: To upload more photos you can also delete existing photos over the limit of $curr_limit.",
		),
	)); ?>
</div>
