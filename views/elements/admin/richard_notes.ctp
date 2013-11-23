<script type="text/javascript">
	function setCookie(c_name, value, exdays) {
		var exdate=new Date();
		exdate.setDate(exdate.getDate() + exdays);
		var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
		document.cookie=c_name + "=" + c_value;
	}
	function getCookie(c_name) {
		var c_value = document.cookie;
		var c_start = c_value.indexOf(" " + c_name + "=");
		if (c_start == -1) {
			c_start = c_value.indexOf(c_name + "=");
		}
		if (c_start == -1) {
			c_value = null;
		} else {
			c_start = c_value.indexOf("=", c_start) + 1;
			var c_end = c_value.indexOf(";", c_start);
			if (c_end == -1) {
				c_end = c_value.length;
			}
			c_value = unescape(c_value.substring(c_start,c_end));
		}
		return c_value;
	}
	
	function checkCookie() {
		var username=getCookie("username");
		if (username!=null && username!=""){
			alert("Welcome again " + username);
		} else {
			username=prompt("Please enter your name:","");
			if (username!=null && username!="") {
				setCookie("username",username,365);
			}
		}
	}
	
	jQuery(document).ready(function() {
		var richard_notes = jQuery('.richard_notes');
		richard_notes.parent().css('position', 'relative');
		
		var current_cookie = getCookie('r_toggle');
		if (current_cookie == 'on') {
			richard_notes.toggle();
		} 
		jQuery('.richard_notes_toggle').click(function() {
			richard_notes.toggle();
			if (richard_notes.is(':visible')) {
				setCookie('r_toggle', 'on', 100);
			} else {
				setCookie('r_toggle', 'off', 100);
			}
		});
	});
</script>
<div class="richard_notes">
	<?php echo $html; ?>
</div>
<div class="richard_notes_toggle"><span>RH</span></div>





















