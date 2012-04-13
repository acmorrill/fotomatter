<?php
class ModelHelperObj {
    
    protected function _record_real_error($description, $extra_data = null, $severity = 'normal') {
	if (empty($this->MajorError)) $this->MajorError = ClassRegistry::Init("MajorError");
	$this->MajorError->setDataSource('default');
	$this->MajorError->major_error($description, $extra_data, $severity);
    }
}