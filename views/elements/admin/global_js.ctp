<script type="text/javascript" src="/js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="/js/jquery-ui-1.8.18.custom/js/jquery-ui-1.8.18.custom.min.js"></script>
<script type="text/javascript" src="/js/jquery-validation-1.8.0/jquery.validate.min.js"></script>

<link href="/js/jquery-ui-1.8.18.custom/css/custom-theme/jquery-ui-1.8.18.custom.css" rel="stylesheet" type="text/css" />

<script type="text/javascript">
	/****************************************************************************
		GLOBAL FUNCTIONS
	****************************************************************************/
	jQuery(document).ready(function() {
		jQuery(document).ajaxError(function(event, request, settings) {
			if (request.status == '403') {
				window.location.replace("/admin/users/login?ajax_autoredirect=<?php echo urlencode($_SERVER["REQUEST_URI"]); ?>");
			} else {
				alert('An ajax error occured! -- this from global_js'); // TODO - make this more sophisticated
			}
		});
	});
	
	function major_error_recover(message) {
		alert('error:'+message);
	}
	
	function element_is_empty(element_id) {
		var child;
		var hasChildElements = false;
		for (child = document.getElementById(element_id).firstChild;
			 child;
			 child = child.nextSibling
			) {

			if (child.nodeType == 1) { // 1 == Element
				hasChildElements = true;
				break;
			}
		}
		
		return !hasChildElements;
	}
	
	jQuery.fn.pulse = function( properties, duration, numTimes, interval, complete_callback) {  

		if (duration === undefined || duration < 0) duration = 500;
		if (duration < 0) duration = 500;

		if (numTimes === undefined) numTimes = 1;
		if (numTimes < 0) numTimes = 0;

		if (interval === undefined || interval < 0) interval = 0;
		
		if (complete_callback === undefined) {
			complete_callback = function() {}
		}

		return this.each(function() {
			var $this = jQuery(this);
			var origProperties = {};
			for (property in properties) {
				origProperties[property] = $this.css(property);
			}

			for (var i = 0; i < numTimes; i++) {
				if (i + 1 == numTimes) { 
					window.setTimeout(function() {
						$this.animate(
							properties,
							{
								duration:duration / 2,
								complete:function() {
									$this.animate(origProperties, {
										duration: duration / 2,
										complete: function() {
											$this.removeAttr('style');
											complete_callback();
										}
									});
								}
							}
						);
					}, (duration + interval) * i);
				} else {
					window.setTimeout(function() {
						$this.animate(
							properties,
							{
								duration:duration / 2,
								complete:function() {
									$this.animate(origProperties, {
										duration: duration / 2,
										complete: function() {
											$this.removeAttr('style');
										}
									});
								}
							}
						);
					}, (duration + interval) * i);
				}
			}
	   });

	};
</script>
