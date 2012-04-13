<script type="text/javascript" src="/js/jquery-1.6.min.js"></script>
<script type="text/javascript" src="/js/jquery-ui-1.8.12/js/jquery-ui-1.8.12.custom.min.js"></script>
<script type="text/javascript" src="/js/jquery-validation-1.8.0/jquery.validate.min.js"></script>



<script type="text/javascript">
	/****************************************************************************
		GLOBAL FUNCTIONS
	****************************************************************************/
	jQuery(document).ready(function() {
		jQuery(document).ajaxError(function(event, request, settings) {
			if (request.status == '403') {
				window.location.replace("/admin/users/login?ajax_autoredirect=<?php echo urlencode($_SERVER["REQUEST_URI"]); ?>");
			}
		});
	});
	
	function major_error_recover(message) {
		alert(message);
	}
</script>
