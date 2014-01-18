<meta name="viewport" content="width=device-width, initial-scale=1.0"/> 
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="/js/jquery-ui-1.8.18.custom/js/jquery-ui-1.8.18.custom.min.js"></script>

<script type="text/javascript">
	if (!window.console) {
		console = {log: function() {}};
	}
	
	jQuery(document).ready(function() {
		jQuery('#main_nav li.main_menu_item').mouseover(function() {
			jQuery('#main_nav li.main_menu_item').removeClass('hover');
			jQuery(this).addClass('hover');
		});
	});
</script>

<link rel="stylesheet" type="text/css" href="/css/global_ecommerce.css" />

<?php if (Configure::read('debug') >= 2): ?>
<!--<script src="/js/live.js"></script>-->
<?php endif; ?>

