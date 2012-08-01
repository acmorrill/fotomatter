var imgArray = new Array();
var currImgIndex = 0;

function setCurrImageIndex(i) {
	currImgIndex = i;
}

function imageData( path, width, height ) {
	this.path = path;
	this.width = width;
	this.height = height;
}

function addImageData( path, width, height ) {
	imgArray[imgArray.length] = new imageData( path, width, height );
}

function changepicture(image, imageData) {
	//image.width=width=imageData.width;
	//image.height=height=imageData.height;
	image.src=src=imageData.path;
}

function setImage( id, path ) {
	var image = document.getElementById(id);
	image.src=src=imageData.path;
}

function nextImage() {
	var image = document.getElementById("FullSizeImage");
	if (currImgIndex < imgArray.length-1) {
		currImgIndex++;
	} else {
		currImgIndex = 0;
	}
	changepicture(image, imgArray[currImgIndex]);
}

function prevImage() {
	var image = document.getElementById("FullSizeImage");
	if (currImgIndex > 0) {
		currImgIndex--;
	} else {
		currImgIndex = imgArray.length-1;
	}
	changepicture(image, imgArray[currImgIndex]);
}