function sleep(miliseconds) {
   var currentTime = new Date().getTime();

   while (currentTime + miliseconds >= new Date().getTime()) {}
}
var total_images = 0;
var loaded_images = 0;
function update_progress_bar() {
	var total_progress = Math.round((loaded_images / total_images) * 100);
	jQuery(document).trigger('image_load_progress', [ total_progress ]);
	if (total_progress == 100) {
		jQuery(document).trigger('images_loaded');
	}
}
jQuery(document).ready(function() {
	jQuery(document).bind('preload_images_for_progress', function(e, images_to_load) {
		if (images_to_load == undefined) {
			images_to_load = jQuery('img.preload_for_progress');
		}
		
		images_to_load.each(function() {
			total_images++;
		});
		images_to_load.each(function() {
			var raw_element = $(this)[0];
			if (raw_element.complete == true) {
				loaded_images++;
				update_progress_bar();
			} else {
				var img_src = $(this).attr('src');
				var tmpImg = document.createElement('img'); // new Image(1, 1); 
				tmpImg.onload = function() {
					loaded_images++;
					update_progress_bar();
				};
				tmpImg.error = function() {
					loaded_images++;
					update_progress_bar();
				};
				tmpImg.src = img_src;
			}
		});
	});
});