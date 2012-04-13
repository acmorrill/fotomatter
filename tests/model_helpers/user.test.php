<?php
require_once(ROOT.DS.'app'.DS.'tests'.DS.'model_helpers'.DS.'model_helper_obj.php');
class UserTestCaseHelper extends ModelHelperObj {
    
    function __construct() {
	$this->User = ClassRegistry::init("User");
    }
    
}
?>
