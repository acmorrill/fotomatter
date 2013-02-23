(function($) {
    $.fn.foto_background_alert = function(args) {
	    var methods = {
                init: function() {
                    //we can only have one of these of the page
                    var total_count = 0;
                    this.each(function() {
                       total_count++; 
                    });
                    if (total_count != 1) {
                        major_error_recover("Background alert was called using a selector that exists more than once on the page. Please try again.");
                        return false;
                    }
                    
                    return this.each(function() {
                        //check data store to see if we have already initialized
                        if ($(this).data('foto_background_alert') != undefined) {
                            major_error_recover("Tried to use background alert twice while on the same element. Please try again.");
                            return false;
                        }
                        
                        //store copy of the div before init
                        var data_store = {};
                        data_store.shown_state = true;
                        
                        //prepare the outer div
                        var outer_div = jQuery("<div></div>");
                        outer_div.css('z-index', '2002');
                        outer_div.css('padding', '35px 25px 5px');
                        outer_div.addClass('message_div');
                        outer_div.addClass('rounded-corners');
                        outer_div.addClass('medium_message_box');
                        outer_div.addClass('drop-shadow');
                        outer_div.addClass('foto_background_alert');
                        
                        //prepare x button
                        var the_x_div = jQuery("<div></div>");
                        the_x_div.addClass('x_button');
                        the_x_div.addClass('abs_image_tr');
                        outer_div.append(the_x_div);
                        
                        var this_literal = $(this);
                        the_x_div.click(function() {
                            this_literal.foto_background_alert('destroy');
                        });
                        
                        $('body').bind('keyup', function(e) {
                            if (e.keyCode == 27) {
                                this_literal.foto_background_alert('destroy');
                            }
                        });
                        
                        //append content
                        var div_passed = $(this).hide().clone().show();
                        data_store.outer_div = outer_div;
                        outer_div.append(div_passed);
                        $('body').append(outer_div);
                        
                        //create the overlay div
                        modal_div = jQuery("<div></div>");
                        modal_div.addClass('ui-widget-overlay');
                        modal_div.css('z-index', '2001');
                        modal_div.css('height', '200%');
                        data_store.modal_div = modal_div;
                        modal_div.addClass('foto-background-alert-modal');
                        $('body').append(modal_div);
                        
                         $(this).data('foto_background_alert', data_store);
                         return true;
                    });
                },
                destroy: function() {
					return this.each(function() {
						if ($(this).data('foto_background_alert') == false) {
							major_error_recover("Tried to destroy a unintialized background element.");
							return false;
						}
						var data_store = $(this).data('foto_background_alert');
						data_store.modal_div.remove();
						data_store.outer_div.remove();
						$(this).removeData('foto_background_alert');
						$('body').unbind('keyup');
						return true;
					});
                }
            }
            
            if (methods[args]) {
                return methods[args].apply(this, Array.prototype.slice.call(arguments, 1));
            } else if (typeof method == 'object' || !args) {
                return methods.init.apply(this, arguments );   
            } else {
                major_error_recover('Method ' + args + ' does not exist in foto_background_alert ');   
            }
            return false;
	};
    
}) (jQuery);