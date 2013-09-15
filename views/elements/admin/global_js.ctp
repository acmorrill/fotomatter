<script type="text/javascript">
	if (!window.console) {
		console = {log: function() {}};
	}
</script>

<script type="text/javascript" src="/js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="/js/jquery-ui-1.8.18.custom/js/jquery-ui-1.8.18.custom.min.js"></script>

<script type="text/javascript" src="/js/tinymce/jscripts/tiny_mce/jquery.tinymce.js"></script>
<script type="text/javascript" src="/js/money_format/accounting.min.js"></script>
<script type="text/javascript" src="/js/chosen/chosen/chosen.jquery.min.js"></script>
<link href="/js/chosen/chosen/chosen.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="/js/global_js.js"></script>
<link href="/js/jquery-ui-1.8.18.custom/css/custom-theme/jquery-ui-1.8.18.custom.css" rel="stylesheet" type="text/css" />

<script type="text/javascript">
	/****************************************************************************
		GLOBAL FUNCTIONS
	****************************************************************************/
	jQuery(document).ready(function() {
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
			
			var alert_div = $("<div class='gen_alert "+settings.type+"'>"+message+"</div>")
			
			$(alert_div).dialog({
				title: "<?php __('Alert'); ?>",
				buttons: {
					'<?php __('Ok'); ?>': function() {
						if (typeof settings.onOk == 'function') {
							settings.onOk();
						}
						$(this).dialog('close');
					}
				},
				close: function() {
					alert_div.remove();
				},
				minWidth: 300,
				minHeight: 200,
				modal: true,
				resizable: false
			});
		},
		confirm: function(args) {
			var settings = $.extend( {
				'title' : '<?php __('Confirm'); ?>',
				'button_title' : '<?php __('Confirm'); ?>',
				'onConfirm' : function() {
					
				},
				'type' : 'alert',
				'message': '<?php __('Are you sure?'); ?>',
				'minWidth': 300,
				'minHeight': 200
			}, args);
			
			var confirm_div = $("<div class='gen_confirm "+settings.type+"'>"+settings.message+"</div>")
			
			$(confirm_div).dialog({
				title: settings.title,
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
						text: "<?php __('Cancel'); ?>",
						click: function() {
							$( this ).dialog( "close" );
						}
					}
				],
				close: function() {
					confirm_div.remove();
				},
				minWidth: settings.minWidth,
				minHeight: settings.minHeight,
				modal: true,
				resizable: false
			});
		}
	}
	
	
	$.foto = function( function_name, args) {
		if (methods[function_name]) {
			return methods[function_name].apply(this, Array.prototype.slice.call(arguments, 1));
		}
		return false;
	};
})(jQuery);
</script>