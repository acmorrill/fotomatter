<meta charset='utf-8'>
<link rel="stylesheet" type="text/css" href="/stylesheets/stylesheet.css" />
<script src="/js/jquery-1.7.1.min.js"></script>
<script src="/js/jquery-ui-1.8.18.custom/js/jquery-ui-1.8.18.custom.min"></script>

<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('#main_nav li.main_menu_item').mouseover(function() {
			jQuery('#main_nav li.main_menu_item').removeClass('hover');
			jQuery(this).addClass('hover');
		});
	});
</script>