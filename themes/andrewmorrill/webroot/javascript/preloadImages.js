image_url = new Array();

function preloadSlideShowImages() {
	image_url[0] = "/slideShow/A-Tangerine-Blue.jpg";
	image_url[1] = "/slideShow/Emerald-Flow.jpg";
	image_url[2] = "/slideShow/From-Earth-to-Heaven.jpg";
	image_url[3] = "/slideShow/Ashen-Flame.jpg";
	image_url[4] = "/slideShow/Winter-Berries.jpg";
	image_url[5] = "/slideShow/Ancient-Waterways.jpg";
	preloadImages(6);
}

function preloadImages(numImages) {

    if (document.images)
    {
		preload_image_object = new Image();
		var i = 0;
		for(i = 0; i < numImages; i++) 
			preload_image_object.src = image_url[i];
    }  
}
  