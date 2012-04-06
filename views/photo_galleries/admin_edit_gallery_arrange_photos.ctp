<h1><?php __('Gallery Edit'); ?></h1>

<?php 
	$subnav = array(); 

	$subnav['title'] = array(
		'name' => $photo_gallery['PhotoGallery']['display_name'],
		'url' => "/admin/photo_galleries/edit_gallery/{$photo_gallery['PhotoGallery']['id']}/"
	);
	$subnav['pages'][] = array(
		'name' => __('Gallery Settings', true),
		'url' => "/admin/photo_galleries/edit_gallery/{$photo_gallery['PhotoGallery']['id']}/"
	);
	$subnav['pages'][] = array(
		'name' => __('Connect Photos', true),
		'url' => "/admin/photo_galleries/edit_gallery_connect_photos/{$photo_gallery['PhotoGallery']['id']}/"
	);
	$subnav['pages'][] = array(
		'name' => __('Arrange Photos', true),
		'url' => "/admin/photo_galleries/edit_gallery_arrange_photos/{$photo_gallery['PhotoGallery']['id']}/",
		'selected' => true
	);
		
	echo $this->Element('/admin/submenu', array( 'subnav' => $subnav ));
?>


<?php debug($photo_gallery); ?>