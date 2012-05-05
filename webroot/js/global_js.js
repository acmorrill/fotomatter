/*******************************
 *Global constants
 ********************************/
var MODO_SHOW_TIME_DEFAULT = 5000;

/****
 *Global start up behavio
 ****/
jQuery(document).ready(function() {
	jQuery(document).ajaxStart(function(event, request, settings) {
		jQuery('body').css('cursor', 'progress');
	});
	jQuery(document).ajaxStop(function(event, request, settings) {
		jQuery('body').css('cursor', 'default');
	});
});

/****************************************************************************
		GLOBAL FUNCTIONS
	****************************************************************************/
function smart_reload(message) {
	show_modal(message, 2000, function() {
		window.location.reload();
	});
}
   
function show_modal(message, time_to_show, after_hide_callback) {
	var modo_div = jQuery("<div>"+message+"</div>");
	modo_div.addClass('ui-widget-overlay');
	modo_div.css('z-index', '2001');
	jQuery('body').append(modo_div);
	
	if(time_to_show == undefined) time_to_show = MODO_SHOW_TIME_DEFAULT;
	
	setTimeout(function() {
		jQuery('.ui-widget-overlay').remove();
		if (after_hide_callback != undefined) {
			after_hide_callback();
		}
	}, time_to_show);
}

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