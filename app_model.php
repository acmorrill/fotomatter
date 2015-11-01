<?php
App::import('Lib', 'LazyModel.LazyModel');

/**
 * General app-wide Model Overrides
 *
 * @package Precious
 */
class AppModel extends LazyModel { // a change - even more change
	public $actsAs = array(
		'Containable'
	);
	
	public function invalidate_and_clear_view_cache() {
		// invalide the view cache opcaches
		$dirp = opendir(VIEW_CACHE_PATH);
		if ($dirp) {
			while (FALSE !== ($file = readdir($dirp))) {
				if ($file == '.' || $file == '..') continue;
				opcache_invalidate(VIEW_CACHE_PATH . '/' . $file);
			}
			closedir($dirp);
		}
		//end invalide the view cache opcaches

		// clear cake view cache for site
		clearCache();
	}
	
	public function recursive_remove_directory($directory, $empty=FALSE) {
		// if the path has a slash at the end we remove it here
		if(substr($directory,-1) == '/')
		{
			$directory = substr($directory,0,-1);
		}

		// if the path is not valid or is not a directory ...
		if(!file_exists($directory) || !is_dir($directory))
		{
			// ... we return false and exit the function
			return FALSE;

		// ... if the path is not readable
		}elseif(!is_readable($directory))
		{
			// ... we return false and exit the function
			return FALSE;

		// ... else if the path is readable
		}else{

			// we open the directory
			$handle = opendir($directory);

			// and scan through the items inside
			while (FALSE !== ($item = readdir($handle)))
			{
				// if the filepointer is not the current directory
				// or the parent directory
				if($item != '.' && $item != '..')
				{
					// we build the new path to delete
					$path = $directory.'/'.$item;

					// if the new path is a directory
					if(is_dir($path)) 
					{
						// we call this function with the new path
						recursive_remove_directory($path);

					// if the new path is a file
					}else{
						// we remove the file
						unlink($path);
					}
				}
			}
			// close the directory
			closedir($handle);

			// if the option to empty is not set to true
			if($empty == FALSE)
			{
				// try to delete the now empty directory
				if(!rmdir($directory))
				{
					// return false if not possible
					return FALSE;
				}
			}
			// return success
			return TRUE;
		}
	}
	
	public function get_session() {
		if (!isset($this->Session)) {
			App::import('Component', 'SessionComponent'); 
			$this->Session = new SessionComponent(); 
		}
		
		return $this->Session;
	}
	
	
	public function get_insult() {
		$insults = array();
		
		$insults[] = 'You really suck!';
		$insults[] = 'Maybe you should just kill yourself';
		$insults[] = 'A day late and a dollar short';
		$insults[] = 'A donut short of being a cop';
		$insults[] = 'Made a career out of a midlife crisis';
		
		return $insults[rand(0, count($insults)-1)];
	}
	
	public function send_fotomatter_email($function_name) {
		App::import('Core', 'Controller'); 
		App::import('Controller','Domains');
		$this->DomainsController = new DomainsController();
		$this->DomainsController->constructClasses();
		$this->DomainsController->Postmark->initialize($this->DomainsController);
		$function_args = func_get_args();
		$function_args[0] = &$this->DomainsController;
		call_user_func_array(array($this->DomainsController->FotomatterEmail, $function_name), $function_args);
	}
	
	/*public function beforeFind($conditions) {
		if ( !isset($conditions['contain']) ) {
			$conditions['contain'] = false;
			$conditions['recursive'] = -1;
		}
		
		return $conditions;
	}*/
	
	
	/**
	 *
	 * @param type $description
	 * @param type $extra_data
	 * @param type $severity // low, normal, high
	 */
	public function major_error($description, $extra_data = null, $severity = 'normal') { // low, normal, high
		$this->SiteSetting = ClassRegistry::init('SiteSetting');
		
		$stackTrace = debug_backtrace(false);
		
		$majorError = ClassRegistry::init("MajorError");
		
		$data['MajorError']['account_id'] = $this->SiteSetting->getVal('account_id');
		$location = '';
		if (isset($stackTrace[1]['class'])) {
			$location .= " --- Class: ".$stackTrace[1]['class']." --- ";
		}
		if (isset($stackTrace[1]['function'])) {
			$location .= " --- Function: ".$stackTrace[1]['function']." --- ";
		}
		$data['MajorError']['location'] = $location;
		$data['MajorError']['line_num'] = isset($stackTrace[1]['line']) ? $stackTrace[1]['line']: 1;
		$data['MajorError']['description'] = $description;
		if ($extra_data != null) {
			$data['MajorError']['extra_data'] = print_r($extra_data, true);
		}
		$data['MajorError']['severity'] = $severity;
		$majorError->create();
		$majorError->save($data);
		
		return $description;
	}
	
	
	/*********************************************************
	 * HELPER FUNCTIONS
	 * 
	 */
	protected function random_num($n=5) {
		return rand(0, pow(10, $n));
	}
	
