<?php
class MajorError extends AppModel {
    var $name = 'MajorError';
	public $useDbConfig = 'server_global';
	public $lock_name = 'aggregate_server_major_errors';

	
	public function aggragate_errors() {
		if ($this->get_lock($this->lock_name, 30) === false) {
			$this->major_error('failed to aggregate majors errors because of lock - so ironic!');
			return false;
		}
			$aggragate_query = "
				INSERT INTO major_error_aggragate (description, error_date, severity, count)
					SELECT description, created, severity, COUNT(1) AS count
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
		$this->release_lock($this->lock_name);
		
		return true;
	}
	
	
	public function create_fake_major_errors() {
		$severities = array(
			'low',
			'normal',
			'high'
		);
		
		$dates = array(
			date('Y-m-d H:i:s', strtotime('-1 day')),
//			date('Y-m-d H:i:s', strtotime('now')),
			date('Y-m-d H:i:s', strtotime('+1 day')),
		);
		
		
		for ($i = 0; $i < 3000; $i++) {
			$new_major_error = array();
			$new_major_error['MajorError']['account_id'] = rand(100, 110);
			$new_major_error['MajorError']['location'] = 'somewhere';
			$new_major_error['MajorError']['line_num'] = rand(1, 2000);
			$new_major_error['MajorError']['description'] = 'random error '.rand(0, 1000);
			$new_major_error['MajorError']['extra_data'] = 'some extra data';
			$new_major_error['MajorError']['severity'] = $severities[rand(0, 2)];
			$new_major_error['MajorError']['created'] = $dates[rand(0, 1)];
			$this->create();
			$this->save($new_major_error);
		}
		
	}
}
