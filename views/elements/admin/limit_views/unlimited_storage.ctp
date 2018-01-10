<div style="clear: both; vertical-align: top;"></div>
<div id="feature_limit" class="center">
	<h1 style='margin-bottom: 10px;'><?php echo __('Unlimited Storage.', true); ?></h1>
	<h2><?php echo __('Upgrade from 100 GB to 250 GB of storage.', true); ?></h2>
	<?php 
		$max_used_space_megabytes = number_format(round($this->Photo->get_max_photo_space(), 2));
		$total_used_space_megabytes = number_format(round($this->Photo->get_total_photo_used_space(), 2));
		echo $this->Element('/admin/limit_views/limit_view_add', array(
			'feature_name' => __('Unlimited Storage', true),
			'feature_ref_name' => 'unlimited_storage',
			'feature_image_src' => '/img/admin/limit_feature_images/limit_ecommerce.jpg',
			'feature_reasons' => array(
				"You are currently using $total_used_space_megabytes MB of the $max_used_space_megabytes MB available &mdash; to upload more photos or modify photos in galleries you can also delete existing photos over the limit of $max_used_space_megabytes MB.",
			),
		)); 
	?>
</div>
