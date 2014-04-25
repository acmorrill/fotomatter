<div style="clear: both; vertical-align: top;"></div>
<div id="feature_limit" class="center">
	<h1 style='margin-bottom: 10px;'><?php echo __('Create Dynamic Pages.', true); ?></h1>
	<h2><?php echo __('Creating Custom Pages Takes Your Website to Another Level.', true); ?></h2>
	<?php echo $this->Element('/admin/limit_views/limit_view_add', array(
		'feature_name' => __('Page Builder', true),
		'feature_ref_name' => 'page_builder',
		'feature_image_src' => '/img/admin/limit_feature_images/limit_ecommerce.jpg',
		'feature_reasons' => array(
			"Create unlimited custom pages and link to them from your menu.",
			"Create a custom contact page.",
			"Create pages that link to external sites such as a blog.",
		),
	)); ?>
</div>
