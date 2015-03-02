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
	jQuery(document).bind('preload_images_for_progress', function() {
		jQuery('img.preload_for_progress').each(function() {
			total_images++;
			
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