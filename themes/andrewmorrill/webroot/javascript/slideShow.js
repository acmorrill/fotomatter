var imgArray = new Array();
var currImgIndex = 0;

function imageData( path, width, height ) {
	this.path = path;
	this.width = width;
	this.height = height;
}

function addImageToScrollList( path, width, height ) {
	imgArray[imgArray.length] = new imageData( path, width, height );
}

function addAllImages() {
	addImageToScrollList( "/slideShow/Emerald-Flow.jpg", 556, 453 );
	addImageToScrollList( "/slideShow/From-Earth-to-Heaven.jpg", 556, 453 );
	addImageToScrollList( "/slideShow/Ashen-Flame.jpg", 556, 453 );
	addImageToScrollList( "/slideShow/Winter-Berries.jpg", 556, 453 );
	addImageToScrollList( "/slideShow/Ancient-Waterways.jpg", 556, 453 );
	addImageToScrollList( "/slideShow/A-Tangerine-Blue.jpg", 556, 453 );
}

var t;
var started = false;
function startSlideShow() {
	addAllImages();
	t = setInterval("advanceSlideShow()", 6000);
	started = true;
}

function toggleSLStartStop() {
	if (started == true) {
		clearInterval(t);
		started = false;
	} else {
		t = setInterval("advanceSlideShow()", 6000);
		started = true;
	}
}

var count = 1;
function advanceSlideShow() {
	blendimage('slideShowDiv', 'currSlideImage', imgArray[currImgIndex].path, 1200);
	
	if (currImgIndex < imgArray.length-1) {
		currImgIndex++;
	} else {
		currImgIndex = 0;
	}
}