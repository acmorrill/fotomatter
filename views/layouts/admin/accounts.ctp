<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html  ng-app='fmAdmin'>
<head>
	<title><?php __('Admin Dashboard'); ?></title>
	<?php echo $this->Element('admin/global_includes'); ?>
	<?php echo $this->Element('admin/global_js'); ?>
	<?php echo $this->Element('admin/angular'); ?>
</head>
<body>
<div id="main">
	<div id="header">
		<?php echo $this->Element('admin/logo'); ?>
		<?php echo $this->Element('admin/menu', array( 'curr_page' => 'accounts' )); ?>
	</div>
	<div id="middle" class="rounded-corners">
					<?php 
			$subnav = array(); 

			$subnav['title'] = array(
				'name' => 'Upgrade Account',
				'url' => "/admin/accounts"
			);
			$subnav['pages'][] = array(
				'name' => __('Account Details', true),
				'url' => "/admin/accounts/account_details/"
			);
			$subnav['pages'][] = array(
				'name'=> __('Site Domains', true),
				'url'=>"/admin/domains"
			);
			echo $this->Element('/admin/submenu', array( 'subnav' => $subnav )); 
		?>
		<?php echo $this->Session->flash(); ?>
		<?php echo $content_for_layout; ?>
	</div>
	<div id="footer"></div>
</div>
<div id="admin_background"></div>

</body>
</html>