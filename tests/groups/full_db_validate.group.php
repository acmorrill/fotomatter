<?php 
class FullModelGroupTest extends TestSuite { 
  var $label = 'Full Database Validation Test'; 
  function FullModelGroupTest() { 
    TestManager::addTestCasesFromDirectory($this, APP_TEST_CASES . DS . 'validate'); 
  } 
} 
?> 