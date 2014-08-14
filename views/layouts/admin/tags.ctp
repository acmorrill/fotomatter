<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<title><?php __('Pages'); ?></title>
	<?php echo $this->Element('admin/global_includes'); ?>
	<?php echo $this->Element('admin/global_js'); ?>
	
	<script src="/js/angular_1.2.22/bower_components/angular/angular.js"></script>
	<script src="/js/angular_1.2.22/bower_components/angular-animate/angular-animate.js"></script>
	<script src="/js/angular_1.2.22/bower_components/angular-route/angular-route.js"></script>
	<script src="/js/angular_1.2.22/bower_components/angular-resource/angular-resource.js"></script>
	
	<script src="/js/angular_1.2.22/app/js/app.js"></script>
	<script src="/js/angular_1.2.22/app/js/controllers.js"></script>
	<script src="/js/angular_1.2.22/app/js/services.js"></script>
</head>
<body ng-app="fotomatterApp">
<div id="main" class="no_subnav">
	<div id="header">
		<?php echo $this->Element('admin/logo'); ?>
		<?php echo $this->Element('admin/menu', array( 'curr_page' => $curr_page )); ?>
	</div>
	<div id="middle" class="rounded-corners">
		<?php echo $this->Session->flash(); ?>
		<?php echo $content_for_layout; ?>
	</div>
	<div id="footer"></div>
</div>
<div id="admin_background"></div>

</body>
</html>