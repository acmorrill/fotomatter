<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<title><?php __('Pages'); ?></title>
	<?php echo $this->Element('admin/global_includes'); ?>
	<?php echo $this->Element('admin/global_js'); ?>
	
	<?php echo $this->Element('admin/photo/jquery_fileupload_includes'); ?>
</head>
<body>
<div id="main">
	<div id="header">
		<?php echo $this->Element('admin/logo'); ?>
		<?php echo $this->Element('admin/menu', array( 'curr_page' => 'pages' )); ?>
	</div>
	<div id="middle" class="rounded-corners">
		<?php 
			if (isset($this->data['SitePage']['id'])) {
				$subnav = array(); 

				$subnav['title'] = array(
					'name' => "Page: {$this->data['SitePage']['id']}",
					'url' => "/admin/site_pages/edit_page/{$this->data['SitePage']['id']}/"
				);
				$subnav['pages'][] = array(
					'name' => __('Page Settings', true),
					'url' => "/admin/site_pages/edit_page/{$this->data['SitePage']['id']}/",
					'selected' => true
				);

				if (isset($this->data['SitePage']['type']) && $this->data['SitePage']['type'] == 'custom') {
					$subnav['pages'][] = array(
						'name' => __('Configure Page', true),
						'url' => "/admin/site_pages/configure_page/{$this->data['SitePage']['id']}/"
					);
				}

				echo $this->Element('/admin/submenu', array( 'subnav' => $subnav ));
			}
		?>
		<?php echo $this->Session->flash(); ?>
		<?php echo $content_for_layout; ?>
	</div>
	<div id="footer"></div>
</div>
<div id="admin_background"></div>

</body>
</html>