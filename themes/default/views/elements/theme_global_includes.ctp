<meta name="viewport" content="width=device-width, initial-scale=1.0"/> 
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" type="text/css" href="/css/theme_global.css" />
<script src="/js/php_closure/theme_global.min.js"></script>

<script type="text/javascript">
	if (!window.console) {
		console = {log: function() {}};
	}
	
	jQuery(document).ready(function() {
		// code for the menu hover
		jQuery('#main_nav li.main_menu_item').mouseover(function() {
			jQuery('#main_nav li.main_menu_item').removeClass('hover');
			jQuery(this).addClass('hover');
		}).mouseout(function() {
			jQuery('#main_nav li.main_menu_item').removeClass('hover');
		});
		
		
		// grab the cart totals
		jQuery.ajax({
			type: 'post',
			url: '/ecommerces/check_frontend_cart',
			data: {},
			success: function(data) {
				var cart_link = jQuery('.cart_link');
				if (data > 0) {
					jQuery('span.cart_item_content', cart_link).text("cart (" + data + ")");
				}
			},
			complete: function() {

			},
			error: function(jqXHR, textStatus, errorThrown) {
				
			},
			dataType: 'json'
		});
	});
</script>


<?php if (Configure::read('debug') >= 2): ?>
<!--<script src="/js/live.js"></script>-->
<?php endif; ?>




