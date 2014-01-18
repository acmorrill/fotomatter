<?php
class CacheShell extends Shell {
	
	public function clear_logo_cache() {
		//get current theme
		$this->SiteSetting = ClassRegistry::init("SiteSetting");
		$current_theme = $this->SiteSetting->getVal('current_theme', false);
		if ($current_theme == false) {
			return;
		}
		
		//delete base file
		$base_file_path = ROOT.DS.'site_logo'.DS.'base'.DS.'grezzo.png';
		if (file_exists($base_file_path)) {
			unlink($base_file_path);	
		}
		
		//delete cache versions
		$cache_folder_path = ROOT.DS.'site_logo'.DS.'caches'.DS.'grezzo';
		$cache_versions = scandir($cache_folder_path);
		
		foreach($cache_versions as $file) {
			$current_abs_path = $cache_folder_path . DS . $file;
			if (is_file($current_abs_path)) {
				unlink($current_abs_path);
			}
		}
	}
	
	
	
}