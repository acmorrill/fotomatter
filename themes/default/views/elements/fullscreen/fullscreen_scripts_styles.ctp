<div id="fullScreen">
	<div class="fullImage">
		<img class="loading" src="/js/Gallery/img/loading.gif">
	</div>

	<a href="#" class="close-full-screen" title="Close Fullscreen" onClick="closeFullScreen(); return false;"><span class="icon-fullscreen_exit"></span></a>
</div>
		
<script type="text/javascript">
	jQuery(document).ready(function() {

		//in case user closes fullscreen with Esc button
		jQuery(document).bind('webkitfullscreenchange mozfullscreenchange fullscreenchange', function(e) {
			var state = document.fullScreen || document.mozFullScreen || document.webkitIsFullScreen;
			var event = state ? 'FullscreenOn' : 'FullscreenOff';
		
			if(event === 'FullscreenOff'){
				closeFullScreen();
			}   
		});
	});

	function makeFullScreen() {
		const fullScreen = jQuery("#fullScreen").get(0);
		jQuery("#fullScreen").show();
		let divObj = jQuery(".slide_show_image:visible");
		divObj = divObj[divObj.length - 1];
		const rel = jQuery(divObj).attr('rel');

		// // ajax to get the fullsize img? instead of preloaded images?
		jQuery.ajax({
			type : 'post',
			url : '/photos/ajax_get_photo_details/',
			data : {'photo_id': rel, 'height': 2000, 'width': 3000},//window.screen.height, 'width': window.screen.width},
			success : function (image) {
				jQuery("#fullScreen .fullImage").html('<img src="'+image.url+'" style="margin:auto !important;width:'+image.widthStyle+' !important; height:'+image.heightStyle+' !important;" />');
			},
			error: function (er) { console.error(er) },
			dataType: "json"
		}); 

		//Use the specification method before using prefixed versions
		if (fullScreen.requestFullscreen) {
			fullScreen.requestFullscreen();
		} else if (fullScreen.msRequestFullscreen) {
			fullScreen.msRequestFullscreen();               
		} else if (fullScreen.mozRequestFullScreen) {
			fullScreen.mozRequestFullScreen();      
		} else if (fullScreen.webkitRequestFullscreen) {
			fullScreen.webkitRequestFullscreen();       
		} else {
			console.warn("Fullscreen API is not supported");
		} 

	}

	function closeFullScreen() {
		jQuery(".hiddenFull, #fullScreen").hide();
		jQuery("#fullScreen .fullImage").html('<img class="loading" src="/js/Gallery/img/loading.gif">');

		if(document.exitFullscreen) {
			document.exitFullscreen();
		} else if(document.mozCancelFullScreen) {
			document.mozCancelFullScreen();
		} else if(document.webkitExitFullscreen) {
			document.webkitExitFullscreen();
		}

	}
</script>
