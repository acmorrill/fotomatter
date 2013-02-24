<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<script src="/js/jquery-1.7.1.min.js"></script>
<script src="/js/jquery-ui-1.8.18.custom/js/jquery-ui-1.8.18.custom.min.js"></script>

<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('#main_nav li.main_menu_item').mouseover(function() {
			jQuery('#main_nav li.main_menu_item').removeClass('hover');
			jQuery(this).addClass('hover');
		});
	});
</script>


<?php if (Configure::read('debug') >= 2): ?>
<!--<script src="/js/live.js"></script>-->
<?php endif; ?>

