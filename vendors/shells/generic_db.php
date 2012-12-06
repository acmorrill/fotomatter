<?php

class GenericDbShell extends Shell {
	public $local_db = null;
	public $global_db = null;
	
	// NOTE: don't put a $uses here -- as it can break db updates - instead use ClassRegistry::init() just before you need the model
	//public $uses = array('SiteSetting', 'DbLocalUpdate', 'DbLocalUpdateItem');
	
	
	///////////////////////////////////////////////////////////////
	/// shell start
	function _welcome() {
		Configure::write('debug', 1);

		$this->out();
		$this->out('Welcome to CakePHP v' . Configure::version() . ' Console');
		$this->hr();
		$this->out('App : '. $this->params['app']);
		$this->out('Path: '. $this->params['working']);
		$this->hr();
	}
	
	protected function _reset($is_global) {
		Configure::write('Cache.disable', true);
		$this->_connect_db();
		
		if ($is_global) {
			$connection = $this->global_db;
			$schema_path = GLOBAL_SCHEMA_PATH;
			$schema_name = 'Global';
			$schema_settings = 'ServerSetting';
		} else {
			$connection = $this->local_db;
			$schema_path = LOCAL_SCHEMA_PATH;
			$schema_name = 'Local';
			$schema_settings = 'SiteSetting';
		}
		
		$this->out(" ");
		$this->out(" ");
		$this->hr();
		$this->out("Clearing ".$schema_name." Database");
		$this->hr();
		$result = mysql_query('SHOW TABLES', $connection);
		while ($row = mysql_fetch_array($result)) {
			$this->out('    '.$row[0]);
			mysql_query('DROP TABLE `'.$row[0].'`', $connection);
		}
		$this->out("--DONE--");
		

		/////////////////////////////////////////////
		// get the latest schema version
		$last_schema_file = $this->_get_latest_schema_bypath($schema_path);
		
		///////////////////////////////////////////////
		// install the latest schema
		$this->out(" ");
		$this->hr();
		$this->out("Installing ".strtolower($schema_name)." schema: ".$last_schema_file);
		$this->hr();
		$this->out("     ".$schema_path.DS.$last_schema_file);
		$schema_parts = pathinfo($last_schema_file);
		$status = $this->_run_sql($schema_path.DS.$last_schema_file, $output, $is_global, true, null);
		if ($status !== true) {
			$this->_format_update_file_output($output);
			exit($status);
		}
		$this->_format_update_file_output($output);
		$this->out("--DONE--");

		
		// save the current schema to the database (the below model can only be used because the schema was installed above)
		$this->$schema_settings = ClassRegistry::init($schema_settings);
		$this->$schema_settings->setVal('current_schema', $schema_parts['filename']);
	}
	
