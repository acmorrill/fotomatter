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
 *Global start up behavior
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
function show_universal_save(show_modal) {
//	console.log('show save');
	if (show_modal === undefined) {
		show_modal = false;
	}
	
	jQuery('#universal_load_popup').stop().hide();
	jQuery('#universal_save_popup').stop().fadeIn('fast');
	
	if (show_modal === true) {
		jQuery('#global_modal_background').stop().show();
	}
}
function hide_universal_save() {
//	console.log('hide save');
	jQuery('#universal_load_popup, #universal_save_popup, #global_modal_background').stop().fadeOut('slow');
}
function show_universal_load(show_modal) {
//	console.log('show load');
	if (show_modal === undefined) {
		show_modal = false;
	}
	
	jQuery('#universal_save_popup').stop().hide();
	jQuery('#universal_load_popup').stop().fadeIn('fast');
	
	if (show_modal === true) {
		jQuery('#global_modal_background').stop().show();
	}
}
function hide_universal_load() {
//	console.log('hide load');
	jQuery('#universal_load_popup, #universal_save_popup, #global_modal_background').stop().fadeOut('slow');
}

function do_features_popup_call(url) {
	if (typeof inAjaxCall == 'boolean' && inAjaxCall) {
		return false;
	}
	inAjaxCall = true;

	$.ajax({
		type: 'GET',
		url: url,
		success: function(data) {
			jQuery('#account_change_finish').dialog('destroy').remove();

			jQuery('body').append(data.html);
			jQuery('.ui-dialog').prepend('<div class="fade_background_top"></div>');
			var flash_message = jQuery('#account_change_finish .flashMessage');
			flash_message.detach();
			flash_message.insertBefore(jQuery('#account_change_finish'));
		},
		complete: function() {
			inAjaxCall = false;
			hide_universal_save();
		},
		error: function() {

		},
		dataType: 'json'
	});
}
function open_add_profile_popup() {
	do_features_popup_call('/admin/accounts/ajax_update_payment/closeWhenDone:false');
}
function open_add_profile_popup_clone_when_done() {
	do_features_popup_call('/admin/accounts/ajax_update_payment/closeWhenDone:true');
}
function open_finish_account_change() {
	do_features_popup_call("/admin/accounts/ajax_finishLineChange");
}
function open_finish_account_change_nocc_confirm() {
	do_features_popup_call("/admin/accounts/ajax_finishLineChange/noCCPromoConfirm:true");
}

function major_error_recover(message) {
	var e = new Error('dummy');
	var data_to_post = {};
	data_to_post.stack = e.stack.replace(/^[^\(]+?[\n$]/gm, '')
	.replace(/^\s+at\s+/gm, '')
	.replace(/^Object.<anonymous>\s*\(/gm, '{anonymous}()@')
	.split('\n');
	data_to_post.location = window.location;
	data_to_post.message = message;
	
	jQuery.ajax({
		type: 'post',
		url: '/admin/accounts/record_frontend_major_error',
		data: {
			data: JSON.stringify(data_to_post)
		},
		success: function(data) {
			// posted error successufully
		},
		complete: function() {
			// complete
		},
		error: function(jqXHR, textStatus, errorThrown) {
			// error
		},
		dataType: 'json'
	});
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
