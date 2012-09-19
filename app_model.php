<?php
App::import('Lib', 'LazyModel.LazyModel');

/**
 * General app-wide Model Overrides
 *
 * @package Precious
 */
class AppModel extends LazyModel {
	public $actsAs = array(
		'Containable'
	);
	
	
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
	
	
	public function get_insult() {
		$insults = array();
		
		$insults[] = 'You really suck!';
		$insults[] = 'Maybe you should just kill yourself';
		$insults[] = 'A day late and a dollar short';
		$insults[] = 'A donut short of being a cop';
		$insults[] = 'Made a career out of a midlife crisis';
		
		return $insults[rand(0, count($insults)-1)];
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
	 * @param type $severity 
	 */
	public function major_error($description, $extra_data = null, $severity = 'normal') {
		$stackTrace = debug_backtrace(false);
		
		$majorError = ClassRegistry::init("MajorError");
		
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
}