<?php
class MajorError extends AppModel {
    var $name = 'MajorError';
	public $useDbConfig = 'server_global';

	
	public function aggragate_errors() {
		// DREW TODO - START HERE TOMORROW - now move the aggragate data to overlord
		// DREW TODO - this could use transactions, but who cares?? :)
		$aggragate_query = "
			INSERT INTO major_error_aggragate (description, severity, count)
				SELECT description, severity, COUNT(1) AS count
				FROM major_errors
				WHERE aggregated = 0
				GROUP BY description
			ON DUPLICATE KEY UPDATE
				count = count + values(count);
		";
		$this->query($aggragate_query);
		
		$mark_query = "
			UPDATE major_errors SET aggregated = 1 WHERE aggregated = 0
		";
		$this->query($mark_query);
	}
	
	
	public function create_fake_major_errors() {
		$severities = array(
			'low',
			'normal',
			'high'
		);
		
		
		
		for ($i = 0; $i < 3000; $i++) {
			$new_major_error = array();
			$new_major_error['MajorError']['account_id'] = rand(100, 110);
			$new_major_error['MajorError']['location'] = 'somewhere';
			$new_major_error['MajorError']['line_num'] = rand(1, 2000);
			$new_major_error['MajorError']['description'] = 'random error '.rand(0, 1000);
			$new_major_error['MajorError']['extra_data'] = 'some extra data';
			$new_major_error['MajorError']['severity'] = $severities[rand(0, 2)];
			$this->create();
			$this->save($new_major_error);
		}
		
	}
}
