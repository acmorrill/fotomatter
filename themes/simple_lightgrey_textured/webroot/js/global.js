/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


jQuery(document).ready(function() {
	jQuery('#main_nav li.main_menu_item').hover(
		function() {
			var prev_element = jQuery(this).prev();
			prev_element.css('borderColor', 'transparent');
		},
		function() {
			var prev_element = jQuery(this).prev();
			prev_element.css('borderColor', '');
		}
	);
});
