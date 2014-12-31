<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Element('admin/meta_and_tags/title', array('layout_default' => 'Configure Page')); // can also $title_for_layout in the controller ?>
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
					'selected' => true,
					'icon_css' => 'PageSettings-01'
				);

				if (isset($this->data['SitePage']['type']) && $this->data['SitePage']['type'] == 'custom') {
					$subnav['pages'][] = array(
						'name' => __('Configure Page', true),
						'url' => "/admin/site_pages/configure_page/{$this->data['SitePage']['id']}/",
						'icon_css' => 'configurePage-01'
					);
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