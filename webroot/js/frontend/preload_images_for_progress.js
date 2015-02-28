function sleep(miliseconds) {
   var currentTime = new Date().getTime();

   while (currentTime + miliseconds >= new Date().getTime()) {}
}
var total_images = 0;
var loaded_images = 0;
function update_progress_bar() {
//	sleep(2000);
	var total_progress = Math.round((loaded_images / total_images) * 100);
	jQuery(document).trigger('image_load_progress', [ total_progress ]);
	if (total_progress == 100) {
		jQuery(document).trigger('images_loaded');
	}
}
jQuery(document).ready(function() {
	jQuery(document).bind('preload_images_for_progress', function() {
		var preload_images = jQuery('img.preload_for_progress');
		preload_images.each(function() {
			total_images++;
		});
		preload_images.each(function() {
			var img_src = $(this).attr('src');
			var tmpImg = document.createElement('img'); // new Image(1, 1); 
			tmpImg.src = img_src;

			if (!tmpImg.complete) {
				loaded_images++;
				update_progress_bar();
			} else {
				var tmpImg2 = document.createElement('img'); // new Image(1, 1); 
				tmpImg2.onload = function() {
					loaded_images++;
					update_progress_bar();
				};
				tmpImg2.error = function() {
					loaded_images++;
					update_progress_bar();
				};
				tmpImg2.src = img_src;
			}
		});
	});
});