<?php

class FeatureLimiterComponent extends Object {
    
	public function limit_view(&$controller, $feature_ref_name, $limit_view_name) {
		if (empty($controller->current_on_off_features[$feature_ref_name])) {
			$controller->render("/elements/admin/limit_views/$limit_view_name");
		}
	}
	
	public function limit_function(&$controller, $feature_ref_name) {
		if (empty($controller->current_on_off_features[$feature_ref_name])) {
			header('HTTP/1.0 403 Forbidden');
			exit();
		}
	}
	
}