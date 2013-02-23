<?php
class EcommerceHelper extends AppHelper {
	
	public function print_size_has_non_pano($print_type) {
		return (isset($print_type['PhotoAvailSize']['photo_format_ids']) && strpos($print_type['PhotoAvailSize']['photo_format_ids'], '1,2,3') !== false);
	}
	
	public function print_size_has_pano($print_type) {
		return (isset($print_type['PhotoAvailSize']['photo_format_ids']) && strpos($print_type['PhotoAvailSize']['photo_format_ids'], '4,5') !== false);
	}
	
}