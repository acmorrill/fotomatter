<script type="text/javascript">
	if (!window.console) {
		console = {log: function() {}};
	}
</script>

<script>
	(function(i, s, o, g, r, a, m) {
		i['GoogleAnalyticsObject'] = r;
		i[r] = i[r] || function() {
			(i[r].q = i[r].q || []).push(arguments)
		}, i[r].l = 1 * new Date();
		a = s.createElement(o),
				m = s.getElementsByTagName(o)[0];
		a.async = 1;
		a.src = g;
		m.parentNode.insertBefore(a, m)
	})(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

	ga('create', 'UA-58390519-1', 'auto');
	ga('send', 'pageview');
</script>



<script type="text/javascript" src="/js/php_closure/fotomatter_admin.min.js"></script>

<script type="text/javascript">
	/****************************************************************************
		GLOBAL FUNCTIONS
	****************************************************************************/
	jQuery(document).ready(function() {
		// do confirm for link deletes
		jQuery('.delete_link').click(function(e) {
			e.preventDefault();
			var context = this;

			jQuery.foto('confirm', {
				message: '<?php echo __('Do you really want to remove the item?', true); ?>',
				onConfirm: function() {
					window.location.href = jQuery(context).attr('href');
				},
				'title' : '<?php echo __('Really delete?', true); ?>',
				'button_title' : '<?php echo __('Delete', true); ?>'
			});
		});
		
		
		jQuery(document).ajaxError(function(event, request, settings) {
			if (request.status == '403') {
				window.location.replace("/admin/users/login?ajax_autoredirect=<?php echo urlencode($_SERVER["REQUEST_URI"]); ?>");
			} else if(request.status == '412') {
				if (typeof(sync) != 'undefined') {
					sync();
				} else {
					smart_reload('Syncing');
				}
			} else if(request.status == 0) {
//				alert('A ajax call was canceled');
			} else {
				major_error_recover('an ajax error occured');
//				alert('An ajax error occured! -- this from global_js'); // DREW TODO - make this more sophisticated
//				console.log (event);
//				console.log (request);
			}
		});
		
		<?php if (isset($current_locking_hash) && isset($current_locking_hash_namespace)): ?>
		$.ajaxSetup({
			data: {
				global_current_js_locking_hash: '<?php echo $current_locking_hash; ?>',
				global_current_js_locking_hash_namespace: '<?php echo $current_locking_hash_namespace; ?>'
			}
		});
		var hash_input = jQuery("<input name='global_current_js_locking_hash' type='hidden' value='<?php echo $current_locking_hash; ?>' />");
		var hash_namespace = jQuery("<input name='global_current_js_locking_hash_namespace' type='hidden' value='<?php echo $current_locking_hash_namespace; ?>' />");
		jQuery('form').each(function() {
			jQuery(this).append(hash_input);
			jQuery(this).append(hash_namespace);
		});
		<?php endif; ?>
	
	
		// setup chosen based on class
		jQuery('.chzn-select').chosen();
		
		jQuery('html').attr('data-site_domain', '<?php echo $site_domain; ?>');
	});
        
	
(function($) {
	var methods = {
		alert: function(message, args) {
			var settings = $.extend( {
				'title' : '<?php echo __('Alert', true); ?>',
				'type' : 'alert',
				'onOk' : function() {
					
				}
			}, args);
			
			var alert_div = $("<div class='gen_alert "+settings.type+"'><p class='alert_or_confirm'><i class='icon-warning-01'></i>" + message + "</p></div>")
			
			$(alert_div).dialog({
				title: settings.title,
				dialogClass: "thin_dialog",
				buttons: {
					'<?php echo __('Ok', true); ?>': function() {
						if (typeof settings.onOk == 'function') {
							settings.onOk();
						}
						$(this).dialog('close');
					}
				},
				close: function() {
					alert_div.remove();
				},
//				minWidth: 400,
				minHeight: 200,
				modal: true,
				resizable: false
			});
		},
		confirm: function(args) {
			var settings = $.extend( {
				'title' : '<?php echo __('Confirm', true); ?>',
				'button_title' : '<?php echo __('Confirm', true); ?>',
				'onConfirm' : function() {
					
				},
				'type' : 'alert',
				'message': '<?php echo __('Are you sure?', true); ?>',
				'minWidth': 400,
				'minHeight': 200
			}, args);
			
			var confirm_div = $("<div class='gen_confirm "+settings.type+"'><p class='alert_or_confirm'><i class='icon-warning-01'></i>" + settings.message + "</p></div>")
			
			$(confirm_div).dialog({
				title: settings.title,
				dialogClass: "thin_dialog",
				buttons: [
					{
						text: settings.button_title,
						click: function() {
							if (typeof settings.onConfirm == 'function') {
								settings.onConfirm();
							}
							$( this ).dialog( "close" );
						}
					},
					{
						text: "<?php echo __('Cancel', true); ?>",
						click: function() {
							$( this ).dialog( "close" );
						}
					}
				],
				close: function() {
					confirm_div.remove();
				},
//				minWidth: settings.minWidth,
				minHeight: settings.minHeight,
				modal: true,
				resizable: false
			});
		}
	};
	
	
	$.foto = function( function_name, args) {
		if (methods[function_name]) {
			return methods[function_name].apply(this, Array.prototype.slice.call(arguments, 1));
		}
		return false;
	};
})(jQuery);

	function show_browser_not_supported_popup() {
		alert("<?php echo __('Your web browser is not fully supported for fotomatter.net. You can continue using this browser, but it\'s possible some features will not work correctly. Use the latest version of Firefox, Chrome or IE to remove this message.', true); ?>");
	}

	jQuery(document).ready(function() {
		<?php if ($browser_is_supported === false): ?>
			<?php if ($showed_supported_browser_popup === false): ?>
				show_browser_not_supported_popup();
			<?php endif; ?>
			jQuery('#browser_not_supported').show();
			jQuery('#browser_not_supported').click(function() {
				show_browser_not_supported_popup();
			});
		<?php endif; ?>
		
			
		<?php if (empty($done_welcome_first_login_popup)): ?>
			var first_time_div = $("<div class='gen_alert alert'>\n\
				<p class='alert_or_confirm'><i class='icon-Success-01'></i>\n\
					Welcome to the admin area of your new photography website!\n\
				</p>\n\
				<ul class='alert_or_confirm'>\n\
					<li>To get back to this admin area simply go to <a target='_blank' href='https://<?php echo $site_domain; ?>.fotomatter.net/admin'><?php echo $site_domain; ?>.fotomatter.net/admin</a></li>\n\
					<li>To see your actual website go to <a target='_blank' href='http://<?php echo $site_domain; ?>.fotomatter.net'><?php echo $site_domain; ?>.fotomatter.net</a></li>\n\
					<li>On most pages in the admin area you can click on the \"Get Help With This Page >\" button. Be sure and use this button if you need help.</li>\n\
					<li>Take a few minutes to look around!</li>\n\
				</ul>\n\
			</div>");
			
			$(first_time_div).dialog({
				title: '<?php echo __('Welcome', true); ?>',
				dialogClass: "wide_dialog",
				buttons: {
					'<?php echo __('Ok', true); ?>': function() {
						$(this).dialog('close');
					}
				},
				close: function() {
					first_time_div.remove();
				},
//				minWidth: 400,
				minHeight: 200,
				modal: true,
				resizable: false
			});
		<?php endif; ?>
	});


</script>
