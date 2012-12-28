<?php
class LessCssComponent extends Object {
	
	public function initialize(&$controller, $settings=array()) {
		$this->controller = $controller;
	}
	
	public function recompile_css() {
		App::import('Vendor', 'LessPhp', array('file' => 'lessphp'.DS.'lessc.inc.php'));

		$this->LessPhp = new lessc();
		
		$webroot_css_path = WEBROOT_ABS.DS.'css';
		$less_css_root_path = LESSCSS_ROOT;
		
		$dir = new DirectoryIterator($less_css_root_path);
		foreach ($dir as $fileinfo) {
			if ($fileinfo->getExtension() == 'less') {
				$less_file_full_path = $less_css_root_path.DS.$fileinfo->getFilename();
				$new_css_full_path = $webroot_css_path.DS.$fileinfo->getBasename('.less').'.css';
				
				$this->LessPhp->checkedCompile($less_file_full_path, $new_css_full_path);
			}
		}
	}
	
}