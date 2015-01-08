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
		
		
		// setup the code for changing the image size on the image page
		jQuery('.sizing_tools .sizing_button').click(function() {
			if (jQuery(this).hasClass('active')) {
				return false;
			}
			
			var current_size = 'small';
			if (jQuery(this).hasClass('small')) {
				current_size = 'small';
			} else if (jQuery(this).hasClass('medium')) {
				current_size = 'medium';
			} else if (jQuery(this).hasClass('large')) {
				current_size = 'large';
			}
			
			jQuery.cookie("frontend_photo_size", current_size, {
			   expires : 30,
			   path    : '/photos/view_photo',
			});
			
			document.location.reload();
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




