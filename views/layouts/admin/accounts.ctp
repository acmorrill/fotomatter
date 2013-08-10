<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html  ng-app>
<head>
	<title><?php __('Admin Dashboard'); ?></title>
	<script type="text/javascript" src="/js/jquery-1.7.1.min.js"></script>
	<?php echo $this->Element('admin/global_includes'); ?>
	<?php echo $this->Element('admin/global_js'); ?>
</head>
<body>
<div id="main">
	<div class="top_links">

	</div>
	<div class="below_links">
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
				echo $this->Element('/admin/submenu', array( 'subnav' => $subnav )); 
			?>
			<?php echo $this->Session->flash(); ?>
			<br/><br/>
			<?php echo $content_for_layout; ?>
		</div>
		<div id="footer"></div>
	</div>
</div>


</body>
</html>