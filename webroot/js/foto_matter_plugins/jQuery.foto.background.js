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
                        if ($(this).data('foto_background_alert')) {
                            major_error_recover("Tried to use background alert while twice on the same element. Please try again.");
                            return false;
                        }
                        
                        //store copy of the div before init
                        var data_store = {};
                        data_store.div_pre_init_state = $(this);
                        $(this).data('foto_background_alert', data_store);
                        
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
                        outer_div.append(the_x_div);
                        
                        //append content
                        $(this).show();
                        outer_div.append($(this));
                        $('body').append(outer_div);
                        
                        //create the overlay div
                        modal_div = jQuery("<div></div>");
                        modal_div.addClass('ui-widget-overlay');
                        modal_div.css('z-index', '2001');
                        modal_div.css('height', '200%');
                        modal_div.addClass('foto-background-alert-modal');
                        $('body').append(modal_div);  
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