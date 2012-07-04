<?php
class Hash extends AppModel {
	
	public function generate_and_return_hash($name_space) {
		//delete any hashes that are currently there
		if ($this->DeleteAll(array(
			'Hash.name_space'=>$name_space
		))=== false) {
			$this->major_error("Deleting old hash failed.", $name_space);
			return false;
		}
		
		$new_hash['Hash']['name_space'] = $name_space;
		$new_hash['Hash']['hash'] = md5(time());
		
		$this->create();
		if ($this->save($new_hash) === false) {
			$this->major_error("Adding new hash failed.", $name_space);
			return false;
		}
		return $new_hash['Hash']['hash'];
	}
	
	public function check_this_hash($hash, $name_space) {
		$result = $this->query("SELECT
									count(id) as id
								FROM
									hashes as Hash
								WHERE
									Hash.hash = ?
									AND
									Hash.name_space = ?", array($hash, $name_space));
		if ($result[0][0]['id'] == 1) {
			return true;
		} else {
			return false;
		}
	}
}