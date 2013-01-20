<?php

class WhiteAngularHelper extends AppHelper {
	
	public function get_image_center_y($x) {
		return ((-395/318)*$x) + 150;
	}
	
	public function get_image_center_x($y) {
		return ((-318/395)*$y) + (9540/79);
	}
	
}