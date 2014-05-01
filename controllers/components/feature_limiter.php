<?php

class FeatureLimiterComponent extends Object {
    
	public function limit_view(&$controller, $feature_ref_name, $limit_view_name) {
		if (empty($controller->current_on_off_features[$feature_ref_name])) {
			$this->limit_view_go($controller, $limit_view_name);
		}
	}
	
	public function limit_function(&$controller, $feature_ref_name) {
		if (empty($controller->current_on_off_features[$feature_ref_name])) {
			$this->limit_function_403();
		}
	}
	
	public function limit_function_403() {
		header('HTTP/1.0 403 Forbidden');
		exit();
	}
	
	public function limit_view_go(&$controller, $limit_view_name) {
		$controller->render("/elements/admin/limit_views/$limit_view_name");
	}
	
}