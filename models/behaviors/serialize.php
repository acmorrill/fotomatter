<?php
class SerializeBehavior extends ModelBehavior {
	var $settings = array();

	function setup(&$model, $settings) {
		if (!isset($this->settings[$model->alias])) {
			$this->settings[$model->alias] = array(
				'fields' => array()
			);
		}

		$this->settings[$model->alias] = array_merge($this->settings[$model->alias], $settings);
	}

	function afterFind(&$model, $results, $primary) {
		$results = $model->afterFind($results, true);
		foreach ($this->settings[$model->alias]['fields'] AS $field) {
			if ($primary) {
				foreach ($results AS $key => $value) {
					if (isset($value[$model->alias][$field])) {
						$results[$key][$model->alias][$field] = unserialize($value[$model->alias][$field]);
					}
				}
			} else {
				if (isset($results[$field])) {
					$results[$field] = unserialize($results[$field]);
				}
			}
		}
		
		$this->log('came here 1', 'afterFind');
		return $results;
	}

	function beforeSave(&$model) {
		foreach ($this->settings[$model->alias]['fields'] AS $field) {
			if (isset($model->data[$model->alias][$field])) {
				$model->data[$model->alias]['original_'.$field] = $model->data[$model->alias][$field];
				$model->data[$model->alias][$field] = serialize($model->data[$model->alias][$field]);
			}
		}
		return true;
	}
}