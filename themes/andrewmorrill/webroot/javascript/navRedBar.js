/*--------------------------------------
	Move red bar code
--------------------------------------*/
var topVal = 339;
var offSetPerLine = 18;
var timeToResetBar;
var barResetDelay = 400;

function moveRedBarPos(offSetsFromTop) {
	var redBar = document.getElementById('smallRedBar');
	redBar.style.top = topVal + (offSetsFromTop * offSetPerLine);
}