	public function startsWith($haystack, $needle) {
		$length = strlen($needle);
		return (substr($haystack, 0, $length) === $needle);
	}

	public function endsWith($haystack, $needle) {
		$length = strlen($needle);
		$start  = $length * -1; //negative
		return (substr($haystack, $start) === $needle);
	}
	
	protected function number_pad($number,$n) {
		return str_pad((int) $number,$n,"0",STR_PAD_LEFT);
	}
	
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// locking functions
	//-----------------------------------------------------------------------------------------------------------
	public function get_lock($lock_name, $wait_time, $do_sleep = false) {
		if (php_sapi_name() == 'cli') {
			return $this->get_file_lock($lock_name, $wait_time, $do_sleep);
		} else {
			return $this->get_apc_lock($lock_name, $wait_time, $do_sleep);
		}
	}
	
	public function release_lock($lock_name) {
		if (php_sapi_name() == 'cli') {
			return $this->release_file_lock($lock_name);
		} else {
			return $this->release_apc_lock($lock_name);
		}
	}
	
	private function get_file_lock($lock_name, $wait_time, $do_sleep = false) {
		$time_left = $wait_time;
		
		while ($time_left > 0) {
			$file_name = APP . "locks/" . $this->get_lock_key($lock_name) . ".lock";
			if ( file_exists($file_name) ) {
				$file_modified_time = shell_exec("stat -c %Y $file_name");
				if (!empty($file_modified_time)) {
					$time_since_touched = time() - $file_modified_time;
					if ( $time_since_touched < $wait_time ) {
						if ($do_sleep === true) {
							if ($time_left - 1 <= 0) {
								return false;
							}
							$time_left -= 1;
							sleep(1);
						} else {
							return false;
						}
					} else {
						break;
					}
				} else {
					break;
				}
			} else {
				break;
			}
		}
		
		shell_exec("touch $file_name");
		
		return true;
	}
	private function release_file_lock($lock_name) {
		$file_name = APP . "locks/" . $this->get_lock_key($lock_name) . ".lock";
		@unlink($file_name);
	}
	
	private function get_apc_lock($lock_name, $wait_time, $do_sleep = false) {
		$time_left = $wait_time;
		$apc_key = $this->get_lock_key($lock_name);
		
		while ($time_left > 0) {
			if (apc_exists($apc_key)) {
				if ($do_sleep === true) {
					if ($time_left - 1 <= 0) {
						return false;
					}
					$time_left -= 1;
					sleep(1);
				} else {
					return false;
				}
			} else {
				break;
			}
		}
		apc_store($apc_key, true, $wait_time);
		
		return true;
	}

	private function release_apc_lock($lock_name) {
		$apc_key = $this->get_lock_key($lock_name);
		apc_delete($apc_key);
	}
	
	private function get_lock_key($lock_name) {
		return "fmapp_lock_$lock_name";
	}
	
	public function get_website_disabled_key() {
		return "disabled_site_{$_SERVER['local']['database']}";
	}
	
	public function disable_website() {
		$apc_key = $this->get_website_disabled_key();
		apc_store($apc_key, true, 600);
	}
	
	public function enable_website() {
		$apc_key = $this->get_website_disabled_key();
		apc_delete($apc_key);
	}
	
	public function get_photo_cache_disabled_key() {
		return "disabled_photo_cache_{$_SERVER['local']['database']}";
	}
	
	public function is_photo_cache_disabled() {
		$apc_key = $this->get_photo_cache_disabled_key();
		return apc_exists($apc_key);
	}
	
	public function get_disabled_photo_cache_percent() {
		$apc_key = $this->get_photo_cache_disabled_key();
		if (apc_exists($apc_key) === false) {
			return false;
		}
		return apc_fetch($apc_key);
	}
	
	public function disable_photo_cache($percent_done) {
		$apc_key = $this->get_photo_cache_disabled_key();
		apc_store($apc_key, $percent_done, 600);
	}
	
	public function enable_photo_cache() {
		$apc_key = $this->get_photo_cache_disabled_key();
		apc_delete($apc_key);
	}
	
}