	protected function _update($is_global) {
		$datasource = ConnectionManager::getDataSource('default');
		$datasource->cacheSources = false;
		Configure::write('Cache.disable', true);
		$this->_connect_db();
		
		if ($is_global) {
			$connection = $this->global_db;
			$schema_path = GLOBAL_SCHEMA_PATH;
			$schema_name = 'Global';
			$schema_settings = 'ServerSetting';
		} else {
			$connection = $this->local_db;
			$schema_path = LOCAL_SCHEMA_PATH;
			$schema_name = 'Local';
			$schema_settings = 'SiteSetting';
		}

		$this->out(" ");
		$this->out(" ");
		$this->hr();
		$this->out("RUNNING ".strtoupper($schema_name)." DB UPDATE");
		$this->hr();
		$this->out("SETTINGS");
		
		// read in the current schema
		$this->$schema_settings = ClassRegistry::init($schema_settings);
		$current_schema = $this->$schema_settings->getVal('current_schema');
		$this->out("     Installed ".$schema_name." Schema: ".$current_schema);
		
		// get the most recent schema available
		$last_avail_schemaInfo = pathinfo($this->_get_latest_schema_bypath($schema_path));
		$last_avail_schema = $last_avail_schemaInfo['filename'];
		$this->out("     Last Available ".$schema_name." Schema: ".$last_avail_schema);
		
		if ($current_schema == false) { // no schema is installed so reset maybe?
			$this->out("     No ".$schema_name." schema installed");
			exit();
			//$this->reset();
		}
		
		$scripts_folders_path = CONFIGS.'versioning'.DS.strtolower($schema_name).DS.'dev';
		
		// get the last update run (to find out where to look for new updates to run)
		$DbUpdate = ClassRegistry::init('Db'.$schema_name.'Update');
		$lastDbUpdate = $DbUpdate->find('first', array(
			'conditions' => array('Db'.$schema_name.'Update.status' => array('success','failed')),
			'order' => array('Db'.$schema_name.'Update.id DESC'),
			'contain' => false
		));
		if ($lastDbUpdate == array()) {
			$this->out("     Last ".$schema_name." Update File Run: none");
		} else {
			
			$this->out("     Last ".$schema_name." Update File Run: schema: ".$lastDbUpdate['Db'.$schema_name.'Update']['schema'].", dev: ".$lastDbUpdate['Db'.$schema_name.'Update']['dev'].", file: ".$lastDbUpdate['Db'.$schema_name.'Update']['file_name']);
		}
				
		// grab all the folders inside the schema or the schema from the last update run
		$all_updates = array();
		$schemaDirectories = scandir($scripts_folders_path);
		foreach ($schemaDirectories as $schemaDirectory) {
			if ($schemaDirectory === '.' || $schemaDirectory === '..' || $schemaDirectory === 'empty') continue;
			
			// skip updates before the install schema
			if ($schemaDirectory < $current_schema) continue;
			
			// skip updates before the schema of the last run update
			if ($lastDbUpdate != array() && $schemaDirectory < $lastDbUpdate['Db'.$schema_name.'Update']['schema']) continue;
			
			// don't use schema folders past the last available schema
			if ($schemaDirectory > $last_avail_schema) continue;
			
			$pathToSchemaFolder = $scripts_folders_path.DS.$schemaDirectory;
			if (is_dir($pathToSchemaFolder)) {
				$dirContents = scandir($pathToSchemaFolder);

				foreach ($dirContents as $dirContent) {
					if ($dirContent === '.' || $dirContent === '..' || $dirContent === 'empty') continue;

					// get the contents of each dev directory inside the schema
					$dev_dir = $pathToSchemaFolder.DS.$dirContent;
					if (is_dir($dev_dir)) {
						$devUpdateFiles = scandir($dev_dir);

						foreach ($devUpdateFiles as $devUpdateFile) {
							if ($devUpdateFile === '.' || $devUpdateFile === '..' || $devUpdateFile === 'empty') continue;

							$filePathInfo = pathinfo($dev_dir.DS.$devUpdateFile);
							$fullFilePath = $dev_dir.DS.$devUpdateFile;
							
							// add each devs files to the list
							$all_updates[] = array(
								'file' => $devUpdateFile,
								'filename' => $filePathInfo['filename'],
								'file_full_path' => $fullFilePath,
								'dev' => $dirContent,
								'schema' => $schemaDirectory,
								'ext' => $filePathInfo['extension']
							);
						}
					}
				}
			}
			
		}

		
		///////////////////////////////////////////////////////////////////////////////////////////
		// check for duplicate numbers used between devs (this is now an error condition)
		$found_nums = array();
		foreach ($all_updates as $all_update) {
			if (isset($found_nums[$all_update['filename']])) {
				$this->out(" ");
				$this->out("ERROR -- duplicate update number used.");
				$this->out("     FIRST: ".$found_nums[$all_update['filename']]);
				$this->out("     SECOND: ".$all_update['file_full_path']);
				exit();
			} 
			
			$found_nums[$all_update['filename']] = $all_update['file_full_path'];
		}
		
		
		// sort all avail updates by schema, then file name without ext, then dev alphabetical
		usort($all_updates, function($a, $b) {
			$a_schema = strtolower($a['schema']);
			$b_schema = strtolower($b['schema']);

			if ($a_schema == $b_schema) {
				$a_file = strtolower($a['filename']);
				$b_file = strtolower($b['filename']);
				
				if ($a_file == $b_file) {
					$a_dev = strtolower($a['dev']);
					$b_dev = strtolower($b['dev']);
					
					if ($a_dev == $b_dev) {
						return 0;
					}

					return ($a_dev > $b_dev) ? +1 : -1;
				}
				return ($a_file > $b_file) ? +1 : -1;
			}
			
			return ($a_schema > $b_schema) ? +1 : -1;
		});
		
		
		
		/////////////////////////////////////////////////////////////////////
		// if this is the first update -- start from the beginning
		// the last update succeded or -- so start from the next update
		// the last php file run failed -- try again because it may work this time
		$start_using_updates = false;
		$veryFirstUpdate = $lastDbUpdate == array();
		$lastUpdateSuccess = $lastDbUpdate['Db'.$schema_name.'Update']['status'] == 'success';
		$lastPhpFailed = $lastDbUpdate['Db'.$schema_name.'Update']['type'] == 'php' && $lastDbUpdate['Db'.$schema_name.'Update']['status'] == 'failed';
		$lastSqlFailed = $lastDbUpdate['Db'.$schema_name.'Update']['type'] == 'sql' && $lastDbUpdate['Db'.$schema_name.'Update']['status'] == 'failed';
		$this->out(" ");
		$this->out("UPDATES");
		if ($veryFirstUpdate || $lastUpdateSuccess || $lastPhpFailed) { 
			// if this is the first db update then just run from the beginning
			if ($veryFirstUpdate) {
				$start_using_updates = true;
			}
			
			// go through each update
			foreach ($all_updates as $all_update) {
//				$this->SiteOneLevelMenu = ClassRegistry::init('SiteOneLevelMenu');
//				$this->SiteOneLevelMenu->getDatasource()->disconnect(); 
//				$this->SiteOneLevelMenu->getDatasource()->connect();
				
				
				// am I on the update that ran last?
				$update_last_run = $lastDbUpdate != array() && $all_update['file_full_path'] == $lastDbUpdate['Db'.$schema_name.'Update']['full_file_path'];
				
				// if the last update was a php update that failed and this is that update then start from the failed one
				if (!$start_using_updates && $lastPhpFailed && $update_last_run) {
					$start_using_updates = true;
				}
				
				// run the update we're on if we have gotten to the one we want to run
				$outputStart = "     --- schema: ".$all_update['schema'].", dev: ".$all_update['dev'].", file: ".$all_update['file'];
				if ($start_using_updates) {
					if ($lastPhpFailed && $lastDbUpdate != array()) {
						// use the last update because it was a php update that failed and we want to try it again
						$data = $lastDbUpdate;
						$lastDbUpdate = array();
					} else {
						// create a new db update and set it as started
						$data['Db'.$schema_name.'Update']['file_name'] = $all_update['file'];
						$data['Db'.$schema_name.'Update']['full_file_path'] = $all_update['file_full_path'];
						$data['Db'.$schema_name.'Update']['dev'] = $all_update['dev'];
						$data['Db'.$schema_name.'Update']['type'] = $all_update['ext'];
						$data['Db'.$schema_name.'Update']['schema'] = $all_update['schema'];
						$data['Db'.$schema_name.'Update']['status'] = 'started';
						$DbUpdate->create();
						unset($data['Db'.$schema_name.'Update']['id']);
						if (!$DbUpdate->save($data)) {
							$this->out($outputStart." ... FAILED");
							$e_out = 'Failed to create Db'.$schema_name.'Update row';
							$e_out .= "     ";
							foreach($data as $d) {
								$e_out .= "     ".print_r($d, true);
							}
							$this->_format_update_file_output($e_out);
							break;
						}
						$data['Db'.$schema_name.'Update']['id'] = $DbUpdate->id;						
					}
					
					
					////////////////////////////////////////////////////
					// actually run the file and pass in the needed params 
					// if its a php file
					// save the result of running the file
					$db_update_id = null;
					if ($data['Db'.$schema_name.'Update']['type'] == 'php') {
						$db_update_id = $data['Db'.$schema_name.'Update']['id'];
					}
					
					if ($this->_run_sql($all_update['file_full_path'], $output, $is_global, ($all_update['ext'] == 'php') ? false : true, $db_update_id) === false) {
						$data['Db'.$schema_name.'Update']['status'] = 'failed';
						$DbUpdate->save($data);
						$this->out($outputStart." ... FAILED");
						$this->_format_update_file_output($output);
						break;
					} else {
						$data['Db'.$schema_name.'Update']['status'] = 'success';
						$DbUpdate->save($data);
						$this->out($outputStart." ... DONE");
						$this->_format_update_file_output($output);
					}
				} else {
					$this->out($outputStart." ... ALREADY RUN");
				}

				// if we still haven't started running updates then do so on the next 
				// iteration if this one is the last one that ran
				if (!$start_using_updates && $lastUpdateSuccess && $update_last_run) {
					$start_using_updates = true;
				}
			}
		} else if ($lastSqlFailed) { // the last one was sql and failed -- so fail again
			$outputStart = "     --- schema: ".$lastDbUpdate['Db'.$schema_name.'Update']['schema'].", dev: ".$lastDbUpdate['Db'.$schema_name.'Update']['dev'].", file: ".$lastDbUpdate['Db'.$schema_name.'Update']['file_name']." ... FAILED";
			$this->out($outputStart);
			$this->_format_update_file_output('sql update previously failed');
			exit();
		}
		
		$this->out(" ");
		$this->out(" ");
		$this->hr();
		$this->out("END DB ".strtoupper($schema_name)." UPDATE");
		$this->hr();
		
		
	}
	
