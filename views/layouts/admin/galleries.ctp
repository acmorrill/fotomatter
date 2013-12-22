<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<title><?php __('Admin Dashboard'); ?></title>
	<script type="text/javascript" src="/js/jquery-1.7.1.min.js"></script>
	<?php echo $this->Element('admin/global_includes'); ?>
	<?php echo $this->Element('admin/global_js'); ?>
</head>
<body>
<div id="main" class="no_subnav">
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
					'url' => "/admin/photo_galleries/edit_gallery/{$this->data['PhotoGallery']['id']}/"
				);
				if ($this->data['PhotoGallery']['type'] === 'smart') {
					$subnav['pages'][] = array(
						'name' => __('Smart Gallery Settings', true),
						'url' => "/admin/photo_galleries/edit_smart_gallery/{$this->data['PhotoGallery']['id']}/"
					);
				} else if ($this->data['PhotoGallery']['type'] === 'standard') {
					$subnav['pages'][] = array(
						'name' => __('Connect Photos', true),
						'url' => "/admin/photo_galleries/edit_gallery_connect_photos/{$this->data['PhotoGallery']['id']}/"
					);
					$subnav['pages'][] = array(
						'name' => __('Arrange Photos', true),
						'url' => "/admin/photo_galleries/edit_gallery_arrange_photos/{$this->data['PhotoGallery']['id']}/"
					);
				}

				echo $this->Element('/admin/submenu', array( 'subnav' => $subnav ));
			}
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