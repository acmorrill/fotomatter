<script type="text/javascript">
	if (!window.console) {
		console = {log: function() {}};
	}
</script>

<script type="text/javascript" src="/js/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="/js/jquery-ui-1.11.0/jquery-ui.min.js"></script>

<script type="text/javascript" src="/js/tinymce/jscripts/tiny_mce/jquery.tinymce.js"></script>
<script type="text/javascript" src="/js/money_format/accounting.min.js"></script>
<script type="text/javascript" src="/js/chosen_v1.1.0/chosen.jquery.min.js"></script>
<link href="/js/chosen_v1.1.0/chosen.min.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="/js/global_js.js"></script>
<link href="/js/jquery-ui-1.11.0/jquery-ui.min.css" rel="stylesheet" type="text/css" />

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
	});
        
var window_loaded = false;
$(window).load(function() {
	window_loaded = true; 
});
	
(function($) {
	var methods = {
		alert: function(message, args) {
			var settings = $.extend( {
				'type' : 'alert',
				'onOk' : function() {
					
				}
			}, args);
			
			var alert_div = $("<div class='gen_alert "+settings.type+"'><p class='alert_or_confirm'><i class='icon-warning-01'></i>" + message + "</p></div>")
			
			$(alert_div).dialog({
				title: "<?php echo __('Alert', true); ?>",
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
</script>