<script type="text/javascript" src="/js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="/js/jquery-ui-1.8.18.custom/js/jquery-ui-1.8.18.custom.min.js"></script>
<script type="text/javascript" src="/js/jquery-validation-1.8.0/jquery.validate.min.js"></script>
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
			} else {
				alert('An ajax error occured! -- this from global_js'); // TODO - make this more sophisticated
				console.log (event);
				console.log (request);
			}
		});
		
		<?php if (isset($current_locking_hash) && isset($current_locking_hash_namespace)): ?>
		$.ajaxSetup({
			data: {
				global_current_js_locking_hash: '<?php echo $current_locking_hash; ?>',
				global_current_js_locking_hash_namespace: '<?php echo $current_locking_hash_namespace; ?>'
			}
		});
		<?php endif; ?>
	});
</script>
