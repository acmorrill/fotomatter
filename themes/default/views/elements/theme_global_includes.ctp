<meta name="viewport" content="width=device-width, initial-scale=1.0"/> 
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<script type="text/javascript" src="/js/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="/js/jquery-ui-1.11.0/jquery-ui.min.js"></script>
<script src="/js/fadeSlideShow.js"></script>

<script type="text/javascript">
	if (!window.console) {
		console = {log: function() {}};
	}
	
	jQuery(document).ready(function() {
		jQuery('#main_nav li.main_menu_item').mouseover(function() {
			jQuery('#main_nav li.main_menu_item').removeClass('hover');
			jQuery(this).addClass('hover');
		}).mouseout(function() {
			jQuery('#main_nav li.main_menu_item').removeClass('hover');
		});
	});
</script>

<link rel="stylesheet" type="text/css" href="/css/theme_global.css" />

<?php if (Configure::read('debug') >= 2): ?>
<!--<script src="/js/live.js"></script>-->
<?php endif; ?>




