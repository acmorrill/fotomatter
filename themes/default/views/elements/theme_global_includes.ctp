<meta name="viewport" content="width=device-width, initial-scale=1.0"/> 
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" type="text/css" href="/css/theme_global.css" />
<script src="/js/php_closure/theme_global.min.js"></script>

<script type="text/javascript">
	if (!window.console) {
		console = {log: function() {}};
	}
	
	jQuery(document).ready(function() {
		
		///////////////////////////////////////////////////////
		// address page javascript
		function country_select_reset(context, country_id, first_load) {
			if (country_id !== '') {
				var state_cont = jQuery(context).closest('form').find('.state');
				var state_select = jQuery('.state_select', state_cont);
				var url = '/ecommerces/get_available_states_for_country_options/'+country_id+'/';
				if (first_load) {
					var start_state_id = state_select.attr('first_load_id');
					url += start_state_id;
				} 

				jQuery.ajax({
					type: 'post',
					url: url,
					data: {},
					success: function(state_data) {
						if (state_data.count == 0) {
							state_cont.hide();
							state_select.html(state_data.html);
						} else {
							state_select.html(state_data.html);
							state_cont.show();
						}
					},
					complete: function() {
	//						console.log ("complete");
					},
					error: function(jqXHR, textStatus, errorThrown) {
	//						console.log ("error");
	//						console.log (textStatus);
	//						console.log (errorThrown);
					},
					dataType: 'json'
				});
			}
		}
		jQuery('.country_select').each(function() {
			var context = this;
			var country_id = jQuery(context).val();

			country_select_reset(context, country_id, true);
		});
		jQuery('.country_select').change(function() {
			var context = this;
			var country_id = jQuery(context).val();

			country_select_reset(context, country_id, false);
		});
		//------------------------------------------------------
		
		// setup the update cart code
		jQuery('#update_cart_button').click(function(e) {
			jQuery('#update_cart_button_form .update_cart_hidden_field').remove();
			jQuery('#cart_table tbody tr').each(function() {
				var cart_item_key = jQuery(this).attr('data-cart_item_key');
				var cart_item_qty = jQuery('.qty input', this).val();
				jQuery('#update_cart_button_form').prepend('<input class="update_cart_hidden_field" type="hidden" value="' + cart_item_qty + '" name="data[cart_items][' + cart_item_key + ']" />');
			});
			jQuery('#update_cart_button_form').submit();
		});
		
		
		// code for the menu hover
		jQuery('#main_nav li.main_menu_item').mouseover(function() {
			jQuery('#main_nav li.main_menu_item').removeClass('hover');
			jQuery('#main_nav li.main_menu_item').removeClass('last_hover');
			jQuery(this).addClass('hover');
		}).mouseout(function() {
			jQuery('#main_nav li.main_menu_item').removeClass('hover');
			jQuery(this).addClass('last_hover');
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
			
			
			jQuery.removeCookie("frontend_photo_size");
			jQuery.cookie("frontend_photo_size", current_size, {
				expires : 30,
				path    : '/photos/view_photo'
			});

			var new_location = jQuery(this).attr('data-photo_url');

			document.location.href = new_location;
		});
		
		jQuery('.frontend_form_submit_button').click(function() {
			jQuery(this).closest('form').submit();
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




