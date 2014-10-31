/*******************************
 *Global constants
 ********************************/
var MODO_SHOW_TIME_DEFAULT = 5000;
var verticle_sortable_defaults = {
	axis : 'y',
	opacity: .7/*,
	containment: 'parent'*/
};

/****
 *Global start up behavio
 ****/
jQuery(document).ready(function() {
	jQuery('.javascript_submit').click(function(){ 
		jQuery(this).closest('form').submit();
	});
	
	jQuery('a.disabled, .disabled a').click(function(e) { 
		e.preventDefault();
	});
	
	jQuery('.add_feature_button').click(function() {
		var ref_feature_name = jQuery(this).attr('ref_feature_name');
		window.location.href = '/admin/accounts/index/' + ref_feature_name;
	});
	
	jQuery(document).ajaxStart(function(event, request, settings) {
	//        console.log('start');
		jQuery('body, div, img, button').addClass('cursor-progress');
	});
	jQuery(document).ajaxStop(function(event, request, settings) {
	//        console.log('stop');
		jQuery('body, div, img, button').removeClass('cursor-progress');	
	});
        
	
	/************************************/
	/* globally setup any text defaults */
	/************************************/
    $(".defaultText").focus(function(srcc) {
		if (jQuery(this).is("textarea")) {
			if ($(this).text() == $(this).attr('title')) {
				$(this).removeClass("defaultTextActive");
				$(this).text("");
			}
		} else {
			if ($(this).val() == $(this).attr('title')) {
				$(this).removeClass("defaultTextActive");
				$(this).val("");
			}
		}
    });
    $(".defaultText").blur(function() {
		if (jQuery(this).is("textarea")) {
			if ($(this).text() == "") {
				$(this).addClass("defaultTextActive");
				$(this).text($(this).attr('title'));
			}
		} else {
			if ($(this).val() == "") {
				$(this).addClass("defaultTextActive");
				$(this).val($(this).attr('title'));
			}
		}
		
    });
    $(".defaultText").blur(); 
	
	/************************************************/
	/* globally setup add buttons (for add anthing) */
	/************************************************/
	//jQuery('.add_button').button();
	
	
	
	
	// setup the help button click event
	jQuery('#help_tour_button').click(function() {
		introJs().start();
	});
	
	// setup the back button
	jQuery('#back_button').click(function() {
		window.history.back();
	});
	
});

/****************************************************************************
	GLOBAL FUNCTIONS
****************************************************************************/
function show_universal_save() {
	jQuery('#universal_load_popup').stop().hide();
	jQuery('#universal_save_popup').stop().fadeIn('fast');
}
function hide_universal_save() {
	jQuery('#universal_load_popup').stop().hide();
	jQuery('#universal_save_popup').stop().fadeOut('slow');
}
function show_universal_load() {
	jQuery('#universal_save_popup').stop().hide();
	jQuery('#universal_load_popup').stop().fadeIn('fast');
}
function hide_universal_load() {
	jQuery('#universal_save_popup').stop().hide();
	jQuery('#universal_load_popup').stop().fadeOut('slow');
}
function smart_reload(message) {
	message = '<div style="display:inline-block;vertical-align:middle;margin:5px;margin:0 25px 15px 0">'+message+'</div>';
	message += '<img src="/img/admin/icons/ajax-loader.gif" />';
	show_modal(message, 750, function() {
		window.location.reload();
	}, false);
}
var modo_div;
var message_div;

function remove_modal() { // just used in the configure background page
	modo_div.remove();
	message_div.remove();
}

function show_modal(message, time_to_show, after_hide_callback, remove_after,css) { // just used in the configure background page
	modo_div = jQuery("<div></div>");
	modo_div.addClass('ui-widget-overlay');
	modo_div.css('z-index', '2001'); 
	modo_div.css('height', '200%');
	
	message_div = jQuery("<div class='message_div medium_message_box drop-shadow'>"+message+"</div>");
	message_div.css('z-index', '2002'); 

	
	jQuery('body').append(modo_div);
	jQuery('body').append(message_div);
	
	
	var window_width = jQuery(window).width();
	var message_width = message_div.width();
	var center_left = (window_width / 2) - (message_div.width() / 2); 
	message_div.css('left', center_left);
	
	
	if (css != undefined) {
		for(var x in css) {
//			console.log(x);
//			console.log(css[x]);
			message_div.css(x, css[x]);
		}
	}
	
	
	if(time_to_show == undefined) time_to_show = MODO_SHOW_TIME_DEFAULT;
	if (remove_after == undefined) remove_after = true;
	
//	setTimeout(function() {
//		if (after_hide_callback != undefined) {
//			after_hide_callback();
//		}
//		if (remove_after) {
//			remove_modal();
//		}
//	}, time_to_show);
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

function microtime(get_as_float) {
	//  discuss at: http://phpjs.org/functions/microtime/
	// original by: Paulo Freitas
	//   example 1: timeStamp = microtime(true);
	//   example 1: timeStamp > 1000000000 && timeStamp < 2000000000
	//   returns 1: true

	var now = new Date().getTime() / 1000;
	var s = parseInt(now, 10);

	return (get_as_float) ? now : (Math.round((now - s) * 1000) / 1000) + ' ' + s;
}
	
function Timeout(fn, interval) {
	var context = this;
	this.cleared = false;
	var id = setTimeout(function() {
		context.cleared = true;
		fn();
	}, interval);
	this.clear = function () {
		context.cleared = true;
		clearTimeout(id);
	};
	this.run_now = function() {
		if (context.cleared === false) {
			clearTimeout(id);
			context.cleared = true;
			fn();
		}
	};
}

function position_of_element_among_siblings(children_selector, child) {
	var children = jQuery(children_selector);
	
	var final_position = 1;
	var found_position = false;
	children.each(function() {
		if (jQuery(this)[0] === child[0]) {
			found_position = true;
			return false;
		}
		final_position++;
	});
	
	if (found_position === true) {
		return final_position;
	} else {
		return false;
	}
}

function ucwords (str) {
    return (str + '').replace(/^([a-z])|\s+([a-z])/g, function ($1) {
        return $1.toUpperCase();
    });
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