	/////////////////////////////////////////////////////////////////
	// shell helper functions
	/////////////////////////////////////////////////////////////////
	protected function _run_sql($file, &$output, $is_global, $is_straight_sql = false, $db_update_id = null) {
		if ($is_global) {
			$db_settings = $this->dbconfig->server_global;
			$connection = $this->global_db;
			$type = 'Global';
		} else {
			$db_settings = $this->dbconfig->default;
			$connection = $this->local_db;
			$type = 'Local';
		}
		
		ob_start();
		if ($is_straight_sql == false) {
			$DbUpdateItem = ClassRegistry::init('Db'.$type.'UpdateItem');
			$DbUpdate = ClassRegistry::init('Db'.$type.'Update');
			$db_update = $DbUpdate->find('first', array(
				'conditions' => array('Db'.$type.'Update.id' => $db_update_id)
			));
			
			$sqls = array();
			$functions = array();
			
			unset($sqls);
			unset($functions);
			require($file);
			$fileInfo = pathinfo($file);
			
			/////////////////////
			// run the sql
			if (!isset($sqls)) {
				print('Warning: $sqls is not set in '.$file."\n");
				$output = ob_get_contents();
				ob_end_clean();
				return false;
			} else {
				$count = 0;
				foreach ($sqls as $key => $sql) {
					if ($db_update != array()) {
						$exists = array();
						// no need to check exist if nothing could have failed last time
						if ($db_update['Db'.$type.'Update']['status'] == 'failed') {
							$exists = $DbUpdateItem->find('first', array(
								'conditions' => array(
									'Db'.$type.'UpdateItem.db_'.strtolower($type).'_update_id' => $db_update['Db'.$type.'Update']['id'],
									'Db'.$type.'UpdateItem.index' => $count,
									'Db'.$type.'UpdateItem.type' => 'sql'
								)
							));
						} 

						// if local update failed last time but this item passed then move on
						if ($exists != array() && $exists['Db'.$type.'UpdateItem']['status'] == 'success') {
							$count++;
							continue;
						}

						// if local update failed last time and this item failed then run item again
						// otherwise run a new item because it has never been run
						if ($exists != array() && $exists['Db'.$type.'UpdateItem']['status'] == 'failed') {
							$data = $exists;
						} else {
							$data['Db'.$type.'UpdateItem']['db_'.strtolower($type).'_update_id'] = $db_update['Db'.$type.'Update']['id'];
							$data['Db'.$type.'UpdateItem']['type'] = 'sql';
							$data['Db'.$type.'UpdateItem']['status'] = 'started';
							$data['Db'.$type.'UpdateItem']['index'] = $count;
							$DbUpdateItem->create();
							unset($data['Db'.$type.'UpdateItem']['id']);
							if (!$DbUpdateItem->save($data)) {
								print("failed to save data for sql\n");
								print("     sql: ".$sql."\n");
								print("     data: \n");
								foreach($data as $d) {
									print("         ".print_r($d, true));
								}
								print("\n");
								$output = ob_get_contents();
								ob_end_clean();
								return false;
							}
						}
					}
					

					mysql_query($sql, $connection);
					if (mysql_error($connection)) {
						if ($db_update != array()) {
							$data['Db'.$type.'UpdateItem']['status'] = 'failed';
							$DbUpdateItem->save($data);
						}
						print("sql failed\n");
						print("     ".$sql."\n");
						print("     ".$file."\n");
						print("     ".mysql_error($connection)."\n");
						$output = ob_get_contents();
						ob_end_clean();
						return false;
					}

					if ($db_update != array()) {
						$data['Db'.$type.'UpdateItem']['status'] = 'success';
						$DbUpdateItem->save($data);
					}
					
					$count++;
				}
			}
			
			/////////////////////////
			// run the functions
			if (!isset($functions)) {
				print('Warning: $functions is not set in '.$file."\n");
				$output = ob_get_contents();
				ob_end_clean();
				return false;
			} else {
				$count = 0;
				foreach ($functions as $function) {
					if ($db_update != array()) {
						$exists = array();
						// no need to check exist if nothing could have failed last time
						if ($db_update['Db'.$type.'Update']['status'] == 'failed') {
							$exists = $DbUpdateItem->find('first', array(
								'conditions' => array(
									'Db'.$type.'UpdateItem.db_'.strtolower($type).'_update_id' => $db_update['Db'.$type.'Update']['id'],
									'Db'.$type.'UpdateItem.index' => $count,
									'Db'.$type.'UpdateItem.type' => 'func'
								)
							));
						} 

						// if local update failed last time but this item passed then move on
						if ($exists != array() && $exists['Db'.$type.'UpdateItem']['status'] == 'success') {
							$count++;
							continue;
						}

						// if local update failed last time and this item failed then run item again
						// otherwise run a new item because it has never been run
						if ($exists != array() && $exists['Db'.$type.'UpdateItem']['status'] == 'failed') {
							$data = $exists;
						} else {
							$data['Db'.$type.'UpdateItem']['db_'.strtolower($type).'_update_id'] = $db_update['Db'.$type.'Update']['id'];
							$data['Db'.$type.'UpdateItem']['type'] = 'func';
							$data['Db'.$type.'UpdateItem']['status'] = 'started';
							$data['Db'.$type.'UpdateItem']['index'] = $count;
							$DbUpdateItem->create();
							unset($data['Db'.$type.'UpdateItem']['id']);
							if (!$DbUpdateItem->save($data)) {
								print("failed to save data for func\n");
								print("     func: ".$count."\n");
								print("     data: \n");
								foreach($data as $d) {
									print("         ".print_r($d, true));
								}
								print("\n");
								$output = ob_get_contents();
								ob_end_clean();
								return false;
							}
						}
					}


					$result = $function();
					if ($result !== true) {
						if ($db_update != array()) {
							$data['Db'.$type.'UpdateItem']['status'] = 'failed';
							$DbUpdateItem->save($data);
						}
						print("function did not return true\n");
						print("     function number $count in\n");
						print("     ".$file."\n");
						$output = ob_get_contents();
						ob_end_clean();
						return false;
					}

					if ($db_update != array()) {
						$data['Db'.$type.'UpdateItem']['status'] = 'success';
						$DbUpdateItem->save($data);
					}
					
					$count++;
				}
			}
		} else {
			$command = 'mysql -h'.$db_settings['host']
					.' -u'.$db_settings['login']
					.' -p'.$db_settings['password']
					.' '.$db_settings['database'].' < ' .$file;
			
			exec($command." 2>&1", $exec_output, $worked);
			switch ($worked) {
				case 0:
					break;
				case 1:
					print("failed to import to database.\n");
					print("     ".$file."\n");
					print("     --- EXEC OUTPUT --- \n");
					foreach ($exec_output as $exec_out) {
						print("     ".$exec_out);
					}
					print("\n     --- END EXEC OUTPUT --- \n");
					$output = ob_get_contents();
					ob_end_clean();
					return false;
					break;
			}
		}
		
		$output = ob_get_contents();
		ob_end_clean();
		return true;
	}
	
