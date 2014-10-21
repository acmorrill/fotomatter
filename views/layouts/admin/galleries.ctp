<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<title><?php __('Admin Dashboard'); ?></title>
	<?php echo $this->Element('admin/global_includes'); ?>
	<?php echo $this->Element('admin/global_js'); ?>
</head>
<body>
<div id="main">
	<div id="header">
		<?php echo $this->Element('admin/logo'); ?>
		<?php echo $this->Element('admin/menu', array( 'curr_page' => 'galleries' )); ?>
	</div>
	<div id="middle" class="rounded-corners">
		<?php 
			if (isset($this->data['PhotoGallery']['id'])) {
				$subnav = array(); 

				$subnav['title'] = array(
					'name' => 'Gallery Name',
					'url' => "/admin/photo_galleries/edit_gallery/{$this->data['PhotoGallery']['id']}/"
				);
				$subnav['pages'][] = array(
					'name' => __('Gallery Settings', true),
					'url' => "/admin/photo_galleries/edit_gallery/{$this->data['PhotoGallery']['id']}/",
					'icon_css' => 'gallerySettings-01'
				);
				if ($this->data['PhotoGallery']['type'] === 'smart') {
					$subnav['pages'][] = array(
						'name' => __('Smart Gallery Settings', true),
						'url' => "/admin/photo_galleries/edit_smart_gallery/{$this->data['PhotoGallery']['id']}/",
						'icon_css' => 'managePhotos-01'
					);
				} else if ($this->data['PhotoGallery']['type'] === 'standard') {
					$subnav['pages'][] = array(
						'name' => __('Manage Gallery Photos', true),
						'url' => "/admin/photo_galleries/edit_gallery_connect_photos/{$this->data['PhotoGallery']['id']}/",
						'icon_css' => 'managePhotos-01'
					);
//					$subnav['pages'][] = array(
//						'name' => __('Arrange Photos', true),
//						'url' => "/admin/photo_galleries/edit_gallery_arrange_photos/{$this->data['PhotoGallery']['id']}/"
//					);
				}

				echo $this->Element('/admin/submenu', array( 'subnav' => $subnav ));
			}
		?>
		<?php echo $this->Session->flash(); ?>
		<?php echo $content_for_layout; ?>
	</div>
	<?php echo $this->Element('admin/global_footer'); ?>
</div>
<?php echo $this->Element('admin/global_after_footer'); ?>

</body>
</html>