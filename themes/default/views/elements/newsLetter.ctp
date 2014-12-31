<?php
	$firstTimeVisit = true;
	// count each visit
	if ($firstTimeVisit) {
		$_SESSION['newsLetterVisit'] = 1;
	} else {
		$_SESSION['newsLetterVisit'] = $_SESSION['newsLetterVisit'] + 1;
	}
	// change to first time visit every twenty page views
	if ($_SESSION['newsLetterVisit'] % 10 == 0) {
		$firstTimeVisit = true;
	}
?>
		<script src="/javascript/easing.js"></script>
		<script src="/email/prototype.js" type="text/javascript"></script>
		<script src="/email/email.js" type="text/javascript"></script>
		<script type="text/javascript" src="http://www.googleadservices.com/pagead/conversion.js">
		</script>
		
		<script type="text/javascript">
		<!--
			/*var google_conversion_id = 1040119663;
			var google_conversion_language = "en";
			var google_conversion_format = "3";
			var google_conversion_color = "ffffff";
			var google_conversion_label = "APCCCJvYmgEQ7-777wM";
			var google_conversion_value = 0;
			function closeNewsletter() {
				jQuery("#newsletterContainer").animate({top: -80}, {queue:false, duration:1000, easing:"sineEaseOut"});
			}
			function trackNewsLetterConv() {
				//alert('label'+google_conversion_label);
				var image = new Image(1,1); 
				image.src = "http://www.googleadservices.com/pagead/conversion/"+google_conversion_id+"/?label="+google_conversion_label+"&amp;guid=ON&amp;script=0"; 
			} */
		-->
		</script>
		<link rel="stylesheet" href="/email/style.css" type="text/css">
			<script language="javascript">
			if (getcookie("newsletter") == null) {
				preload_image_object = new Image();
				preload_image_object.src = "/images/misc/error_arrow_up.gif";
				document.write("<div id='newsletterContainer' style='top: <?php echo ($firstTimeVisit)? ('-80px'): ('-5px'); ?>;'>");
				document.write("<div id='results'>");
				<?php if ($firstTimeVisit): ?>
					jQuery(window).load(function() {
						setTimeout ( "jQuery('#newsletterContainer').animate({top: -5}, {queue:false, duration:1000, easing:'bounceEaseOut'})", 300 );
					});
				<?php endif; ?>
				jQuery(document).ready(function(){
					jQuery("img.button").unbind('click').click(function () {
						jQuery("span#newsletterWarning").html("<img src='/images/misc/error_arrow_up.gif' />Please Enter an Email Address");
					});
					jQuery('input#newsletter').each(function(){
						this.value = jQuery(this).attr('title');
						jQuery(this).addClass('text-label');

						jQuery(this).focus(function(){
							if(this.value == jQuery(this).attr('title')) {
								this.value = '';
								jQuery(this).removeClass('text-label');
							}
		  
							jQuery(this).unbind('keypress').keypress(function(e) {
								if(e.keyCode == 9) {
									e.preventDefault();
								}
							});
							jQuery(this).unbind('keyup').keyup(function(e) {
								checkemail();
								if(e.keyCode == 13) {
									jQuery("#newsletterButton").click();
								} else if (e.keyCode == 9) {
									e.preventDefault();
								}
							});
						});
						jQuery(this).blur(function(){
							if(this.value == '') {
								this.value = jQuery(this).attr('title');
								jQuery(this).addClass('text-label');
							}
							jQuery(this).unbind('keyup');
							jQuery(this).unbind('keypress');
						});
					});

				});
				document.write('<b>Get Celestial Light News, Sales and Deals</b><br/>');
				document.write('<input title="your email address..." type="text" maxlength="100" style="width:170px; margin-top: 6px;" name="newsletter" id="newsletter" > <img src="/email/blank.gif" id="valid" />');
				document.write('<span id="sending" style="color: red; margin-left: 6px;">');
				document.write('</span>');
				document.write('<img id="newsletterButton" class="button" src="/images/misc/newsletterSubmit.png" />');
				document.write('</div>');
				document.write('<span id="newsletterWarning"></span>');
				document.write('</div>');
			}
			</script>