	protected function _connect_db() {
		if ($this->local_db == null || !mysql_ping($this->local_db)) {
			require_once(CONFIGS.'database.php');
			$this->dbconfig = new DATABASE_CONFIG();

			$this->local_db = mysql_connect($this->dbconfig->default['host'], $this->dbconfig->default['login'], $this->dbconfig->default['password'], true);
			if (mysql_error($this->local_db)) {
				$this->out("Cannot connect to local db. Check config, and try again.");
				exit(1);
			}

			mysql_select_db($this->dbconfig->default['database'], $this->local_db);
			if (mysql_error($this->local_db)) {
				$this->out("Cannot select local db. Check config, and try again.");
				exit(2);
			}

			$this->global_db = mysql_connect($this->dbconfig->server_global['host'], $this->dbconfig->server_global['login'], $this->dbconfig->server_global['password'], true);
			if (mysql_error($this->global_db)) {
				$this->out("Cannot connect to global db. Check config, and try again.");
				exit(1);
			}

			mysql_select_db($this->dbconfig->server_global['database'], $this->global_db);
			if (mysql_error($this->global_db)) {
				$this->out("Cannot select global db. Check config, and try again.");
				exit(2);
			}
		}
	}
	
	protected function _global_db_installed() {
		$this->_connect_db();
		$sql = "SELECT value FROM server_settings WHERE name = 'current_schema'";
		
		$val = mysql_query($sql, $this->global_db);
		if (!mysql_error($this->global_db)) {
			return true;
		} else {
			return false;
		}
	}
	
	protected function _format_update_file_output($output) {
		if (!empty($output)) {
			$output = split("[\n\r]", $output);
			if (count($output) > 0) {
				$this->out("         ---------------------------------------------------------------");
				$this->out("         --- OUTPUT ---");
				$this->out("         ---------------------------------------------------------------");
			}
			foreach ($output as $out) {
				$this->out("             ".$out);
			}
			if (count($output) > 0) {
				$this->out("         ---------------------------------------------------------------");
				$this->out(" ");
			}
		}
	}
	
	protected function _get_latest_schema_bypath($path) {
		$handle = opendir($path);
		$schema_files = array();
		if ($handle) {
			while (false !== ($entry = readdir($handle))) {
				if (!is_dir($path.DS.$entry) && ($entry != '.' || $entry != '..')) {
					$schema_files[] = $entry;
				}
			}
			closedir($handle);
		}
		usort($schema_files, 'version_compare');
		$last_schema_file = end($schema_files);
		reset($schema_files);
		
		return $last_schema_file;
	}
}
